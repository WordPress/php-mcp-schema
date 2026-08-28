<?php

declare(strict_types=1);

namespace WP\McpSchema\Internal;

use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Record;

/**
 * Bounded interpreter for the JSON Schema vocabulary used by supported MCP revisions.
 *
 * @internal
 */
final class SchemaInterpreter
{
    /** @var array<string, array<string, mixed>> */
    private $definitions;

    /** @var string */
    private $revision;

    /** @var array<string, array{definition: string, versions: array<int, string>}> */
    private $records;

    /** @var \Closure */
    private $recordFactory;

    /**
     * @param array<string, array<string, mixed>> $definitions
     * @param array<string, array{definition: string, versions: array<int, string>}> $records
     */
    public function __construct(array $definitions, string $revision, array $records)
    {
        $this->definitions = $definitions;
        $this->revision    = $revision;
        $this->records     = $records;

        $factory = \Closure::bind(
            /**
             * @param class-string<Record> $class
             * @param array<string, mixed> $values
             * @param array<int, string>   $declaredFields
             */
            static function (string $class, array $values, array $declaredFields): Record {
                /** @var Record $record */
                $record = new $class($values, $declaredFields);

                return $record;
            },
            null,
            Record::class
        );
        if (! $factory instanceof \Closure) {
            throw new \LogicException('Unable to bind the private record hydrator.');
        }
        $this->recordFactory = $factory;
    }

    /**
     * @param class-string $rootClass
     * @param mixed        $value
     * @return object
     */
    public function hydrate(string $definition, string $rootClass, $value, bool $programmaticArrays)
    {
        $normalizer = new InputNormalizer($programmaticArrays);
        $normalized = $normalizer->normalize($value);
        $result     = $this->evaluateDefinition($definition, $normalized, '', 0, $rootClass, $programmaticArrays);
        if (! is_object($result) || ! $result instanceof $rootClass) {
            throw new \LogicException(sprintf('Hydrated %s does not implement requested root %s.', $definition, $rootClass));
        }

        return $result;
    }

    /**
     * @param mixed             $value
     * @param class-string|null $preferredClass
     * @return mixed
     */
    private function evaluateDefinition(
        string $name,
        $value,
        string $pointer,
        int $depth,
        ?string $preferredClass,
        bool $programmaticArrays
    ) {
        if (! isset($this->definitions[$name])) {
            throw new \LogicException(sprintf('Catalog reference points to unknown definition %s.', $name));
        }
        /** @var class-string<Record>|null $recordClass */
        $recordClass = $preferredClass !== null && isset($this->records[$preferredClass])
            ? $preferredClass
            : $this->recordClassForDefinition($name);
        if ($recordClass !== null) {
            return $this->evaluateObject(
                $this->objectShape($this->definitions[$name], array($name)),
                $value,
                $pointer,
                $depth,
                $recordClass,
                $programmaticArrays
            );
        }

        return $this->evaluateSchema(
            $this->definitions[$name],
            $value,
            $pointer,
            $depth,
            $programmaticArrays
        );
    }

    /**
     * @param array<string, mixed> $schema
     * @param mixed                $value
     * @return mixed
     */
    private function evaluateSchema(
        array $schema,
        $value,
        string $pointer,
        int $depth,
        bool $programmaticArrays
    ) {
        if ($depth > InputNormalizer::MAX_DEPTH) {
            throw new ValidationException($pointer, 'Schema evaluation exceeds the maximum nesting depth.');
        }

        if (isset($schema['$ref'])) {
            /** @var string $reference */
            $reference = $schema['$ref'];
            $name   = $this->referenceName($reference);
            $result = $this->evaluateDefinition($name, $value, $pointer, $depth, null, $programmaticArrays);
            $siblings = array_diff_key($schema, array('$ref' => true, 'description' => true));
            if ($siblings !== array()) {
                $this->evaluateSchema($siblings, $value, $pointer, $depth, $programmaticArrays);
            }

            return $result;
        }

        if (isset($schema['anyOf'])) {
            /** @var array<int, array<string, mixed>> $members */
            $members = $schema['anyOf'];
            foreach ($members as $member) {
                try {
                    return $this->evaluateSchema($member, $value, $pointer, $depth, $programmaticArrays);
                } catch (ValidationException $exception) {
                    // Continue in canonical union order.
                }
            }
            throw new ValidationException($pointer, 'Value does not match any allowed union member.');
        }

        if ($this->constrainsObject($schema, array())) {
            return $this->evaluateObject(
                $this->objectShape($schema, array()),
                $value,
                $pointer,
                $depth,
                null,
                $programmaticArrays
            );
        }

        if (isset($schema['allOf'])) {
            /** @var array<int, array<string, mixed>> $members */
            $members = $schema['allOf'];
            $result  = $this->materializeAny($value);
            foreach ($members as $member) {
                $result = $this->evaluateSchema(
                    $member,
                    $value,
                    $pointer,
                    $depth,
                    $programmaticArrays
                );
            }
            $siblings = array_diff_key($schema, array('allOf' => true, 'description' => true));
            if ($siblings !== array()) {
                $result = $this->evaluateSchema(
                    $siblings,
                    $value,
                    $pointer,
                    $depth,
                    $programmaticArrays
                );
            }

            return $result;
        }

        if (array_key_exists('const', $schema) && ! self::jsonEquals($value, $schema['const'])) {
            throw new ValidationException($pointer, sprintf('Value must equal %s.', (string) json_encode($schema['const'])));
        }
        if (isset($schema['enum'])) {
            /** @var array<int, mixed> $enum */
            $enum = $schema['enum'];
            $matched = false;
            foreach ($enum as $allowed) {
                if (self::jsonEquals($value, $allowed)) {
                    $matched = true;
                    break;
                }
            }
            if (! $matched) {
                throw new ValidationException($pointer, 'Value is not one of the allowed enum members.');
            }
        }

        if (isset($schema['type']) && ! $this->matchesType($value, $schema['type'], $programmaticArrays)) {
            /** @var string|array<int, string> $schemaType */
            $schemaType = $schema['type'];
            $expected = is_array($schemaType) ? implode('|', $schemaType) : $schemaType;
            throw new ValidationException($pointer, sprintf('Expected %s, got %s.', $expected, self::valueType($value)));
        }
        if (isset($schema['minimum'])) {
            /** @var int|float $minimum */
            $minimum = $schema['minimum'];
            if (! is_int($value) && ! is_float($value) || $value < $minimum) {
                throw new ValidationException($pointer, sprintf('Number must be at least %s.', (string) $minimum));
            }
        }
        if (isset($schema['maximum'])) {
            /** @var int|float $maximum */
            $maximum = $schema['maximum'];
            if (! is_int($value) && ! is_float($value) || $value > $maximum) {
                throw new ValidationException($pointer, sprintf('Number must be at most %s.', (string) $maximum));
            }
        }

        $types = isset($schema['type']) ? (array) $schema['type'] : array();
        if (in_array('array', $types, true)) {
            if (! is_array($value) || ! InputNormalizer::isList($value)) {
                throw new ValidationException($pointer, 'Expected a JSON list.');
            }
            if (isset($schema['maxItems'])) {
                /** @var int $maxItems */
                $maxItems = $schema['maxItems'];
                if (count($value) > $maxItems) {
                    throw new ValidationException($pointer, sprintf('List must contain at most %d items.', $maxItems));
                }
            }
            $output = array();
            foreach ($value as $index => $item) {
                if (isset($schema['items'])) {
                    /** @var array<string, mixed> $itemSchema */
                    $itemSchema = $schema['items'];
                    $output[] = $this->evaluateSchema(
                        $itemSchema,
                        $item,
                        InputNormalizer::appendPointer($pointer, (string) $index),
                        $depth + 1,
                        $programmaticArrays
                    );
                } else {
                    $output[] = $this->materializeAny($item);
                }
            }

            return $output;
        }

        return $this->materializeAny($value);
    }

    /**
     * @param array{properties: array<string, array<string, mixed>>, required: array<string, true>, additional: mixed} $shape
     * @param mixed $value
     * @param class-string<Record>|null $recordClass
     * @return Record|\stdClass
     */
    private function evaluateObject(
        array $shape,
        $value,
        string $pointer,
        int $depth,
        ?string $recordClass,
        bool $programmaticArrays
    ) {
        if ($value instanceof \stdClass) {
            $fields = get_object_vars($value);
        } elseif (
            $programmaticArrays &&
            is_array($value) &&
            ($value === array() || ! InputNormalizer::isList($value))
        ) {
            $fields = array();
            foreach ($value as $key => $item) {
                $fields[(string) $key] = $item;
            }
        } else {
            throw new ValidationException($pointer, sprintf('Expected object, got %s.', self::valueType($value)));
        }

        foreach ($shape['required'] as $field => $_required) {
            if (! array_key_exists($field, $fields)) {
                throw new ValidationException(
                    InputNormalizer::appendPointer($pointer, $field),
                    'Required field is missing.'
                );
            }
        }

        $hydrated = array();
        foreach ($fields as $field => $item) {
            $fieldPointer = InputNormalizer::appendPointer($pointer, (string) $field);
            if (isset($shape['properties'][$field])) {
                $hydrated[$field] = $this->evaluateSchema(
                    $shape['properties'][$field],
                    $item,
                    $fieldPointer,
                    $depth + 1,
                    $programmaticArrays
                );
                continue;
            }
            if ($shape['additional'] === false) {
                throw new ValidationException($fieldPointer, 'Additional field is not allowed.');
            }
            if (is_array($shape['additional']) && $shape['additional'] !== array()) {
                $hydrated[$field] = $this->evaluateSchema(
                    $shape['additional'],
                    $item,
                    $fieldPointer,
                    $depth + 1,
                    $programmaticArrays
                );
            } else {
                $hydrated[$field] = $this->materializeAny($item);
            }
        }

        $declared = array_keys($shape['properties']);
        if ($recordClass !== null) {
            /** @var Record $record */
            $record = ($this->recordFactory)($recordClass, $hydrated, $declared);

            return $record;
        }

        $output = new \stdClass();
        foreach ($hydrated as $field => $item) {
            $output->{$field} = $item;
        }

        return $output;
    }

    /**
     * @param array<string, mixed> $schema
     * @param array<int, string>   $seen
     * @return array{properties: array<string, array<string, mixed>>, required: array<string, true>, additional: mixed, additionalSpecified: bool}
     */
    private function objectShape(array $schema, array $seen): array
    {
        $shape = array(
            'properties'          => array(),
            'required'            => array(),
            'additional'          => true,
            'additionalSpecified' => false,
        );
        if (isset($schema['$ref'])) {
            /** @var string $reference */
            $reference = $schema['$ref'];
            $name = $this->referenceName($reference);
            if (in_array($name, $seen, true)) {
                throw new \LogicException(sprintf('Recursive object composition at %s.', $name));
            }
            $this->mergeShape(
                $shape,
                $this->objectShape($this->definitions[$name], array_merge($seen, array($name)))
            );
        }
        /** @var array<int, array<string, mixed>> $allOf */
        $allOf = $schema['allOf'] ?? array();
        foreach ($allOf as $member) {
            $this->mergeShape($shape, $this->objectShape($member, $seen));
        }
        /** @var array<string, array<string, mixed>> $properties */
        $properties = $schema['properties'] ?? array();
        foreach ($properties as $field => $propertySchema) {
            if (isset($shape['properties'][$field])) {
                $shape['properties'][$field] = array(
                    'allOf' => array($shape['properties'][$field], $propertySchema),
                );
            } else {
                $shape['properties'][$field] = $propertySchema;
            }
        }
        /** @var array<int, string> $required */
        $required = $schema['required'] ?? array();
        foreach ($required as $field) {
            $shape['required'][$field] = true;
        }
        if (array_key_exists('additionalProperties', $schema)) {
            $shape['additional']          = $schema['additionalProperties'];
            $shape['additionalSpecified'] = true;
        }

        return $shape;
    }

    /**
     * Whether a schema intersection constrains the value as an object.
     *
     * JSON Schema permits `allOf` for primitive refinements as well as object
     * composition. Only object intersections may be flattened into a property
     * shape; primitive intersections must evaluate each member independently.
     *
     * @param array<string, mixed> $schema
     * @param array<int, string>   $seen
     */
    private function constrainsObject(array $schema, array $seen): bool
    {
        if (($schema['type'] ?? null) === 'object' || isset($schema['properties'])) {
            return true;
        }
        if (isset($schema['$ref'])) {
            /** @var string $reference */
            $reference = $schema['$ref'];
            $name = $this->referenceName($reference);
            if (in_array($name, $seen, true)) {
                return false;
            }

            return $this->constrainsObject(
                $this->definitions[$name],
                array_merge($seen, array($name))
            );
        }
        /** @var array<int, array<string, mixed>> $allOf */
        $allOf = $schema['allOf'] ?? array();
        foreach ($allOf as $member) {
            if ($this->constrainsObject($member, $seen)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array{properties: array<string, array<string, mixed>>, required: array<string, true>, additional: mixed, additionalSpecified: bool} $target
     * @param array{properties: array<string, array<string, mixed>>, required: array<string, true>, additional: mixed, additionalSpecified: bool} $source
     */
    private function mergeShape(array &$target, array $source): void
    {
        foreach ($source['properties'] as $field => $schema) {
            if (isset($target['properties'][$field])) {
                $target['properties'][$field] = array(
                    'allOf' => array($target['properties'][$field], $schema),
                );
            } else {
                $target['properties'][$field] = $schema;
            }
        }
        $target['required'] += $source['required'];
        if ($source['additionalSpecified']) {
            $target['additional']          = $source['additional'];
            $target['additionalSpecified'] = true;
        }
    }

    /**
     * @param mixed $expected
     * @param mixed $value
     */
    private function matchesType($value, $expected, bool $programmaticArrays): bool
    {
        foreach ((array) $expected as $type) {
            if ($type === 'null' && $value === null) return true;
            if ($type === 'boolean' && is_bool($value)) return true;
            if ($type === 'string' && is_string($value)) return true;
            if ($type === 'number' && (is_int($value) || is_float($value))) return true;
            if (
                $type === 'integer' &&
                (is_int($value) || is_float($value) && is_finite($value) && floor($value) === $value)
            ) {
                return true;
            }
            if ($type === 'array' && is_array($value) && InputNormalizer::isList($value)) return true;
            if (
                $type === 'object' &&
                (
                    $value instanceof \stdClass ||
                    $programmaticArrays &&
                    is_array($value) &&
                    ($value === array() || ! InputNormalizer::isList($value))
                )
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function materializeAny($value)
    {
        if ($value instanceof \stdClass) {
            $output = new \stdClass();
            foreach (get_object_vars($value) as $key => $item) {
                $output->{$key} = $this->materializeAny($item);
            }

            return $output;
        }
        if (! is_array($value)) {
            return $value;
        }
        if (InputNormalizer::isList($value)) {
            return array_map(array($this, 'materializeAny'), $value);
        }

        $output = new \stdClass();
        foreach ($value as $key => $item) {
            $output->{(string) $key} = $this->materializeAny($item);
        }

        return $output;
    }

    private function recordClassForDefinition(string $definition): ?string
    {
        foreach ($this->records as $class => $metadata) {
            if ($metadata['definition'] === $definition && in_array($this->revision, $metadata['versions'], true)) {
                /** @var class-string<Record> $class */
                return $class;
            }
        }

        return null;
    }

    private function referenceName(string $reference): string
    {
        if (! preg_match('/^#\/\$defs\/([A-Za-z][A-Za-z0-9]*)$/', $reference, $matches)) {
            throw new \LogicException(sprintf('Unsupported catalog reference %s.', $reference));
        }

        return $matches[1];
    }

    /**
     * @param mixed $left
     * @param mixed $right
     */
    private static function jsonEquals($left, $right): bool
    {
        if ((is_int($left) || is_float($left)) && (is_int($right) || is_float($right))) {
            return $left == $right;
        }

        return $left === $right;
    }

    /**
     * @param mixed $value
     */
    private static function valueType($value): string
    {
        if ($value instanceof \stdClass) return 'object';
        if (is_array($value)) return InputNormalizer::isList($value) ? 'array' : 'object-like PHP array';
        if (is_int($value)) return 'integer';
        if (is_float($value)) return 'number';
        if (is_bool($value)) return 'boolean';
        if ($value === null) return 'null';

        return gettype($value);
    }
}

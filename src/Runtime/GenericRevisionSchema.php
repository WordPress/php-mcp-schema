<?php

declare(strict_types=1);

namespace WP\McpSchema\Runtime;

use LogicException;
use stdClass;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Contract\RevisionSchema;
use WP\McpSchema\Contract\Type;

/**
 * The single recursive validator and hydrator used by every revision.
 *
 * @phpstan-type Descriptor array<string, mixed>
 * @phpstan-type Field array{required: bool, type: Descriptor}
 * @phpstan-type Shape array{fields: array<string, Field>, additional: Descriptor|false}
 */
class GenericRevisionSchema implements RevisionSchema
{
    private string $revision;

    /** @var array<string, array<string, mixed>> */
    private array $descriptors;

    /** @var array<string, string> */
    private array $typeHashes;

    /** @var array<string, true> */
    private array $rootTypes;

    private string $fingerprint;

    /** @var array<string, Type<array<string, mixed>, array<string, mixed>>> */
    private array $types = [];

    /**
     * @param array<string, array<string, mixed>> $descriptors
     * @param array<string, string> $typeHashes
     * @param array<int, string> $rootTypes
     */
    protected function __construct(
        string $revision,
        array $descriptors,
        array $typeHashes,
        array $rootTypes,
        string $fingerprint
    ) {
        $this->revision = $revision;
        $this->descriptors = $descriptors;
        $this->typeHashes = $typeHashes;
        $this->rootTypes = array_fill_keys($rootTypes, true);
        $this->fingerprint = $fingerprint;
    }

    /** @return Type<array<string, mixed>, array<string, mixed>> */
    public function type(string $name): Type
    {
        if (!isset($this->rootTypes[$name])) {
            throw new LogicException(sprintf(
                "Unknown record-compatible MCP type '%s' in revision %s",
                $name,
                $this->revision
            ));
        }

        if (!isset($this->types[$name])) {
            $this->types[$name] = new TypeDefinition($this, $name, $this->typeHashes[$name]);
        }
        return $this->types[$name];
    }

    public function hasType(string $name): bool
    {
        return isset($this->rootTypes[$name]);
    }

    /** @return array<int, string> */
    public function types(): array
    {
        return array_keys($this->rootTypes);
    }

    public function revision(): string
    {
        return $this->revision;
    }

    public function fingerprint(): string
    {
        return $this->fingerprint;
    }

    /**
     * @param mixed $value
     * @return GenericRecord
     */
    public function hydrate(string $typeName, $value): Record
    {
        $decoded = $this->decodeNamed($typeName, $value, '$');
        if (!$decoded instanceof GenericRecord) {
            throw $this->error(
                $typeName,
                '$',
                'expected a record-compatible type, descriptor decoded to ' . self::describe($decoded)
            );
        }
        return $decoded;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function decodeNamed(string $name, $value, string $path)
    {
        $descriptor = $this->descriptor($name);
        if (self::kind($descriptor) === 'ref' && $this->category($descriptor) === 'record') {
            $seen = [];
            while (self::kind($descriptor) === 'ref') {
                $target = self::stringEntry($descriptor, 'name');
                if (isset($seen[$target])) {
                    throw new LogicException('Cyclic type alias involving ' . $target);
                }
                $seen[$target] = true;
                $descriptor = $this->descriptor($target);
            }
        }
        return $this->decode($descriptor, $value, $path, $name);
    }

    /**
     * @param array<string, mixed> $descriptor
     * @param mixed $value
     * @return mixed
     */
    private function decode(array $descriptor, $value, string $path, string $contextName)
    {
        $kind = self::kind($descriptor);
        switch ($kind) {
            case 'any':
                return $this->decodeAny($value, $path, $contextName);
            case 'string':
                if (!is_string($value)) {
                    throw $this->expected($contextName, $path, 'string', $value);
                }
                return $value;
            case 'number':
                if ((!is_int($value) && !is_float($value)) || (is_float($value) && !is_finite($value))) {
                    throw $this->expected($contextName, $path, 'number', $value);
                }
                return $value;
            case 'boolean':
                if (!is_bool($value)) {
                    throw $this->expected($contextName, $path, 'boolean', $value);
                }
                return $value;
            case 'null':
                if ($value !== null) {
                    throw $this->expected($contextName, $path, 'null', $value);
                }
                return null;
            case 'literal':
                $literal = $descriptor['value'] ?? null;
                if ($value !== $literal) {
                    throw $this->error(
                        $contextName,
                        $path,
                        'expected literal ' . var_export($literal, true) . ', got ' . self::describe($value)
                    );
                }
                return $value;
            case 'ref':
                return $this->decodeNamed(self::stringEntry($descriptor, 'name'), $value, $path);
            case 'list':
                return $this->decodeList(self::descriptorEntry($descriptor, 'items'), $value, $path, $contextName);
            case 'tuple':
                return $this->decodeTuple($descriptor, $value, $path, $contextName);
            case 'record':
            case 'map':
            case 'intersection':
            case 'omit':
                return $this->decodeRecord($descriptor, $value, $path, $contextName);
            case 'union':
                return $this->decodeUnion($descriptor, $value, $path, $contextName);
            default:
                throw new LogicException('Unsupported descriptor kind: ' . $kind);
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function decodeAny($value, string $path, string $contextName)
    {
        if ($value === null || is_string($value) || is_bool($value) || is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            if (!is_finite($value)) {
                throw $this->expected($contextName, $path, 'finite JSON number', $value);
            }
            return $value;
        }
        if ($value instanceof stdClass) {
            $result = new stdClass();
            foreach (get_object_vars($value) as $key => $item) {
                $result->{$key} = $this->decodeAny($item, $path . '.' . $key, $contextName);
            }
            return $result;
        }
        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $item) {
                $result[$key] = $this->decodeAny($item, $path . '.' . (string) $key, $contextName);
            }
            return $result;
        }
        throw $this->expected($contextName, $path, 'JSON-compatible value', $value);
    }

    /**
     * @param array<string, mixed> $itemDescriptor
     * @param mixed $value
     * @return array<int, mixed>
     */
    private function decodeList(array $itemDescriptor, $value, string $path, string $contextName): array
    {
        if (!is_array($value) || !self::isList($value)) {
            throw $this->expected($contextName, $path, 'list', $value);
        }

        $result = [];
        foreach ($value as $index => $item) {
            $result[] = $this->decode(
                $itemDescriptor,
                $item,
                sprintf('%s[%d]', $path, $index),
                $contextName . '[]'
            );
        }
        return $result;
    }

    /**
     * @param array<string, mixed> $descriptor
     * @param mixed $value
     * @return array<int, mixed>
     */
    private function decodeTuple(array $descriptor, $value, string $path, string $contextName): array
    {
        if (!is_array($value) || !self::isList($value)) {
            throw $this->expected($contextName, $path, 'tuple', $value);
        }
        $items = self::descriptorListEntry($descriptor, 'items');
        if (count($value) !== count($items)) {
            throw $this->error(
                $contextName,
                $path,
                sprintf('expected tuple of %d items, got %d', count($items), count($value))
            );
        }

        $result = [];
        foreach ($items as $index => $itemDescriptor) {
            $result[] = $this->decode(
                $itemDescriptor,
                $value[$index],
                sprintf('%s[%d]', $path, $index),
                $contextName . '[]'
            );
        }
        return $result;
    }

    /**
     * @param array<string, mixed> $descriptor
     * @param mixed $value
     * @return mixed
     */
    private function decodeUnion(array $descriptor, $value, string $path, string $contextName)
    {
        $candidates = self::descriptorListEntry($descriptor, 'anyOf');
        $candidates = $this->orderUnionCandidates($candidates, $value);
        $problems = [];

        foreach ($candidates as $candidate) {
            try {
                return $this->decode($candidate, $value, $path, $contextName);
            } catch (ValidationException $exception) {
                $problems[] = $exception->getMessage();
            }
        }

        throw $this->error(
            $contextName,
            $path,
            sprintf(
                'did not match any of %d union members (%s)',
                count($candidates),
                implode('; ', array_slice($problems, 0, 3))
            )
        );
    }

    /**
     * @param array<int, array<string, mixed>> $candidates
     * @param mixed $value
     * @return array<int, array<string, mixed>>
     */
    private function orderUnionCandidates(array $candidates, $value): array
    {
        $preferred = null;
        if ($value instanceof stdClass) {
            $preferred = 'record';
        } elseif (is_array($value)) {
            $preferred = self::isList($value) ? 'list' : 'record';
        }
        if ($preferred === null) {
            return $candidates;
        }

        usort($candidates, function (array $left, array $right) use ($preferred): int {
            $leftPreferred = $this->category($left) === $preferred;
            $rightPreferred = $this->category($right) === $preferred;
            return ($leftPreferred === $rightPreferred) ? 0 : ($leftPreferred ? -1 : 1);
        });
        return $candidates;
    }

    /** @param array<string, mixed> $descriptor */
    private function category(array $descriptor): string
    {
        $kind = self::kind($descriptor);
        if ($kind === 'ref') {
            return $this->category($this->descriptor(self::stringEntry($descriptor, 'name')));
        }
        if ($kind === 'list' || $kind === 'tuple') {
            return 'list';
        }
        if (in_array($kind, ['record', 'map', 'intersection', 'omit'], true)) {
            return 'record';
        }
        return 'scalar';
    }

    /**
     * @param array<string, mixed> $descriptor
     * @param mixed $value
     */
    private function decodeRecord(
        array $descriptor,
        $value,
        string $path,
        string $contextName
    ): GenericRecord {
        $shape = $this->shape($descriptor, []);
        if ($value instanceof stdClass) {
            /** @var array<string, mixed> $data */
            $data = get_object_vars($value);
        } elseif (is_array($value) && ($value === [] || !self::isList($value))) {
            /** @var array<string, mixed> $data */
            $data = $value;
        } else {
            throw $this->expected($contextName, $path, 'object record', $value);
        }

        foreach ($shape['fields'] as $name => $field) {
            if ($field['required'] && !array_key_exists($name, $data)) {
                throw $this->error($contextName, $path . '.' . $name, 'required field is missing');
            }
        }

        $decoded = [];
        foreach ($data as $key => $item) {
            $name = (string) $key;
            if (isset($shape['fields'][$name])) {
                $field = $shape['fields'][$name];
                $decoded[$key] = $this->decode(
                    $field['type'],
                    $item,
                    $path . '.' . $name,
                    $contextName . '.' . $name
                );
                continue;
            }
            if ($shape['additional'] === false) {
                throw $this->error($contextName, $path . '.' . $name, 'unexpected field');
            }
            $decoded[$key] = $this->decode(
                $shape['additional'],
                $item,
                $path . '.' . $name,
                $contextName . '{value}'
            );
        }

        return new GenericRecord($this->revision, $contextName, $decoded);
    }

    /**
     * @param array<string, mixed> $descriptor
     * @param array<int, string> $visiting
     * @return array{fields: array<string, array{required: bool, type: array<string, mixed>}>, additional: array<string, mixed>|false}
     */
    private function shape(array $descriptor, array $visiting): array
    {
        $kind = self::kind($descriptor);
        if ($kind === 'ref') {
            $name = self::stringEntry($descriptor, 'name');
            if (in_array($name, $visiting, true)) {
                throw new LogicException('Cyclic record inheritance involving ' . $name);
            }
            $visiting[] = $name;
            return $this->shape($this->descriptor($name), $visiting);
        }
        if ($kind === 'map') {
            return [
                'fields' => [],
                'additional' => self::descriptorEntry($descriptor, 'values'),
            ];
        }
        if ($kind === 'omit') {
            $shape = $this->shape(self::descriptorEntry($descriptor, 'from'), $visiting);
            foreach (self::stringListEntry($descriptor, 'keys') as $key) {
                unset($shape['fields'][$key]);
            }
            return $shape;
        }
        if ($kind === 'intersection') {
            $shape = ['fields' => [], 'additional' => false];
            foreach (self::descriptorListEntry($descriptor, 'allOf') as $part) {
                $shape = self::mergeShapes($shape, $this->shape($part, $visiting));
            }
            return $shape;
        }
        if ($kind !== 'record') {
            throw new LogicException('Descriptor is not record-compatible: ' . $kind);
        }

        $shape = ['fields' => [], 'additional' => false];
        foreach (self::descriptorListEntry($descriptor, 'parents') as $parent) {
            $shape = self::mergeShapes($shape, $this->shape($parent, $visiting));
        }
        $shape['fields'] = array_merge($shape['fields'], self::fieldMapEntry($descriptor, 'fields'));
        $additional = $descriptor['additional'] ?? false;
        if ($additional !== false) {
            if (!is_array($additional)) {
                throw new LogicException('Descriptor additional entry must be a descriptor or false');
            }
            /** @var array<string, mixed> $additional */
            $shape['additional'] = $additional;
        }
        return $shape;
    }

    /**
     * @param array{fields: array<string, array{required: bool, type: array<string, mixed>}>, additional: array<string, mixed>|false} $left
     * @param array{fields: array<string, array{required: bool, type: array<string, mixed>}>, additional: array<string, mixed>|false} $right
     * @return array{fields: array<string, array{required: bool, type: array<string, mixed>}>, additional: array<string, mixed>|false}
     */
    private static function mergeShapes(array $left, array $right): array
    {
        return [
            'fields' => array_merge($left['fields'], $right['fields']),
            'additional' => $right['additional'] !== false ? $right['additional'] : $left['additional'],
        ];
    }

    /** @return array<string, mixed> */
    private function descriptor(string $name): array
    {
        $hash = $this->typeHashes[$name] ?? null;
        if ($hash === null || !isset($this->descriptors[$hash])) {
            throw new LogicException(sprintf(
                "Descriptor for MCP type '%s' is unavailable in revision %s",
                $name,
                $this->revision
            ));
        }
        return $this->descriptors[$hash];
    }

    /** @param array<string, mixed> $descriptor */
    private static function kind(array $descriptor): string
    {
        return self::stringEntry($descriptor, 'kind');
    }

    /** @param array<string, mixed> $descriptor */
    private static function stringEntry(array $descriptor, string $key): string
    {
        $value = $descriptor[$key] ?? null;
        if (!is_string($value)) {
            throw new LogicException(sprintf("Descriptor entry '%s' must be a string", $key));
        }
        return $value;
    }

    /**
     * @param array<string, mixed> $descriptor
     * @return array<string, mixed>
     */
    private static function descriptorEntry(array $descriptor, string $key): array
    {
        $value = $descriptor[$key] ?? null;
        if (!is_array($value)) {
            throw new LogicException(sprintf("Descriptor entry '%s' must be a descriptor", $key));
        }
        /** @var array<string, mixed> $value */
        return $value;
    }

    /**
     * @param array<string, mixed> $descriptor
     * @return array<int, array<string, mixed>>
     */
    private static function descriptorListEntry(array $descriptor, string $key): array
    {
        $value = $descriptor[$key] ?? null;
        if (!is_array($value)) {
            throw new LogicException(sprintf("Descriptor entry '%s' must be a descriptor list", $key));
        }
        $result = [];
        foreach ($value as $item) {
            if (!is_array($item)) {
                throw new LogicException(sprintf("Descriptor entry '%s' contains a non-descriptor", $key));
            }
            /** @var array<string, mixed> $item */
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param array<string, mixed> $descriptor
     * @return array<int, string>
     */
    private static function stringListEntry(array $descriptor, string $key): array
    {
        $value = $descriptor[$key] ?? null;
        if (!is_array($value)) {
            throw new LogicException(sprintf("Descriptor entry '%s' must be a string list", $key));
        }
        $result = [];
        foreach ($value as $item) {
            if (!is_string($item)) {
                throw new LogicException(sprintf("Descriptor entry '%s' contains a non-string", $key));
            }
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param array<string, mixed> $descriptor
     * @return array<string, array{required: bool, type: array<string, mixed>}>
     */
    private static function fieldMapEntry(array $descriptor, string $key): array
    {
        $value = $descriptor[$key] ?? null;
        if (!is_array($value)) {
            throw new LogicException(sprintf("Descriptor entry '%s' must be a field map", $key));
        }
        $result = [];
        foreach ($value as $name => $field) {
            if (!is_string($name) || !is_array($field)) {
                throw new LogicException(sprintf("Descriptor entry '%s' contains an invalid field", $key));
            }
            $required = $field['required'] ?? null;
            $type = $field['type'] ?? null;
            if (!is_bool($required) || !is_array($type)) {
                throw new LogicException(sprintf("Descriptor field '%s' is malformed", $name));
            }
            /** @var array<string, mixed> $type */
            $result[$name] = ['required' => $required, 'type' => $type];
        }
        return $result;
    }

    /** @param array<int|string, mixed> $value */
    private static function isList(array $value): bool
    {
        if ($value === []) {
            return true;
        }
        return array_keys($value) === range(0, count($value) - 1);
    }

    /** @param mixed $actual */
    private function expected(
        string $typeName,
        string $path,
        string $expected,
        $actual
    ): ValidationException {
        return $this->error(
            $typeName,
            $path,
            sprintf('expected %s, got %s', $expected, self::describe($actual))
        );
    }

    private function error(string $typeName, string $path, string $problem): ValidationException
    {
        return new ValidationException($this->revision, $typeName, $path, $problem);
    }

    /** @param mixed $value */
    private static function describe($value): string
    {
        if ($value instanceof stdClass) {
            return 'object';
        }
        if (is_object($value)) {
            return get_class($value);
        }
        if (is_array($value)) {
            return self::isList($value) ? 'list' : 'object-like array';
        }
        return gettype($value);
    }
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\Compatibility;

use stdClass;
use UnexpectedValueException;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Revision;
use WP\McpSchema\Schemas;

/**
 * Minimal compatibility facade over one descriptor-backed legacy record.
 *
 * @internal
 */
abstract class DescriptorBackedDto
{
    /** @var Record<array<string, mixed>, array<string, mixed>> */
    private Record $record;

    /** @param Record<array<string, mixed>, array<string, mixed>> $record */
    final protected function __construct(Record $record, string $typeName)
    {
        if ($record->revision() !== Revision::V20251125 || $record->typeName() !== $typeName) {
            throw new UnexpectedValueException(sprintf(
                'Expected %s %s, received %s %s.',
                Revision::V20251125,
                $typeName,
                $record->revision(),
                $record->typeName()
            ));
        }

        $this->record = $record;
    }

    /**
     * @param array<string, mixed> $data
     * @param list<list<string>> $objectPaths
     * @return Record<array<string, mixed>, array<string, mixed>>
     */
    final protected static function hydrate(string $typeName, array $data, array $objectPaths = []): Record
    {
        foreach ($objectPaths as $path) {
            $data = self::normalizeEmptyObjectAtPath($data, $path);
        }

        /** @var Record<array<string, mixed>, array<string, mixed>> $record */
        $record = Schemas::v20251125()->type($typeName)->fromArray($data);
        return $record;
    }

    /** @return array<string, mixed> */
    final public function toArray(): array
    {
        return $this->record->toArray();
    }

    /** @return mixed */
    final protected function value(string $key)
    {
        return $this->record->get($key);
    }

    /** @return mixed */
    final protected function optionalValue(string $key)
    {
        return $this->record->has($key) ? $this->record->get($key) : null;
    }

    final protected function stringValue(string $key): string
    {
        $value = $this->value($key);
        if (!is_string($value)) {
            throw new UnexpectedValueException(sprintf("Expected '%s' to be a string.", $key));
        }
        return $value;
    }

    final protected function nullableStringValue(string $key): ?string
    {
        $value = $this->optionalValue($key);
        if ($value !== null && !is_string($value)) {
            throw new UnexpectedValueException(sprintf("Expected '%s' to be a string or null.", $key));
        }
        return $value;
    }

    final protected function nullableBoolValue(string $key): ?bool
    {
        $value = $this->optionalValue($key);
        if ($value !== null && !is_bool($value)) {
            throw new UnexpectedValueException(sprintf("Expected '%s' to be a boolean or null.", $key));
        }
        return $value;
    }

    final protected function nullableIntValue(string $key): ?int
    {
        $value = $this->optionalValue($key);
        if ($value !== null && !is_int($value)) {
            throw new UnexpectedValueException(sprintf("Expected '%s' to be an integer or null.", $key));
        }
        return $value;
    }

    /** @return array<mixed>|null */
    final protected function nullableArrayValue(string $key): ?array
    {
        $value = $this->optionalValue($key);
        if ($value === null) {
            return null;
        }

        $value = self::plainValue($value);
        if (!is_array($value)) {
            throw new UnexpectedValueException(sprintf("Expected '%s' to be an array-compatible value.", $key));
        }
        return $value;
    }

    /** @return list<Record<array<string, mixed>, array<string, mixed>>>|null */
    final protected function nullableRecordListValue(string $key): ?array
    {
        $value = $this->optionalValue($key);
        if ($value === null) {
            return null;
        }
        if (!is_array($value)) {
            throw new UnexpectedValueException(sprintf("Expected '%s' to be a record list.", $key));
        }

        $records = [];
        foreach ($value as $item) {
            if (!$item instanceof Record) {
                throw new UnexpectedValueException(sprintf("Expected '%s' to contain records.", $key));
            }
            /** @var Record<array<string, mixed>, array<string, mixed>> $item */
            $records[] = $item;
        }
        return $records;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function plainValue($value)
    {
        if ($value instanceof Record) {
            return $value->toArray();
        }
        if ($value instanceof stdClass) {
            $value = get_object_vars($value);
        }
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = self::plainValue($item);
        }
        return $result;
    }

    /**
     * @param array<string, mixed> $data
     * @param list<string> $path
     * @return array<string, mixed>
     */
    private static function normalizeEmptyObjectAtPath(array $data, array $path): array
    {
        $key = array_shift($path);
        if ($key === null || !array_key_exists($key, $data)) {
            return $data;
        }
        if ($path === []) {
            if ($data[$key] === []) {
                $data[$key] = new stdClass();
            }
            return $data;
        }
        if (is_array($data[$key])) {
            $data[$key] = self::normalizeEmptyObjectAtPath($data[$key], $path);
        }
        return $data;
    }
}

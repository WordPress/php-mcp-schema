<?php

declare(strict_types=1);

namespace WP\McpSchema\Runtime;

use OutOfBoundsException;
use stdClass;
use WP\McpSchema\Contract\Record;

/** @implements Record<array<string, mixed>, array<string, mixed>> */
final class GenericRecord implements Record
{
    private string $revision;

    private string $typeName;

    /** @var array<string, mixed> */
    private array $data;

    /** @param array<string, mixed> $data */
    public function __construct(string $revision, string $typeName, array $data)
    {
        $this->revision = $revision;
        $this->typeName = $typeName;
        $this->data = $data;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        /** @var array<string, mixed> $result */
        $result = self::toWireArray($this->data);
        return $result;
    }

    /** @return mixed */
    public function get(string $key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new OutOfBoundsException(sprintf(
                "%s %s has no present field '%s'",
                $this->revision,
                $this->typeName,
                $key
            ));
        }

        return self::copyForRead($this->data[$key]);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function revision(): string
    {
        return $this->revision;
    }

    public function typeName(): string
    {
        return $this->typeName;
    }

    public function jsonSerialize(): object
    {
        $result = new stdClass();
        foreach ($this->data as $key => $value) {
            $result->{(string) $key} = self::toJsonValue($value);
        }
        return $result;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function copyForRead($value)
    {
        if ($value instanceof self) {
            return $value;
        }
        if ($value instanceof stdClass) {
            $result = new stdClass();
            foreach (get_object_vars($value) as $key => $item) {
                $result->{$key} = self::copyForRead($item);
            }
            return $result;
        }
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = self::copyForRead($item);
        }
        return $result;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function toWireArray($value)
    {
        if ($value instanceof self) {
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
            $result[$key] = self::toWireArray($item);
        }
        return $result;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function toJsonValue($value)
    {
        if ($value instanceof self) {
            return $value->jsonSerialize();
        }
        if ($value instanceof stdClass) {
            $result = new stdClass();
            foreach (get_object_vars($value) as $key => $item) {
                $result->{$key} = self::toJsonValue($item);
            }
            return $result;
        }
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = self::toJsonValue($item);
        }
        return $result;
    }
}

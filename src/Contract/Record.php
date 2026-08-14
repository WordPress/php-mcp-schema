<?php

declare(strict_types=1);

namespace WP\McpSchema\Contract;

use JsonSerializable;

/**
 * An immutable, revision-bound MCP wire record.
 *
 * @template TWire of array<string, mixed>
 * @template TFields of array<string, mixed>
 */
interface Record extends JsonSerializable
{
    /** @return TWire */
    public function toArray(): array;

    /**
     * Returns a top-level PHP array while preserving nested JSON objects as objects.
     *
     * @return array<string, mixed>
     */
    public function toWireArray(): array;

    /**
     * Returns nested PHP arrays that still encode to the exact protocol bytes.
     *
     * Unlike toArray(), a value the revision declares as an object stays a
     * stdClass whenever a plain array would not encode as a JSON object, that
     * is when the array is empty or its keys form a list. Consumers that read
     * or rewrite a payload before serialising it want this shape: array access
     * works everywhere it did before, and json_encode still emits {} where the
     * protocol requires an object.
     *
     * @return array<string, mixed>
     */
    public function toJsonArray(): array;

    /**
     * @template K of key-of<TFields>
     * @param K $key
     * @return TFields[K]
     */
    public function get(string $key);

    public function has(string $key): bool;

    public function revision(): string;

    public function typeName(): string;
}

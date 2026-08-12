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
     * @template K of key-of<TFields>
     * @param K $key
     * @return TFields[K]
     */
    public function get(string $key);

    public function has(string $key): bool;

    public function revision(): string;

    public function typeName(): string;
}

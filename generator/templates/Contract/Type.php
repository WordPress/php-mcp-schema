<?php

declare(strict_types=1);

namespace WP\McpSchema\Contract;

/**
 * A named wire type in one explicit MCP revision.
 *
 * @template TWire of array<string, mixed>
 * @template TFields of array<string, mixed>
 */
interface Type
{
    /**
     * @param TWire $data
     * @return Record<TWire, TFields>
     */
    public function fromArray(array $data): Record;

    /** @return Record<TWire, TFields> */
    public function fromJson(string $json): Record;

    /**
     * Hydrates an already-decoded JSON value, preserving object/list
     * identity: `stdClass` means a JSON object and a PHP list means a
     * JSON list, exactly as produced by `json_decode()` without the
     * assoc flag.
     *
     * @param mixed $value
     * @return Record<TWire, TFields>
     */
    public function fromValue($value): Record;

    /** @param TWire $data */
    public function validate(array $data): void;

    public function name(): string;

    public function revision(): string;

    public function fingerprint(): string;
}

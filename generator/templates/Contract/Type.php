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
     * Hydrates an already-decoded JSON value, as produced by
     * `json_decode()` without the assoc flag: `stdClass` means a JSON
     * object and a PHP list means a JSON list, including objects whose
     * keys are all numeric strings. At positions the schema types as
     * object/map the usual empty tolerance still applies — an empty
     * list hydrates as an empty object; non-empty lists are rejected.
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

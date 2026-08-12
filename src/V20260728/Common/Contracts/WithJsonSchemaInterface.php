<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Contracts;

/**
 * Interface for objects that provide their JSON Schema definition.
 *
 * @mcp-version 2026-07-28
 */
interface WithJsonSchemaInterface
{
    /**
     * Returns the JSON Schema definition for this type.
     *
     * @return array<string, mixed> The JSON Schema definition.
     */
    public static function getJsonSchema(): array;
}

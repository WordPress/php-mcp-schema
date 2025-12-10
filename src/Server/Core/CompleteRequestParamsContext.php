<?php

declare(strict_types=1);

namespace WP\McpSchema\Server\Core;

use WP\McpSchema\Common\AbstractDataTransferObject;

/**
 * Additional, optional context for completions
 *
 * @mcp-domain Server
 * @mcp-subdomain Core
 * @mcp-version 2025-11-25
 */
class CompleteRequestParamsContext extends AbstractDataTransferObject
{
    /**
     */
    public function __construct()
    {
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self();
    }

    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [];

        return $result;
    }
}

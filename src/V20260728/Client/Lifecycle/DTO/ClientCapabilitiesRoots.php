<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Lifecycle\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;

/**
 * Present if the client supports listing roots.
 *
 * @mcp-domain Client
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
class ClientCapabilitiesRoots extends AbstractDataTransferObject
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

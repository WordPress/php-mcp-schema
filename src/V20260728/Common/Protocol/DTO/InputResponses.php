<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;

/**
 * A map of client responses to server-initiated requests.
 * Keys correspond to the keys in the {@link InputRequests} map;
 * values are the client's result for each request.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class InputResponses extends AbstractDataTransferObject
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

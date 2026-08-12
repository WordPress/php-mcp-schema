<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\Union;

/**
 * Union type members:
 * - CreateMessageResult
 * - ListRootsResult
 * - ElicitResult
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
interface InputResponseInterface
{
    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

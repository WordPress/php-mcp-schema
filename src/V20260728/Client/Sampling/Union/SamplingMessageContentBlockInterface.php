<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Sampling\Union;

/**
 * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
 *
 * Union type members:
 * - TextContent
 * - ImageContent
 * - AudioContent
 * - ToolUseContent
 * - ToolResultContent
 *
 * @mcp-domain Client
 * @mcp-subdomain Sampling
 * @mcp-version 2026-07-28
 */
interface SamplingMessageContentBlockInterface
{
    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

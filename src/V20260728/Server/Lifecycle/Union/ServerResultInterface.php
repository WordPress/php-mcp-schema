<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Lifecycle\Union;

/**
 * Union type members:
 * - EmptyResult
 * - DiscoverResult
 * - CompleteResult
 * - GetPromptResult
 * - ListPromptsResult
 * - ListResourceTemplatesResult
 * - ListResourcesResult
 * - ReadResourceResult
 * - SubscriptionsListenResult
 * - CallToolResult
 * - ListToolsResult
 * - InputRequiredResult
 *
 * @mcp-domain Server
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
interface ServerResultInterface
{
    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

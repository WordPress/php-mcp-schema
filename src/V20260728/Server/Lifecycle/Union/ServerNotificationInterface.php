<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Lifecycle\Union;

/**
 * Union type members:
 * - CancelledNotification
 * - ProgressNotification
 * - LoggingMessageNotification
 * - ResourceUpdatedNotification
 * - ResourceListChangedNotification
 * - ToolListChangedNotification
 * - PromptListChangedNotification
 * - SubscriptionsAcknowledgedNotification
 *
 * @mcp-domain Server
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
interface ServerNotificationInterface
{
    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a {@link SubscriptionsAcknowledgedNotification | notifications/subscriptions/acknowledged} notification.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionsAcknowledgedNotificationParams extends NotificationParams
{
    use ValidatesRequiredFields;

    /**
     * The subset of requested notification types the server agreed to honor.
     * Only includes notification types the server actually supports; if the
     * client requested an unsupported type (e.g., `promptsListChanged` when
     * the server has no prompts), it is omitted from this set.
     *
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter
     */
    protected SubscriptionFilter $notifications;

    /**
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter $notifications @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta @since 2026-07-28
     */
    public function __construct(
        SubscriptionFilter $notifications,
        ?NotificationMetaObject $_meta = null
    ) {
        parent::__construct($_meta);
        $this->notifications = $notifications;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null,
     *     notifications: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['notifications']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter $notifications */
        $notifications = is_array($data['notifications'])
            ? SubscriptionFilter::fromArray(self::asArray($data['notifications']))
            : $data['notifications'];

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? NotificationMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $notifications,
            $_meta
        );
    }

    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['notifications'] = $this->notifications->toArray();

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter
     */
    public function getNotifications(): SubscriptionFilter
    {
        return $this->notifications;
    }
}

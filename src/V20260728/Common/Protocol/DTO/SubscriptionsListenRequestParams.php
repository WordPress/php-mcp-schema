<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a {@link SubscriptionsListenRequest | subscriptions/listen} request.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionsListenRequestParams extends RequestParams
{
    use ValidatesRequiredFields;

    /**
     * The notifications the client opts in to on this stream. The server
     * **MUST NOT** send notification types the client has not explicitly
     * requested.
     *
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter
     */
    protected SubscriptionFilter $notifications;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter $notifications @since 2026-07-28
     */
    public function __construct(
        RequestMetaObject $_meta,
        SubscriptionFilter $notifications
    ) {
        parent::__construct($_meta);
        $this->notifications = $notifications;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject,
     *     notifications: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['_meta', 'notifications']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta */
        $_meta = is_array($data['_meta'])
            ? RequestMetaObject::fromArray(self::asArray($data['_meta']))
            : $data['_meta'];

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionFilter $notifications */
        $notifications = is_array($data['notifications'])
            ? SubscriptionFilter::fromArray(self::asArray($data['notifications']))
            : $data['notifications'];

        return new self(
            $_meta,
            $notifications
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

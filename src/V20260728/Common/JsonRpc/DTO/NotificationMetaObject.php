<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\JsonRpc\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Extends {@link MetaObject} with additional notification-specific fields. All key naming rules from `MetaObject` apply.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain JsonRpc
 * @mcp-version 2026-07-28
 */
class NotificationMetaObject extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Identifies the subscription stream a notification was delivered on. The
     * server MUST include this key on every notification delivered via a
     * {@link SubscriptionsListenRequest | subscriptions/listen} stream, so the
     * client can correlate the notification with the originating subscription.
     * The key is absent on notifications not delivered via a subscription
     * stream (e.g. progress notifications for an in-flight request), which is
     * why it is optional here.
     *
     * The value is the JSON-RPC ID of the `subscriptions/listen` request that
     * opened the stream.
     *
     * @since 2026-07-28
     *
     * @var string|number|null
     */
    protected $subscriptionId;

    /**
     * @param string|number|null $subscriptionId @since 2026-07-28
     */
    public function __construct(
        $subscriptionId = null
    ) {
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     'io.modelcontextprotocol/subscriptionId'?: string|number|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var string|number|null $subscriptionId */
        $subscriptionId = isset($data['io.modelcontextprotocol/subscriptionId'])
            ? self::asStringOrNumberOrNull($data['io.modelcontextprotocol/subscriptionId'])
            : null;

        return new self(
            $subscriptionId
        );
    }

    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->subscriptionId !== null) {
            $result['io.modelcontextprotocol/subscriptionId'] = $this->subscriptionId;
        }

        return $result;
    }

    /**
     * @return string|number|null
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }
}

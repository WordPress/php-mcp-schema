<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Extends {@link ResultMetaObject} with the subscription-stream identifier carried by a
 * {@link SubscriptionsListenResult}. All key naming rules from `MetaObject` apply.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionsListenResultMetaObject extends ResultMetaObject
{
    use ValidatesRequiredFields;

    /**
     * Identifies the subscription stream this response closes, so the client can
     * correlate it with the originating subscription — mirroring the same key on
     * the stream's notifications. The value is the JSON-RPC ID of the
     * `subscriptions/listen` request that opened the stream (and equals this
     * response's `id`).
     *
     * @since 2026-07-28
     *
     * @var string|number
     */
    protected $subscriptionId;

    /**
     * @param string|number $subscriptionId @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null $serverInfo @since 2026-07-28
     */
    public function __construct(
        $subscriptionId,
        ?Implementation $serverInfo = null
    ) {
        parent::__construct($serverInfo);
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     'io.modelcontextprotocol/serverInfo'?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null,
     *     'io.modelcontextprotocol/subscriptionId': string|number
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['io.modelcontextprotocol/subscriptionId']);

        /** @var string|number $subscriptionId */
        $subscriptionId = self::asStringOrNumber($data['io.modelcontextprotocol/subscriptionId']);

        /** @var \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null $serverInfo */
        $serverInfo = isset($data['io.modelcontextprotocol/serverInfo'])
            ? (is_array($data['io.modelcontextprotocol/serverInfo'])
                ? Implementation::fromArray(self::asArray($data['io.modelcontextprotocol/serverInfo']))
                : $data['io.modelcontextprotocol/serverInfo'])
            : null;

        return new self(
            $subscriptionId,
            $serverInfo
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

        $result['io.modelcontextprotocol/subscriptionId'] = $this->subscriptionId;

        return $result;
    }

    /**
     * @return string|number
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }
}

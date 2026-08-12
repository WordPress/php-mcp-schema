<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCNotification;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerNotificationInterface;

/**
 * Sent by the server to acknowledge that a
 * {@link SubscriptionsListenRequest | subscriptions/listen} subscription has been
 * established and to report which notification types it agreed to honor.
 *
 * This notification MUST be the first message the server sends carrying the
 * subscription's ID in `io.modelcontextprotocol/subscriptionId`. The server MUST
 * NOT send any notification on the subscription before acknowledging it. On
 * stdio, where every subscription shares one channel, this ordering is defined
 * per subscription ID and not per channel: messages belonging to other
 * subscriptions MAY be interleaved before it.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionsAcknowledgedNotification extends JSONRPCNotification implements ServerNotificationInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'notifications/subscriptions/acknowledged';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'notifications/subscriptions/acknowledged';

    /**
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsAcknowledgedNotificationParams
     */
    protected SubscriptionsAcknowledgedNotificationParams $typedParams;

    /**
     * @param '2.0' $jsonrpc @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsAcknowledgedNotificationParams $params @since 2026-07-28
     */
    public function __construct(
        string $jsonrpc,
        SubscriptionsAcknowledgedNotificationParams $params
    ) {
        parent::__construct(self::METHOD, $jsonrpc, null);
        $this->typedParams = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     jsonrpc: '2.0',
     *     method: 'notifications/subscriptions/acknowledged',
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsAcknowledgedNotificationParams
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['jsonrpc', 'params']);

        /** @var '2.0' $jsonrpc */
        $jsonrpc = self::asString($data['jsonrpc']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsAcknowledgedNotificationParams $params */
        $params = is_array($data['params'])
            ? SubscriptionsAcknowledgedNotificationParams::fromArray(self::asArray($data['params']))
            : $data['params'];

        return new self(
            $jsonrpc,
            $params
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

        $result['params'] = $this->typedParams->toArray();

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsAcknowledgedNotificationParams
     */
    public function getTypedParams(): SubscriptionsAcknowledgedNotificationParams
    {
        return $this->typedParams;
    }
}

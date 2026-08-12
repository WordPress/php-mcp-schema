<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCNotification;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerNotificationInterface;

/**
 * This notification is sent by the client to indicate that it is cancelling a request it previously issued.
 *
 * On stdio, the server also sends this notification, solely to terminate a {@link SubscriptionsListenRequest | subscriptions/listen} stream: it references the ID of the `subscriptions/listen` request that opened the stream. Servers MUST NOT use this notification to cancel any other request.
 *
 * The request SHOULD still be in-flight, but due to communication latency, it is always possible that this notification MAY arrive after the request has already finished.
 *
 * This notification indicates that the result will be unused, so any associated processing SHOULD cease.
 *
 * @since 2024-11-05
 * @last-updated 2025-11-25 (modified property: params)
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class CancelledNotification extends JSONRPCNotification implements ServerNotificationInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'notifications/cancelled';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'notifications/cancelled';

    /**
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\CancelledNotificationParams
     */
    protected CancelledNotificationParams $typedParams;

    /**
     * @param '2.0' $jsonrpc @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\CancelledNotificationParams $params @since 2024-11-05
     */
    public function __construct(
        string $jsonrpc,
        CancelledNotificationParams $params
    ) {
        parent::__construct(self::METHOD, $jsonrpc, null);
        $this->typedParams = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     jsonrpc: '2.0',
     *     method: 'notifications/cancelled',
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\CancelledNotificationParams
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['jsonrpc', 'params']);

        /** @var '2.0' $jsonrpc */
        $jsonrpc = self::asString($data['jsonrpc']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\CancelledNotificationParams $params */
        $params = is_array($data['params'])
            ? CancelledNotificationParams::fromArray(self::asArray($data['params']))
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
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\CancelledNotificationParams
     */
    public function getTypedParams(): CancelledNotificationParams
    {
        return $this->typedParams;
    }
}

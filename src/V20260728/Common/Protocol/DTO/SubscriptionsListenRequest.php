<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCRequest;
use WP\McpSchema\V20260728\Common\Protocol\Union\ClientRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Sent from the client to open a long-lived channel for receiving notifications
 * outside the context of a specific request. Replaces the previous HTTP GET
 * endpoint and ensures consistent behavior between HTTP and STDIO.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionsListenRequest extends JSONRPCRequest implements ClientRequestInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'subscriptions/listen';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'subscriptions/listen';

    /**
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenRequestParams
     */
    protected SubscriptionsListenRequestParams $typedParams;

    /**
     * @param '2.0' $jsonrpc @since 2026-07-28
     * @param string|number $id @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenRequestParams $params @since 2026-07-28
     */
    public function __construct(
        string $jsonrpc,
        $id,
        SubscriptionsListenRequestParams $params
    ) {
        parent::__construct(self::METHOD, $jsonrpc, $id, null);
        $this->typedParams = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     jsonrpc: '2.0',
     *     id: string|number,
     *     method: 'subscriptions/listen',
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenRequestParams
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['jsonrpc', 'id', 'params']);

        /** @var '2.0' $jsonrpc */
        $jsonrpc = self::asString($data['jsonrpc']);

        /** @var string|number $id */
        $id = self::asStringOrNumber($data['id']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenRequestParams $params */
        $params = is_array($data['params'])
            ? SubscriptionsListenRequestParams::fromArray(self::asArray($data['params']))
            : $data['params'];

        return new self(
            $jsonrpc,
            $id,
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
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenRequestParams
     */
    public function getTypedParams(): SubscriptionsListenRequestParams
    {
        return $this->typedParams;
    }
}

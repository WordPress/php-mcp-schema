<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCRequest;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams;
use WP\McpSchema\V20260728\Common\Protocol\Union\ClientRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A request from the client asking the server to advertise its supported
 * protocol versions, capabilities, and other metadata. Servers **MUST**
 * implement `server/discover`. Clients **MAY** call it but are not required
 * to — version negotiation can also happen inline via per-request `_meta`.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class DiscoverRequest extends JSONRPCRequest implements ClientRequestInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'server/discover';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'server/discover';

    /**
     * @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams
     */
    protected RequestParams $typedParams;

    /**
     * @param '2.0' $jsonrpc @since 2026-07-28
     * @param string|number $id @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams $params @since 2026-07-28
     */
    public function __construct(
        string $jsonrpc,
        $id,
        RequestParams $params
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
     *     method: 'server/discover',
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams
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

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams $params */
        $params = is_array($data['params'])
            ? RequestParams::fromArray(self::asArray($data['params']))
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
     * @return \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams
     */
    public function getTypedParams(): RequestParams
    {
        return $this->typedParams;
    }
}

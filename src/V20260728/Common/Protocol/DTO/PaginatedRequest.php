<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCRequest;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * @since 2024-11-05
 * @last-updated 2025-11-25 (modified property: params)
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class PaginatedRequest extends JSONRPCRequest
{
    use ValidatesRequiredFields;

    /**
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams
     */
    protected PaginatedRequestParams $typedParams;

    /**
     * @param '2.0' $jsonrpc @since 2025-11-25
     * @param string|number $id @since 2025-11-25
     * @param string $method @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams $params @since 2024-11-05
     */
    public function __construct(
        string $jsonrpc,
        $id,
        string $method,
        PaginatedRequestParams $params
    ) {
        parent::__construct($method, $jsonrpc, $id, null);
        $this->typedParams = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     jsonrpc: '2.0',
     *     id: string|number,
     *     method: string,
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['jsonrpc', 'id', 'method', 'params']);

        /** @var '2.0' $jsonrpc */
        $jsonrpc = self::asString($data['jsonrpc']);

        /** @var string|number $id */
        $id = self::asStringOrNumber($data['id']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams $params */
        $params = is_array($data['params'])
            ? PaginatedRequestParams::fromArray(self::asArray($data['params']))
            : $data['params'];

        return new self(
            $jsonrpc,
            $id,
            self::asString($data['method']),
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
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams
     */
    public function getTypedParams(): PaginatedRequestParams
    {
        return $this->typedParams;
    }
}

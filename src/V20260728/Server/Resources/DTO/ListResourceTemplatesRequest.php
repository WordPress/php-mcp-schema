<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Resources\DTO;

use WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequest;
use WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams;
use WP\McpSchema\V20260728\Common\Protocol\Union\ClientRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Sent from the client to request a list of resource templates the server has.
 *
 * Note: This class is structurally identical to PaginatedRequest.
 * It exists as a separate type for semantic distinction per MCP specification.
 *
 * @since 2024-11-05
 * @last-updated 2025-11-25 (modified property: params)
 *
 * @mcp-domain Server
 * @mcp-subdomain Resources
 * @mcp-version 2026-07-28
 * @see PaginatedRequest
 */
class ListResourceTemplatesRequest extends PaginatedRequest implements ClientRequestInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'resources/templates/list';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'resources/templates/list';

    /**
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams $params @since 2024-11-05
     * @param '2.0' $jsonrpc @since 2025-11-25
     * @param string|number $id @since 2025-11-25
     */
    public function __construct(
        PaginatedRequestParams $params,
        string $jsonrpc,
        $id
    ) {
        parent::__construct($jsonrpc, $id, self::METHOD, $params);
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams,
     *     jsonrpc: '2.0',
     *     id: string|number,
     *     method: 'resources/templates/list'
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['params', 'jsonrpc', 'id']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\PaginatedRequestParams $params */
        $params = is_array($data['params'])
            ? PaginatedRequestParams::fromArray(self::asArray($data['params']))
            : $data['params'];

        /** @var '2.0' $jsonrpc */
        $jsonrpc = self::asString($data['jsonrpc']);

        /** @var string|number $id */
        $id = self::asStringOrNumber($data['id']);

        return new self(
            $params,
            $jsonrpc,
            $id
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

        return $result;
    }
}

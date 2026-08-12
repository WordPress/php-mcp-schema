<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\JsonRpc\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Common params for any request.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Common
 * @mcp-subdomain JsonRpc
 * @mcp-version 2026-07-28
 */
class RequestParams extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * @since 2025-11-25
     *
     * @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject
     */
    protected RequestMetaObject $_meta;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2025-11-25
     */
    public function __construct(
        RequestMetaObject $_meta
    ) {
        $this->_meta = $_meta;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['_meta']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta */
        $_meta = is_array($data['_meta'])
            ? RequestMetaObject::fromArray(self::asArray($data['_meta']))
            : $data['_meta'];

        return new self(
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
        $result = [];

        $result['_meta'] = $this->_meta->toArray();

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject
     */
    public function get_meta(): RequestMetaObject
    {
        return $this->_meta;
    }
}

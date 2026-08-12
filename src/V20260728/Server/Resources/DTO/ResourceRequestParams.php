<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Resources\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Common params for resource-related requests.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Resources
 * @mcp-version 2026-07-28
 */
class ResourceRequestParams extends RequestParams
{
    use ValidatesRequiredFields;

    /**
     * The URI of the resource. The URI can use any protocol; it is up to the server how to interpret it.
     *
     * @since 2025-11-25
     *
     * @var string
     */
    protected string $uri;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2025-11-25
     * @param string $uri @since 2025-11-25
     */
    public function __construct(
        RequestMetaObject $_meta,
        string $uri
    ) {
        parent::__construct($_meta);
        $this->uri = $uri;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject,
     *     uri: string
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['_meta', 'uri']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta */
        $_meta = is_array($data['_meta'])
            ? RequestMetaObject::fromArray(self::asArray($data['_meta']))
            : $data['_meta'];

        return new self(
            $_meta,
            self::asString($data['uri'])
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

        $result['uri'] = $this->uri;

        return $result;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}

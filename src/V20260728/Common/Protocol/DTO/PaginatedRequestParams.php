<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Common params for paginated requests.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class PaginatedRequestParams extends RequestParams
{
    use ValidatesRequiredFields;

    /**
     * An opaque token representing the current pagination position.
     * If provided, the server should return results starting after this cursor.
     *
     * @since 2025-11-25
     *
     * @var string|null
     */
    protected ?string $cursor;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2025-11-25
     * @param string|null $cursor @since 2025-11-25
     */
    public function __construct(
        RequestMetaObject $_meta,
        ?string $cursor = null
    ) {
        parent::__construct($_meta);
        $this->cursor = $cursor;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject,
     *     cursor?: string|null
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
            $_meta,
            self::asStringOrNull($data['cursor'] ?? null)
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

        if ($this->cursor !== null) {
            $result['cursor'] = $this->cursor;
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getCursor(): ?string
    {
        return $this->cursor;
    }
}

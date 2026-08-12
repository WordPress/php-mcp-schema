<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Resources\DTO;

use WP\McpSchema\V20260728\Common\Protocol\DTO\CacheableResult;
use WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * The result returned by the server for a {@link ReadResourceRequest | resources/read} request.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: cacheScope, resultType, ttlMs; modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Resources
 * @mcp-version 2026-07-28
 */
class ReadResourceResult extends CacheableResult implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['ttlMs', 'cacheScope', '_meta', 'resultType', 'contents'];

    /**
     * @since 2024-11-05
     *
     * @var array<\WP\McpSchema\V20260728\Common\Protocol\DTO\TextResourceContents|\WP\McpSchema\V20260728\Common\Protocol\DTO\BlobResourceContents>
     */
    protected array $contents;

    /**
     * @param float $ttlMs @since 2026-07-28
     * @param 'public'|'private' $cacheScope @since 2026-07-28
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param array<\WP\McpSchema\V20260728\Common\Protocol\DTO\TextResourceContents|\WP\McpSchema\V20260728\Common\Protocol\DTO\BlobResourceContents> $contents @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2024-11-05
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        float $ttlMs,
        string $cacheScope,
        $resultType,
        array $contents,
        ?ResultMetaObject $_meta = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $ttlMs, $cacheScope, $_meta, $additionalProperties);
        $this->contents = $contents;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     ttlMs: float,
     *     cacheScope: 'public'|'private',
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     contents: array<\WP\McpSchema\V20260728\Common\Protocol\DTO\TextResourceContents|\WP\McpSchema\V20260728\Common\Protocol\DTO\BlobResourceContents>
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['ttlMs', 'cacheScope', 'resultType', 'contents']);

        /** @var 'public'|'private' $cacheScope */
        $cacheScope = self::asString($data['cacheScope']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var array<\WP\McpSchema\V20260728\Common\Protocol\DTO\TextResourceContents|\WP\McpSchema\V20260728\Common\Protocol\DTO\BlobResourceContents> $contents */
        $contents = self::asArray($data['contents']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? ResultMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            self::asFloat($data['ttlMs']),
            $cacheScope,
            $resultType,
            $contents,
            $_meta,
            self::additionalFields($data, self::KNOWN_KEYS)
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

        $result['contents'] = array_map(static fn($item) => (is_object($item) && method_exists($item, 'toArray')) ? $item->toArray() : $item, $this->contents);

        return $result;
    }

    /**
     * @return array<\WP\McpSchema\V20260728\Common\Protocol\DTO\TextResourceContents|\WP\McpSchema\V20260728\Common\Protocol\DTO\BlobResourceContents>
     */
    public function getContents(): array
    {
        return $this->contents;
    }
}

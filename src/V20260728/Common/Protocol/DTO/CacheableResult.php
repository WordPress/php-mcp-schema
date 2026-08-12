<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A result that supports a time-to-live (TTL) hint for client-side caching.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class CacheableResult extends Result
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType', 'ttlMs', 'cacheScope'];

    /**
     * A hint from the server indicating how long (in milliseconds) the
     * client MAY cache this response before re-fetching. Semantics are
     * analogous to HTTP Cache-Control max-age.
     *
     * - If 0, The response SHOULD be considered immediately stale,
     * The client MAY re-fetch every time the result is needed.
     * - If positive, the client SHOULD consider the result fresh for this many
     * milliseconds after receiving the response.
     *
     * @since 2026-07-28
     *
     * @var float
     */
    protected float $ttlMs;

    /**
     * Indicates the intended scope of the cached response, analogous to HTTP
     * `Cache-Control: public` vs `Cache-Control: private`.
     *
     * - `"public"`: The response does not contain user-specific data. Any
     * client or intermediary (e.g., shared gateway, caching proxy) MAY cache
     * the response and serve it across authorization contexts.
     * - `"private"`: The response MAY be cached and reused only within the
     * same authorization context. Caches MUST NOT be shared across
     * authorization contexts (e.g., a different access token requires a
     * different cache).
     *
     * @since 2026-07-28
     *
     * @var 'public'|'private'
     */
    protected string $cacheScope;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param float $ttlMs @since 2026-07-28
     * @param 'public'|'private' $cacheScope @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2026-07-28
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        float $ttlMs,
        string $cacheScope,
        ?ResultMetaObject $_meta = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $_meta, $additionalProperties);
        $this->ttlMs = $ttlMs;
        $this->cacheScope = $cacheScope;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     ttlMs: float,
     *     cacheScope: 'public'|'private'
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['resultType', 'ttlMs', 'cacheScope']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var 'public'|'private' $cacheScope */
        $cacheScope = self::asString($data['cacheScope']);

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? ResultMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $resultType,
            self::asFloat($data['ttlMs']),
            $cacheScope,
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

        $result['ttlMs'] = $this->ttlMs;
        $result['cacheScope'] = $this->cacheScope;

        return $result;
    }

    /**
     * @return float
     */
    public function getTtlMs(): float
    {
        return $this->ttlMs;
    }

    /**
     * @return 'public'|'private'
     */
    public function getCacheScope(): string
    {
        return $this->cacheScope;
    }
}

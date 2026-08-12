<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilities;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * The result returned by the server for a {@link DiscoverRequest | server/discover} request.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class DiscoverResult extends CacheableResult implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['ttlMs', 'cacheScope', '_meta', 'resultType', 'supportedVersions', 'capabilities', 'instructions'];

    /**
     * MCP Protocol Versions this server supports. The client should choose a
     * version from this list for use in subsequent requests.
     *
     * @since 2026-07-28
     *
     * @var array<string>
     */
    protected array $supportedVersions;

    /**
     * The capabilities of the server.
     *
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilities
     */
    protected ServerCapabilities $capabilities;

    /**
     * Natural-language guidance describing the server and its features.
     *
     * This can be used by clients to improve an LLM's understanding of
     * available tools (e.g., by including it in a system prompt). It should
     * focus on information that helps the model use the server effectively
     * and should not duplicate information already in tool descriptions.
     *
     * @since 2026-07-28
     *
     * @var string|null
     */
    protected ?string $instructions;

    /**
     * @param float $ttlMs @since 2026-07-28
     * @param 'public'|'private' $cacheScope @since 2026-07-28
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param array<string> $supportedVersions @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilities $capabilities @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2026-07-28
     * @param string|null $instructions @since 2026-07-28
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        float $ttlMs,
        string $cacheScope,
        $resultType,
        array $supportedVersions,
        ServerCapabilities $capabilities,
        ?ResultMetaObject $_meta = null,
        ?string $instructions = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $ttlMs, $cacheScope, $_meta, $additionalProperties);
        $this->supportedVersions = $supportedVersions;
        $this->capabilities = $capabilities;
        $this->instructions = $instructions;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     ttlMs: float,
     *     cacheScope: 'public'|'private',
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     supportedVersions: array<string>,
     *     capabilities: array<string, mixed>|\WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilities,
     *     instructions?: string|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['ttlMs', 'cacheScope', 'resultType', 'supportedVersions', 'capabilities']);

        /** @var 'public'|'private' $cacheScope */
        $cacheScope = self::asString($data['cacheScope']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilities $capabilities */
        $capabilities = is_array($data['capabilities'])
            ? ServerCapabilities::fromArray(self::asArray($data['capabilities']))
            : $data['capabilities'];

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
            self::asStringArray($data['supportedVersions']),
            $capabilities,
            $_meta,
            self::asStringOrNull($data['instructions'] ?? null),
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

        $result['supportedVersions'] = $this->supportedVersions;
        $result['capabilities'] = $this->capabilities->toArray();
        if ($this->instructions !== null) {
            $result['instructions'] = $this->instructions;
        }

        return $result;
    }

    /**
     * @return array<string>
     */
    public function getSupportedVersions(): array
    {
        return $this->supportedVersions;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilities
     */
    public function getCapabilities(): ServerCapabilities
    {
        return $this->capabilities;
    }

    /**
     * @return string|null
     */
    public function getInstructions(): ?string
    {
        return $this->instructions;
    }
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: resultType; modified property: _meta)
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class PaginatedResult extends Result
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType', 'nextCursor'];

    /**
     * An opaque token representing the pagination position after the last returned result.
     * If present, there may be more results available.
     *
     * @since 2024-11-05
     *
     * @var string|null
     */
    protected ?string $nextCursor;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2024-11-05
     * @param string|null $nextCursor @since 2024-11-05
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        ?ResultMetaObject $_meta = null,
        ?string $nextCursor = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $_meta, $additionalProperties);
        $this->nextCursor = $nextCursor;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     nextCursor?: string|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['resultType']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? ResultMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $resultType,
            $_meta,
            self::asStringOrNull($data['nextCursor'] ?? null),
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

        if ($this->nextCursor !== null) {
            $result['nextCursor'] = $this->nextCursor;
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getNextCursor(): ?string
    {
        return $this->nextCursor;
    }
}

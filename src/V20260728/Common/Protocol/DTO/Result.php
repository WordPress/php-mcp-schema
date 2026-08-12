<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Common result fields.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: resultType; modified property: _meta)
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class Result extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType'];

    /**
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null
     */
    protected ?ResultMetaObject $_meta;

    /**
     * Indicates the type of the result, which allows the client to determine
     * how to parse the result object.
     *
     * Servers implementing this protocol version MUST include this field.
     * For backward compatibility, when a client receives a result from a
     * server implementing an earlier protocol version (which does not include
     * `resultType`), the client MUST treat the absent field as `"complete"`.
     *
     * @since 2026-07-28
     *
     * @var "complete"|"input_required"|string
     */
    protected $resultType;

    /**
     * Keys carried on the wire that this type does not model. Preserved verbatim so unrecognized fields survive a round trip.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $additionalProperties;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2024-11-05
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        ?ResultMetaObject $_meta = null,
        ?array $additionalProperties = null
    ) {
        $this->resultType = $resultType;
        $this->_meta = $_meta;
        $this->additionalProperties = $additionalProperties;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string
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
        $result = [];

        if ($this->_meta !== null) {
            $result['_meta'] = $this->_meta->toArray();
        }
        $result['resultType'] = $this->resultType;

        return $result + ($this->additionalProperties ?? []);
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null
     */
    public function get_meta(): ?ResultMetaObject
    {
        return $this->_meta;
    }

    /**
     * @return "complete"|"input_required"|string
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdditionalProperties(): ?array
    {
        return $this->additionalProperties;
    }
}

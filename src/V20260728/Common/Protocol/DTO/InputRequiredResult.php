<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * An InputRequiredResult sent by the server to indicate that additional input is needed
 * before the request can be completed.
 *
 * At least one of `inputRequests` or `requestState` MUST be present.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class InputRequiredResult extends Result implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType', 'inputRequests', 'requestState'];

    /**
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequests|null
     */
    protected ?InputRequests $inputRequests;

    /**
     * @since 2026-07-28
     *
     * @var string|null
     */
    protected ?string $requestState;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequests|null $inputRequests @since 2026-07-28
     * @param string|null $requestState @since 2026-07-28
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        ?ResultMetaObject $_meta = null,
        ?InputRequests $inputRequests = null,
        ?string $requestState = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $_meta, $additionalProperties);
        $this->inputRequests = $inputRequests;
        $this->requestState = $requestState;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     inputRequests?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequests|null,
     *     requestState?: string|null
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

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequests|null $inputRequests */
        $inputRequests = isset($data['inputRequests'])
            ? (is_array($data['inputRequests'])
                ? InputRequests::fromArray(self::asArray($data['inputRequests']))
                : $data['inputRequests'])
            : null;

        return new self(
            $resultType,
            $_meta,
            $inputRequests,
            self::asStringOrNull($data['requestState'] ?? null),
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

        if ($this->inputRequests !== null) {
            $result['inputRequests'] = $this->inputRequests->toArray();
        }
        if ($this->requestState !== null) {
            $result['requestState'] = $this->requestState;
        }

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequests|null
     */
    public function getInputRequests(): ?InputRequests
    {
        return $this->inputRequests;
    }

    /**
     * @return string|null
     */
    public function getRequestState(): ?string
    {
        return $this->requestState;
    }
}

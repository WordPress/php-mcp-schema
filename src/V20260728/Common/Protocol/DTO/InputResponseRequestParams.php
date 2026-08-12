<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class InputResponseRequestParams extends RequestParams
{
    use ValidatesRequiredFields;

    /**
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null
     */
    protected ?InputResponses $inputResponses;

    /**
     * @since 2026-07-28
     *
     * @var string|null
     */
    protected ?string $requestState;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null $inputResponses @since 2026-07-28
     * @param string|null $requestState @since 2026-07-28
     */
    public function __construct(
        RequestMetaObject $_meta,
        ?InputResponses $inputResponses = null,
        ?string $requestState = null
    ) {
        parent::__construct($_meta);
        $this->inputResponses = $inputResponses;
        $this->requestState = $requestState;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject,
     *     inputResponses?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null,
     *     requestState?: string|null
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

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null $inputResponses */
        $inputResponses = isset($data['inputResponses'])
            ? (is_array($data['inputResponses'])
                ? InputResponses::fromArray(self::asArray($data['inputResponses']))
                : $data['inputResponses'])
            : null;

        return new self(
            $_meta,
            $inputResponses,
            self::asStringOrNull($data['requestState'] ?? null)
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

        if ($this->inputResponses !== null) {
            $result['inputResponses'] = $this->inputResponses->toArray();
        }
        if ($this->requestState !== null) {
            $result['requestState'] = $this->requestState;
        }

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null
     */
    public function getInputResponses(): ?InputResponses
    {
        return $this->inputResponses;
    }

    /**
     * @return string|null
     */
    public function getRequestState(): ?string
    {
        return $this->requestState;
    }
}

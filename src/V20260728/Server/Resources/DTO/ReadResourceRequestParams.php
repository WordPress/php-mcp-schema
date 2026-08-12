<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Resources\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a `resources/read` request.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (added properties: inputResponses, requestState; modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Resources
 * @mcp-version 2026-07-28
 */
class ReadResourceRequestParams extends ResourceRequestParams
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
     * @param string $uri @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null $inputResponses @since 2026-07-28
     * @param string|null $requestState @since 2026-07-28
     */
    public function __construct(
        string $uri,
        RequestMetaObject $_meta,
        ?InputResponses $inputResponses = null,
        ?string $requestState = null
    ) {
        parent::__construct($_meta, $uri);
        $this->inputResponses = $inputResponses;
        $this->requestState = $requestState;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     uri: string,
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject,
     *     inputResponses?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null,
     *     requestState?: string|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['uri', '_meta']);

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
            self::asString($data['uri']),
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

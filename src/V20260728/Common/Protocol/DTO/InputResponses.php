<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Factory\InputResponseFactory;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A map of client responses to server-initiated requests.
 * Keys correspond to the keys in the {@link InputRequests} map;
 * values are the client's result for each request.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class InputResponses extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = [];

    /**
     * Keys carried on the wire that this type does not model. Preserved verbatim so unrecognized fields survive a round trip.
     *
     * @var array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface>
     */
    protected array $additionalProperties;

    /**
     * @param array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface> $additionalProperties
     */
    public function __construct(
        array $additionalProperties
    ) {
        $this->additionalProperties = $additionalProperties;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface> $additionalProperties */
        $additionalProperties = array_map(
            static fn($item) => is_array($item)
                ? InputResponseFactory::fromArray($item)
                : $item,
            (self::additionalFields($data, self::KNOWN_KEYS) ?? [])
        );

        return new self(
            $additionalProperties
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

        return $result + array_map(static fn($item) => $item->toArray(), $this->additionalProperties);
    }

    /**
     * @return array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface>
     */
    public function getAdditionalProperties(): array
    {
        return $this->additionalProperties;
    }
}

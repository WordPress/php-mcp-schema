<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Factory\InputRequestFactory;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A map of server-initiated requests that the client must fulfill.
 * Keys are server-assigned identifiers; values are the request objects.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class InputRequests extends AbstractDataTransferObject
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
     * @var array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface>
     */
    protected array $additionalProperties;

    /**
     * @param array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface> $additionalProperties
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
        /** @var array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface> $additionalProperties */
        $additionalProperties = array_map(
            static fn($item) => is_array($item)
                ? InputRequestFactory::fromArray($item)
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
     * @return array<string, \WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface>
     */
    public function getAdditionalProperties(): array
    {
        return $this->additionalProperties;
    }
}

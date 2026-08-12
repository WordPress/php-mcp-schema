<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Tools\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * An optional JSON Schema object defining the structure of the tool's output returned in
 * the structuredContent field of a {@link CallToolResult}. This can be any valid JSON Schema 2020-12.
 *
 * Defaults to JSON Schema 2020-12 when no explicit `$schema` is provided.
 *
 * @mcp-domain Server
 * @mcp-subdomain Tools
 * @mcp-version 2026-07-28
 */
class ToolOutputSchema extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['$schema'];

    /**
     * @var string|null
     */
    protected ?string $schema;

    /**
     * Keys carried on the wire that this type does not model. Preserved verbatim so unrecognized fields survive a round trip.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $additionalProperties;

    /**
     * @param string|null $schema
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        ?string $schema = null,
        ?array $additionalProperties = null
    ) {
        $this->schema = $schema;
        $this->additionalProperties = $additionalProperties;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     '$schema'?: string|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::asStringOrNull($data['$schema'] ?? null),
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

        if ($this->schema !== null) {
            $result['$schema'] = $this->schema;
        }

        return $result + ($this->additionalProperties ?? []);
    }

    /**
     * @return string|null
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdditionalProperties(): ?array
    {
        return $this->additionalProperties;
    }
}

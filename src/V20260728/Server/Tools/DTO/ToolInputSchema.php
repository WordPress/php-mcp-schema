<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Tools\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A JSON Schema object defining the expected parameters for the tool.
 *
 * Tool arguments are always JSON objects, so `type: "object"` is required at the root.
 * Beyond that, any JSON Schema 2020-12 keyword may appear alongside `type` — including
 * composition keywords (`oneOf`, `anyOf`, `allOf`, `not`), conditional keywords
 * (`if`/`then`/`else`), reference keywords (`$ref`, `$defs`, `$anchor`), and any other
 * standard validation or annotation keywords.
 *
 * Property schemas may carry an `x-mcp-header` annotation to mirror the
 * argument value into an HTTP header on the Streamable HTTP transport. See
 * the Streamable HTTP transport specification for the validity and
 * extraction rules.
 *
 * Defaults to JSON Schema 2020-12 when no explicit `$schema` is provided.
 *
 * @mcp-domain Server
 * @mcp-subdomain Tools
 * @mcp-version 2026-07-28
 */
class ToolInputSchema extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    public const TYPE = 'object';

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['$schema', 'type'];

    /**
     * @var string|null
     */
    protected ?string $schema;

    /**
     * @var 'object'
     */
    protected string $type;

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
        $this->type = self::TYPE;
        $this->schema = $schema;
        $this->additionalProperties = $additionalProperties;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     '$schema'?: string|null,
     *     type: 'object'
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
        $result['type'] = $this->type;

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
     * @return 'object'
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdditionalProperties(): ?array
    {
        return $this->additionalProperties;
    }
}

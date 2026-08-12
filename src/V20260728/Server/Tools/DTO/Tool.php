<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Tools\DTO;

use WP\McpSchema\V20260728\Common\Core\DTO\Icon;
use WP\McpSchema\V20260728\Common\Protocol\DTO\BaseMetadata;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Definition for a tool the client can call.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (removed properties: execution; modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Tools
 * @mcp-version 2026-07-28
 */
class Tool extends BaseMetadata
{
    use ValidatesRequiredFields;

    /**
     * A human-readable description of the tool.
     *
     * This can be used by clients to improve the LLM's understanding of available tools. It can be thought of like a "hint" to the model.
     *
     * @since 2024-11-05
     *
     * @var string|null
     */
    protected ?string $description;

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
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Server\Tools\DTO\ToolInputSchema
     */
    protected ToolInputSchema $inputSchema;

    /**
     * An optional JSON Schema object defining the structure of the tool's output returned in
     * the structuredContent field of a {@link CallToolResult}. This can be any valid JSON Schema 2020-12.
     *
     * Defaults to JSON Schema 2020-12 when no explicit `$schema` is provided.
     *
     * @since 2025-06-18
     *
     * @var \WP\McpSchema\V20260728\Server\Tools\DTO\ToolOutputSchema|null
     */
    protected ?ToolOutputSchema $outputSchema;

    /**
     * Optional additional tool information.
     *
     * Display name precedence order is: `title`, `annotations.title`, then `name`.
     *
     * @since 2025-03-26
     *
     * @var \WP\McpSchema\V20260728\Server\Tools\DTO\ToolAnnotations|null
     */
    protected ?ToolAnnotations $annotations;

    /**
     * @since 2025-06-18
     *
     * @var array<string, mixed>|null
     */
    protected ?array $_meta;

    /**
     * Optional set of sized icons that the client can display in a user interface.
     *
     * Clients that support rendering icons MUST support at least the following MIME types:
     * - `image/png` - PNG images (safe, universal compatibility)
     * - `image/jpeg` (and `image/jpg`) - JPEG images (safe, universal compatibility)
     *
     * Clients that support rendering icons SHOULD also support:
     * - `image/svg+xml` - SVG images (scalable but requires security precautions)
     * - `image/webp` - WebP images (modern, efficient format)
     *
     * @since 2025-11-25
     *
     * @var array<\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null
     */
    protected ?array $icons;

    /**
     * @param string $name @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Server\Tools\DTO\ToolInputSchema $inputSchema @since 2024-11-05
     * @param string|null $title @since 2025-06-18
     * @param string|null $description @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Server\Tools\DTO\ToolOutputSchema|null $outputSchema @since 2025-06-18
     * @param \WP\McpSchema\V20260728\Server\Tools\DTO\ToolAnnotations|null $annotations @since 2025-03-26
     * @param array<string, mixed>|null $_meta @since 2025-06-18
     * @param array<\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null $icons @since 2025-11-25
     */
    public function __construct(
        string $name,
        ToolInputSchema $inputSchema,
        ?string $title = null,
        ?string $description = null,
        ?ToolOutputSchema $outputSchema = null,
        ?ToolAnnotations $annotations = null,
        ?array $_meta = null,
        ?array $icons = null
    ) {
        parent::__construct($name, $title);
        $this->inputSchema = $inputSchema;
        $this->description = $description;
        $this->outputSchema = $outputSchema;
        $this->annotations = $annotations;
        $this->_meta = $_meta;
        $this->icons = $icons;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     name: string,
     *     title?: string|null,
     *     description?: string|null,
     *     inputSchema: array<string, mixed>|\WP\McpSchema\V20260728\Server\Tools\DTO\ToolInputSchema,
     *     outputSchema?: array<string, mixed>|\WP\McpSchema\V20260728\Server\Tools\DTO\ToolOutputSchema|null,
     *     annotations?: array<string, mixed>|\WP\McpSchema\V20260728\Server\Tools\DTO\ToolAnnotations|null,
     *     _meta?: array<string, mixed>|null,
     *     icons?: array<array<string, mixed>|\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['name', 'inputSchema']);

        /** @var \WP\McpSchema\V20260728\Server\Tools\DTO\ToolInputSchema $inputSchema */
        $inputSchema = is_array($data['inputSchema'])
            ? ToolInputSchema::fromArray(self::asArray($data['inputSchema']))
            : $data['inputSchema'];

        /** @var \WP\McpSchema\V20260728\Server\Tools\DTO\ToolOutputSchema|null $outputSchema */
        $outputSchema = isset($data['outputSchema'])
            ? (is_array($data['outputSchema'])
                ? ToolOutputSchema::fromArray(self::asArray($data['outputSchema']))
                : $data['outputSchema'])
            : null;

        /** @var \WP\McpSchema\V20260728\Server\Tools\DTO\ToolAnnotations|null $annotations */
        $annotations = isset($data['annotations'])
            ? (is_array($data['annotations'])
                ? ToolAnnotations::fromArray(self::asArray($data['annotations']))
                : $data['annotations'])
            : null;

        /** @var array<\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null $icons */
        $icons = isset($data['icons'])
            ? array_map(
                static fn($item) => is_array($item)
                    ? Icon::fromArray($item)
                    : $item,
                self::asArray($data['icons'])
            )
            : null;

        return new self(
            self::asString($data['name']),
            $inputSchema,
            self::asStringOrNull($data['title'] ?? null),
            self::asStringOrNull($data['description'] ?? null),
            $outputSchema,
            $annotations,
            self::asArrayOrNull($data['_meta'] ?? null),
            $icons
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

        if ($this->description !== null) {
            $result['description'] = $this->description;
        }
        $result['inputSchema'] = $this->inputSchema->toArray();
        if ($this->outputSchema !== null) {
            $result['outputSchema'] = $this->outputSchema->toArray();
        }
        if ($this->annotations !== null) {
            $result['annotations'] = $this->annotations->toArray();
        }
        if ($this->_meta !== null) {
            $result['_meta'] = $this->_meta;
        }
        if ($this->icons !== null) {
            $result['icons'] = array_map(static fn($item) => $item->toArray(), $this->icons);
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Tools\DTO\ToolInputSchema
     */
    public function getInputSchema(): ToolInputSchema
    {
        return $this->inputSchema;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Tools\DTO\ToolOutputSchema|null
     */
    public function getOutputSchema(): ?ToolOutputSchema
    {
        return $this->outputSchema;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Tools\DTO\ToolAnnotations|null
     */
    public function getAnnotations(): ?ToolAnnotations
    {
        return $this->annotations;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get_meta(): ?array
    {
        return $this->_meta;
    }

    /**
     * @return array<\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null
     */
    public function getIcons(): ?array
    {
        return $this->icons;
    }
}

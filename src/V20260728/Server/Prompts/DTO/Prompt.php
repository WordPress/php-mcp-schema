<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Prompts\DTO;

use WP\McpSchema\V20260728\Common\Core\DTO\Icon;
use WP\McpSchema\V20260728\Common\Protocol\DTO\BaseMetadata;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A prompt or prompt template that the server offers.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Prompts
 * @mcp-version 2026-07-28
 */
class Prompt extends BaseMetadata
{
    use ValidatesRequiredFields;

    /**
     * An optional description of what this prompt provides
     *
     * @since 2024-11-05
     *
     * @var string|null
     */
    protected ?string $description;

    /**
     * A list of arguments to use for templating the prompt.
     *
     * @since 2024-11-05
     *
     * @var array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptArgument>|null
     */
    protected ?array $arguments;

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
     * @param string|null $title @since 2025-06-18
     * @param string|null $description @since 2024-11-05
     * @param array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptArgument>|null $arguments @since 2024-11-05
     * @param array<string, mixed>|null $_meta @since 2025-06-18
     * @param array<\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null $icons @since 2025-11-25
     */
    public function __construct(
        string $name,
        ?string $title = null,
        ?string $description = null,
        ?array $arguments = null,
        ?array $_meta = null,
        ?array $icons = null
    ) {
        parent::__construct($name, $title);
        $this->description = $description;
        $this->arguments = $arguments;
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
     *     arguments?: array<array<string, mixed>|\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptArgument>|null,
     *     _meta?: array<string, mixed>|null,
     *     icons?: array<array<string, mixed>|\WP\McpSchema\V20260728\Common\Core\DTO\Icon>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['name']);

        /** @var array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptArgument>|null $arguments */
        $arguments = isset($data['arguments'])
            ? array_map(
                static fn($item) => is_array($item)
                    ? PromptArgument::fromArray($item)
                    : $item,
                self::asArray($data['arguments'])
            )
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
            self::asStringOrNull($data['title'] ?? null),
            self::asStringOrNull($data['description'] ?? null),
            $arguments,
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
        if ($this->arguments !== null) {
            $result['arguments'] = array_map(static fn($item) => $item->toArray(), $this->arguments);
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
     * @return array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptArgument>|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
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

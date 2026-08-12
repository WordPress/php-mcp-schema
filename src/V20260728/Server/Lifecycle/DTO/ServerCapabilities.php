<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Lifecycle\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Capabilities that a server may support. Known capabilities are defined here, in this schema, but this is not a closed set: any server can define its own, additional capabilities.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: extensions; removed properties: tasks; modified properties: completions, logging)
 *
 * @mcp-domain Server
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
class ServerCapabilities extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Experimental, non-standard capabilities that the server supports.
     *
     * @since 2024-11-05
     *
     * @var array<string, mixed>|null
     */
    protected ?array $experimental;

    /**
     * Present if the server supports sending log messages to the client.
     *
     * @since 2024-11-05
     *
     * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $logging;

    /**
     * Present if the server supports argument autocompletion suggestions.
     *
     * @since 2025-03-26
     *
     * @var array<string, mixed>|null
     */
    protected ?array $completions;

    /**
     * Present if the server offers any prompt templates.
     *
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesPrompts|null
     */
    protected ?ServerCapabilitiesPrompts $prompts;

    /**
     * Present if the server offers any resources to read.
     *
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesResources|null
     */
    protected ?ServerCapabilitiesResources $resources;

    /**
     * Present if the server offers any tools to call.
     *
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesTools|null
     */
    protected ?ServerCapabilitiesTools $tools;

    /**
     * Optional MCP extensions that the server supports. Keys are extension identifiers
     * (e.g., "io.modelcontextprotocol/tasks"), and values are per-extension settings
     * objects. An empty object indicates support with no settings.
     *
     * Keys MUST follow the {@link MetaObject | `_meta` key naming rules}, with a
     * mandatory prefix.
     *
     * @since 2026-07-28
     *
     * @var array<string, mixed>|null
     */
    protected ?array $extensions;

    /**
     * @param array<string, mixed>|null $experimental @since 2024-11-05
     * @param array<string, mixed>|null $logging @since 2024-11-05
     * @param array<string, mixed>|null $completions @since 2025-03-26
     * @param \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesPrompts|null $prompts @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesResources|null $resources @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesTools|null $tools @since 2024-11-05
     * @param array<string, mixed>|null $extensions @since 2026-07-28
     */
    public function __construct(
        ?array $experimental = null,
        ?array $logging = null,
        ?array $completions = null,
        ?ServerCapabilitiesPrompts $prompts = null,
        ?ServerCapabilitiesResources $resources = null,
        ?ServerCapabilitiesTools $tools = null,
        ?array $extensions = null
    ) {
        $this->experimental = $experimental;
        $this->logging = $logging;
        $this->completions = $completions;
        $this->prompts = $prompts;
        $this->resources = $resources;
        $this->tools = $tools;
        $this->extensions = $extensions;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     experimental?: array<string, mixed>|null,
     *     logging?: array<string, mixed>|null,
     *     completions?: array<string, mixed>|null,
     *     prompts?: array<string, mixed>|\WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesPrompts|null,
     *     resources?: array<string, mixed>|\WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesResources|null,
     *     tools?: array<string, mixed>|\WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesTools|null,
     *     extensions?: array<string, mixed>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesPrompts|null $prompts */
        $prompts = isset($data['prompts'])
            ? (is_array($data['prompts'])
                ? ServerCapabilitiesPrompts::fromArray(self::asArray($data['prompts']))
                : $data['prompts'])
            : null;

        /** @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesResources|null $resources */
        $resources = isset($data['resources'])
            ? (is_array($data['resources'])
                ? ServerCapabilitiesResources::fromArray(self::asArray($data['resources']))
                : $data['resources'])
            : null;

        /** @var \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesTools|null $tools */
        $tools = isset($data['tools'])
            ? (is_array($data['tools'])
                ? ServerCapabilitiesTools::fromArray(self::asArray($data['tools']))
                : $data['tools'])
            : null;

        return new self(
            self::asArrayOrNull($data['experimental'] ?? null),
            self::asArrayOrNull($data['logging'] ?? null),
            self::asArrayOrNull($data['completions'] ?? null),
            $prompts,
            $resources,
            $tools,
            self::asArrayOrNull($data['extensions'] ?? null)
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

        if ($this->experimental !== null) {
            $result['experimental'] = $this->experimental;
        }
        if ($this->logging !== null) {
            $result['logging'] = $this->logging;
        }
        if ($this->completions !== null) {
            $result['completions'] = $this->completions;
        }
        if ($this->prompts !== null) {
            $result['prompts'] = $this->prompts->toArray();
        }
        if ($this->resources !== null) {
            $result['resources'] = $this->resources->toArray();
        }
        if ($this->tools !== null) {
            $result['tools'] = $this->tools->toArray();
        }
        if ($this->extensions !== null) {
            $result['extensions'] = $this->extensions;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExperimental(): ?array
    {
        return $this->experimental;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getLogging(): ?array
    {
        return $this->logging;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCompletions(): ?array
    {
        return $this->completions;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesPrompts|null
     */
    public function getPrompts(): ?ServerCapabilitiesPrompts
    {
        return $this->prompts;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesResources|null
     */
    public function getResources(): ?ServerCapabilitiesResources
    {
        return $this->resources;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Lifecycle\DTO\ServerCapabilitiesTools|null
     */
    public function getTools(): ?ServerCapabilitiesTools
    {
        return $this->tools;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }
}

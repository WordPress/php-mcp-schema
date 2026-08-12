<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Lifecycle\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Capabilities a client may support. Known capabilities are defined here, in this schema, but this is not a closed set: any client can define its own, additional capabilities.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: extensions; removed properties: tasks)
 *
 * @mcp-domain Client
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
class ClientCapabilities extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Experimental, non-standard capabilities that the client supports.
     *
     * @since 2024-11-05
     *
     * @var array<string, mixed>|null
     */
    protected ?array $experimental;

    /**
     * Present if the client supports listing roots.
     *
     * @since 2024-11-05
     *
     * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
     *
     * @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesRoots|null
     */
    protected ?ClientCapabilitiesRoots $roots;

    /**
     * Present if the client supports sampling from an LLM.
     *
     * @since 2024-11-05
     *
     * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
     *
     * @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesSampling|null
     */
    protected ?ClientCapabilitiesSampling $sampling;

    /**
     * Present if the client supports elicitation from the server.
     *
     * @since 2025-06-18
     *
     * @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesElicitation|null
     */
    protected ?ClientCapabilitiesElicitation $elicitation;

    /**
     * Optional MCP extensions that the client supports. Keys are extension identifiers
     * (e.g., "io.modelcontextprotocol/oauth-client-credentials"), and values are
     * per-extension settings objects. An empty object indicates support with no settings.
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
     * @param \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesRoots|null $roots @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesSampling|null $sampling @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesElicitation|null $elicitation @since 2025-06-18
     * @param array<string, mixed>|null $extensions @since 2026-07-28
     */
    public function __construct(
        ?array $experimental = null,
        ?ClientCapabilitiesRoots $roots = null,
        ?ClientCapabilitiesSampling $sampling = null,
        ?ClientCapabilitiesElicitation $elicitation = null,
        ?array $extensions = null
    ) {
        $this->experimental = $experimental;
        $this->roots = $roots;
        $this->sampling = $sampling;
        $this->elicitation = $elicitation;
        $this->extensions = $extensions;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     experimental?: array<string, mixed>|null,
     *     roots?: array<string, mixed>|\WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesRoots|null,
     *     sampling?: array<string, mixed>|\WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesSampling|null,
     *     elicitation?: array<string, mixed>|\WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesElicitation|null,
     *     extensions?: array<string, mixed>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesRoots|null $roots */
        $roots = isset($data['roots'])
            ? (is_array($data['roots'])
                ? ClientCapabilitiesRoots::fromArray(self::asArray($data['roots']))
                : $data['roots'])
            : null;

        /** @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesSampling|null $sampling */
        $sampling = isset($data['sampling'])
            ? (is_array($data['sampling'])
                ? ClientCapabilitiesSampling::fromArray(self::asArray($data['sampling']))
                : $data['sampling'])
            : null;

        /** @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesElicitation|null $elicitation */
        $elicitation = isset($data['elicitation'])
            ? (is_array($data['elicitation'])
                ? ClientCapabilitiesElicitation::fromArray(self::asArray($data['elicitation']))
                : $data['elicitation'])
            : null;

        return new self(
            self::asArrayOrNull($data['experimental'] ?? null),
            $roots,
            $sampling,
            $elicitation,
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
        if ($this->roots !== null) {
            $result['roots'] = $this->roots->toArray();
        }
        if ($this->sampling !== null) {
            $result['sampling'] = $this->sampling->toArray();
        }
        if ($this->elicitation !== null) {
            $result['elicitation'] = $this->elicitation->toArray();
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
     * @return \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesRoots|null
     */
    public function getRoots(): ?ClientCapabilitiesRoots
    {
        return $this->roots;
    }

    /**
     * @return \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesSampling|null
     */
    public function getSampling(): ?ClientCapabilitiesSampling
    {
        return $this->sampling;
    }

    /**
     * @return \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilitiesElicitation|null
     */
    public function getElicitation(): ?ClientCapabilitiesElicitation
    {
        return $this->elicitation;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }
}

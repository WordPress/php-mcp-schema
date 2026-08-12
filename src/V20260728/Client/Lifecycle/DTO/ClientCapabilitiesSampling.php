<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Lifecycle\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Present if the client supports sampling from an LLM.
 *
 * @mcp-domain Client
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
class ClientCapabilitiesSampling extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Whether the client supports context inclusion via `includeContext` parameter. If not declared, servers SHOULD only use `includeContext: "none"` (or omit it).
     *
     * @var array<string, mixed>|null
     */
    protected ?array $context;

    /**
     * Whether the client supports tool use via `tools` and `toolChoice` parameters.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $tools;

    /**
     * @param array<string, mixed>|null $context
     * @param array<string, mixed>|null $tools
     */
    public function __construct(
        ?array $context = null,
        ?array $tools = null
    ) {
        $this->context = $context;
        $this->tools = $tools;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     context?: array<string, mixed>|null,
     *     tools?: array<string, mixed>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::asArrayOrNull($data['context'] ?? null),
            self::asArrayOrNull($data['tools'] ?? null)
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

        if ($this->context !== null) {
            $result['context'] = $this->context;
        }
        if ($this->tools !== null) {
            $result['tools'] = $this->tools;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getTools(): ?array
    {
        return $this->tools;
    }
}

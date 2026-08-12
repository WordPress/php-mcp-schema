<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Extends {@link MetaObject} with additional result-specific fields. All key naming rules from `MetaObject` apply.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class ResultMetaObject extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Identifies the server software producing the response. Servers SHOULD
     * include this field on every response unless specifically configured not
     * to do so.
     *
     * The {@link Implementation} schema requires `name` and `version`; other
     * fields are optional.
     *
     * The value is self-reported by the server and is not verified by the
     * protocol. It is intended for display, logging, and debugging. Clients
     * SHOULD NOT use it to change their behavior, and SHOULD NOT rely on it for
     * security decisions.
     *
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null
     */
    protected ?Implementation $serverInfo;

    /**
     * @param \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null $serverInfo @since 2026-07-28
     */
    public function __construct(
        ?Implementation $serverInfo = null
    ) {
        $this->serverInfo = $serverInfo;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     'io.modelcontextprotocol/serverInfo'?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null $serverInfo */
        $serverInfo = isset($data['io.modelcontextprotocol/serverInfo'])
            ? (is_array($data['io.modelcontextprotocol/serverInfo'])
                ? Implementation::fromArray(self::asArray($data['io.modelcontextprotocol/serverInfo']))
                : $data['io.modelcontextprotocol/serverInfo'])
            : null;

        return new self(
            $serverInfo
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

        if ($this->serverInfo !== null) {
            $result['io.modelcontextprotocol/serverInfo'] = $this->serverInfo->toArray();
        }

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null
     */
    public function getServerInfo(): ?Implementation
    {
        return $this->serverInfo;
    }
}

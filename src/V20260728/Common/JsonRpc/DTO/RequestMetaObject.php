<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\JsonRpc\DTO;

use WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilities;
use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Extends {@link MetaObject} with additional request-specific fields. All key naming rules from `MetaObject` apply.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain JsonRpc
 * @mcp-version 2026-07-28
 */
class RequestMetaObject extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['progressToken', 'io.modelcontextprotocol/protocolVersion', 'io.modelcontextprotocol/clientInfo', 'io.modelcontextprotocol/clientCapabilities', 'io.modelcontextprotocol/logLevel'];

    /**
     * If specified, the caller is requesting out-of-band progress notifications for this request (as represented by {@link ProgressNotification | notifications/progress}). The value of this parameter is an opaque token that will be attached to any subsequent notifications. The receiver is not obligated to provide these notifications.
     *
     * @since 2026-07-28
     *
     * @var string|number|null
     */
    protected $progressToken;

    /**
     * The MCP Protocol Version being used for this request. Required.
     *
     * For the HTTP transport, this value MUST match the `MCP-Protocol-Version`
     * header; otherwise the server MUST return a `400 Bad Request`. If the
     * server does not support the requested version, it MUST return an
     * {@link UnsupportedProtocolVersionError}.
     *
     * @since 2026-07-28
     *
     * @var string
     */
    protected string $protocolVersion;

    /**
     * Identifies the client software making the request. Clients SHOULD
     * include this field on every request unless specifically configured not
     * to do so.
     *
     * The {@link Implementation} schema requires `name` and `version`; other
     * fields are optional.
     *
     * The value is self-reported by the client and is not verified by the
     * protocol. It is intended for display, logging, and debugging. Servers
     * SHOULD NOT use it to change their behavior, and SHOULD NOT rely on it for
     * security decisions.
     *
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null
     */
    protected ?Implementation $clientInfo;

    /**
     * The client's capabilities for this specific request. Required.
     *
     * Capabilities are declared per-request rather than once at initialization;
     * an empty object means the client supports no optional capabilities.
     * Servers MUST NOT infer capabilities from prior requests.
     *
     * @since 2026-07-28
     *
     * @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilities
     */
    protected ClientCapabilities $clientCapabilities;

    /**
     * The desired log level for this request. Optional.
     *
     * If absent, the server MUST NOT send any {@link LoggingMessageNotification | notifications/message}
     * notifications for this request. The client opts in to log messages by
     * explicitly setting a level. Replaces the former `logging/setLevel` RPC.
     *
     * @since 2026-07-28
     *
     * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
     *
     * @var 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'|null
     */
    protected ?string $logLevel;

    /**
     * Keys carried on the wire that this type does not model. Preserved verbatim so unrecognized fields survive a round trip.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $additionalProperties;

    /**
     * @param string $protocolVersion @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilities $clientCapabilities @since 2026-07-28
     * @param string|number|null $progressToken @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null $clientInfo @since 2026-07-28
     * @param 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'|null $logLevel @since 2026-07-28
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        string $protocolVersion,
        ClientCapabilities $clientCapabilities,
        $progressToken = null,
        ?Implementation $clientInfo = null,
        ?string $logLevel = null,
        ?array $additionalProperties = null
    ) {
        $this->protocolVersion = $protocolVersion;
        $this->clientCapabilities = $clientCapabilities;
        $this->progressToken = $progressToken;
        $this->clientInfo = $clientInfo;
        $this->logLevel = $logLevel;
        $this->additionalProperties = $additionalProperties;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     progressToken?: string|number|null,
     *     'io.modelcontextprotocol/protocolVersion': string,
     *     'io.modelcontextprotocol/clientInfo'?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null,
     *     'io.modelcontextprotocol/clientCapabilities': array<string, mixed>|\WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilities,
     *     'io.modelcontextprotocol/logLevel'?: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['io.modelcontextprotocol/protocolVersion', 'io.modelcontextprotocol/clientCapabilities']);

        /** @var \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilities $clientCapabilities */
        $clientCapabilities = is_array($data['io.modelcontextprotocol/clientCapabilities'])
            ? ClientCapabilities::fromArray(self::asArray($data['io.modelcontextprotocol/clientCapabilities']))
            : $data['io.modelcontextprotocol/clientCapabilities'];

        /** @var string|number|null $progressToken */
        $progressToken = isset($data['progressToken'])
            ? self::asStringOrNumberOrNull($data['progressToken'])
            : null;

        /** @var \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null $clientInfo */
        $clientInfo = isset($data['io.modelcontextprotocol/clientInfo'])
            ? (is_array($data['io.modelcontextprotocol/clientInfo'])
                ? Implementation::fromArray(self::asArray($data['io.modelcontextprotocol/clientInfo']))
                : $data['io.modelcontextprotocol/clientInfo'])
            : null;

        /** @var 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'|null $logLevel */
        $logLevel = isset($data['io.modelcontextprotocol/logLevel'])
            ? self::asStringOrNull($data['io.modelcontextprotocol/logLevel'])
            : null;

        return new self(
            self::asString($data['io.modelcontextprotocol/protocolVersion']),
            $clientCapabilities,
            $progressToken,
            $clientInfo,
            $logLevel,
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

        if ($this->progressToken !== null) {
            $result['progressToken'] = $this->progressToken;
        }
        $result['io.modelcontextprotocol/protocolVersion'] = $this->protocolVersion;
        if ($this->clientInfo !== null) {
            $result['io.modelcontextprotocol/clientInfo'] = $this->clientInfo->toArray();
        }
        $result['io.modelcontextprotocol/clientCapabilities'] = $this->clientCapabilities->toArray();
        if ($this->logLevel !== null) {
            $result['io.modelcontextprotocol/logLevel'] = $this->logLevel;
        }

        return $result + ($this->additionalProperties ?? []);
    }

    /**
     * @return string|number|null
     */
    public function getProgressToken()
    {
        return $this->progressToken;
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Lifecycle\DTO\Implementation|null
     */
    public function getClientInfo(): ?Implementation
    {
        return $this->clientInfo;
    }

    /**
     * @return \WP\McpSchema\V20260728\Client\Lifecycle\DTO\ClientCapabilities
     */
    public function getClientCapabilities(): ClientCapabilities
    {
        return $this->clientCapabilities;
    }

    /**
     * @return 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'|null
     */
    public function getLogLevel(): ?string
    {
        return $this->logLevel;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdditionalProperties(): ?array
    {
        return $this->additionalProperties;
    }
}

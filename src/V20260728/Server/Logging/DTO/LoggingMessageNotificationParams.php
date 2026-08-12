<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Logging\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a `notifications/message` notification.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
 *
 * @mcp-domain Server
 * @mcp-subdomain Logging
 * @mcp-version 2026-07-28
 */
class LoggingMessageNotificationParams extends NotificationParams
{
    use ValidatesRequiredFields;

    /**
     * The severity of this log message.
     *
     * @since 2025-11-25
     *
     * @var 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'
     */
    protected string $level;

    /**
     * An optional name of the logger issuing this message.
     *
     * @since 2025-11-25
     *
     * @var string|null
     */
    protected ?string $logger;

    /**
     * The data to be logged, such as a string message or an object. Any JSON serializable type is allowed here.
     *
     * @since 2025-11-25
     *
     * @var mixed
     */
    protected $data;

    /**
     * @param 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency' $level @since 2025-11-25
     * @param mixed $data @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta @since 2025-11-25
     * @param string|null $logger @since 2025-11-25
     */
    public function __construct(
        string $level,
        $data,
        ?NotificationMetaObject $_meta = null,
        ?string $logger = null
    ) {
        parent::__construct($_meta);
        $this->level = $level;
        $this->data = $data;
        $this->logger = $logger;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null,
     *     level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency',
     *     logger?: string|null,
     *     data: mixed
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['level', 'data']);

        /** @var 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency' $level */
        $level = self::asString($data['level']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? NotificationMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $level,
            $data['data'],
            $_meta,
            self::asStringOrNull($data['logger'] ?? null)
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

        $result['level'] = $this->level;
        if ($this->logger !== null) {
            $result['logger'] = $this->logger;
        }
        $result['data'] = $this->data;

        return $result;
    }

    /**
     * @return 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return string|null
     */
    public function getLogger(): ?string
    {
        return $this->logger;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}

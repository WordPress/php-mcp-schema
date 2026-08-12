<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a `notifications/cancelled` notification.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class CancelledNotificationParams extends NotificationParams
{
    use ValidatesRequiredFields;

    /**
     * The ID of the request to cancel.
     *
     * This MUST correspond to the ID of a request the client previously issued.
     *
     * @since 2025-11-25
     *
     * @var string|number
     */
    protected $requestId;

    /**
     * An optional string describing the reason for the cancellation. This MAY be logged or presented to the user.
     *
     * @since 2025-11-25
     *
     * @var string|null
     */
    protected ?string $reason;

    /**
     * @param string|number $requestId @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta @since 2025-11-25
     * @param string|null $reason @since 2025-11-25
     */
    public function __construct(
        $requestId,
        ?NotificationMetaObject $_meta = null,
        ?string $reason = null
    ) {
        parent::__construct($_meta);
        $this->requestId = $requestId;
        $this->reason = $reason;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null,
     *     requestId: string|number,
     *     reason?: string|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['requestId']);

        /** @var string|number $requestId */
        $requestId = self::asStringOrNumber($data['requestId']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? NotificationMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $requestId,
            $_meta,
            self::asStringOrNull($data['reason'] ?? null)
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

        $result['requestId'] = $this->requestId;
        if ($this->reason !== null) {
            $result['reason'] = $this->reason;
        }

        return $result;
    }

    /**
     * @return string|number
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}

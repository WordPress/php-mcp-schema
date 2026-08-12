<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\JsonRpc\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Common params for any notification.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Common
 * @mcp-subdomain JsonRpc
 * @mcp-version 2026-07-28
 */
class NotificationParams extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * @since 2025-11-25
     *
     * @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null
     */
    protected ?NotificationMetaObject $_meta;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta @since 2025-11-25
     */
    public function __construct(
        ?NotificationMetaObject $_meta = null
    ) {
        $this->_meta = $_meta;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? NotificationMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $_meta
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

        if ($this->_meta !== null) {
            $result['_meta'] = $this->_meta->toArray();
        }

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null
     */
    public function get_meta(): ?NotificationMetaObject
    {
        return $this->_meta;
    }
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Resources\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationParams;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a `notifications/resources/updated` notification.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Resources
 * @mcp-version 2026-07-28
 */
class ResourceUpdatedNotificationParams extends NotificationParams
{
    use ValidatesRequiredFields;

    /**
     * The URI of the resource that has been updated. This might be a sub-resource of the one that the client actually subscribed to.
     *
     * @since 2025-11-25
     *
     * @var string
     */
    protected string $uri;

    /**
     * @param string $uri @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta @since 2025-11-25
     */
    public function __construct(
        string $uri,
        ?NotificationMetaObject $_meta = null
    ) {
        parent::__construct($_meta);
        $this->uri = $uri;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null,
     *     uri: string
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['uri']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\NotificationMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? NotificationMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            self::asString($data['uri']),
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
        $result = parent::toArray();

        $result['uri'] = $this->uri;

        return $result;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}

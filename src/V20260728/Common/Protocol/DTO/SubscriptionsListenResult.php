<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * The response to a {@link SubscriptionsListenRequest | subscriptions/listen}
 * request, signalling that the subscription has ended gracefully (for example,
 * during server shutdown). Because the listen stream is long-lived, this result
 * is sent only when the server tears the subscription down; an abrupt transport
 * close carries no response. The result body is otherwise empty.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionsListenResult extends Result implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['resultType', '_meta'];

    /**
     * @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenResultMetaObject
     */
    protected SubscriptionsListenResultMetaObject $typed_meta;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenResultMetaObject $_meta @since 2026-07-28
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        SubscriptionsListenResultMetaObject $_meta,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, null, $additionalProperties);
        $this->typed_meta = $_meta;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     resultType: "complete"|"input_required"|string,
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenResultMetaObject
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['resultType', '_meta']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenResultMetaObject $_meta */
        $_meta = is_array($data['_meta'])
            ? SubscriptionsListenResultMetaObject::fromArray(self::asArray($data['_meta']))
            : $data['_meta'];

        return new self(
            $resultType,
            $_meta,
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
        $result = parent::toArray();

        $result['_meta'] = $this->typed_meta->toArray();

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenResultMetaObject
     */
    public function getTyped_meta(): SubscriptionsListenResultMetaObject
    {
        return $this->typed_meta;
    }
}

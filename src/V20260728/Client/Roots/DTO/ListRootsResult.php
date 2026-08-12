<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Roots\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * The result returned by the client for a {@link ListRootsRequest | roots/list} request.
 * This result contains an array of {@link Root} objects, each representing a root directory
 * or file that the server can operate on.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (removed properties: _meta)
 *
 * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
 *
 * @mcp-domain Client
 * @mcp-subdomain Roots
 * @mcp-version 2026-07-28
 */
class ListRootsResult extends AbstractDataTransferObject implements InputResponseInterface
{
    use ValidatesRequiredFields;

    /**
     * @since 2024-11-05
     *
     * @var array<\WP\McpSchema\V20260728\Client\Roots\DTO\Root>
     */
    protected array $roots;

    /**
     * @param array<\WP\McpSchema\V20260728\Client\Roots\DTO\Root> $roots @since 2024-11-05
     */
    public function __construct(
        array $roots
    ) {
        $this->roots = $roots;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     roots: array<array<string, mixed>|\WP\McpSchema\V20260728\Client\Roots\DTO\Root>
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['roots']);

        /** @var array<\WP\McpSchema\V20260728\Client\Roots\DTO\Root> $roots */
        $roots = array_map(
            static fn($item) => is_array($item)
                ? Root::fromArray($item)
                : $item,
            self::asArray($data['roots'])
        );

        return new self(
            $roots
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

        $result['roots'] = array_map(static fn($item) => $item->toArray(), $this->roots);

        return $result;
    }

    /**
     * @return array<\WP\McpSchema\V20260728\Client\Roots\DTO\Root>
     */
    public function getRoots(): array
    {
        return $this->roots;
    }
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Roots\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Sent from the server to request a list of root URIs from the client. Roots allow
 * servers to ask for specific directories or files to operate on. A common example
 * for roots is providing a set of repositories or directories a server should operate
 * on.
 *
 * This request is typically used when the server needs to understand the file system
 * structure or access specific locations that the client has permission to read from.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (modified property: params)
 *
 * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
 *
 * @mcp-domain Client
 * @mcp-subdomain Roots
 * @mcp-version 2026-07-28
 */
class ListRootsRequest extends AbstractDataTransferObject implements InputRequestInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'roots/list';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'roots/list';

    /**
     * @since 2024-11-05
     *
     * @var 'roots/list'
     */
    protected string $method;

    /**
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequestParams|null
     */
    protected ?ListRootsRequestParams $params;

    /**
     * @param \WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequestParams|null $params @since 2024-11-05
     */
    public function __construct(
        ?ListRootsRequestParams $params = null
    ) {
        $this->method = self::METHOD;
        $this->params = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     method: 'roots/list',
     *     params?: array<string, mixed>|\WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequestParams|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var \WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequestParams|null $params */
        $params = isset($data['params'])
            ? (is_array($data['params'])
                ? ListRootsRequestParams::fromArray(self::asArray($data['params']))
                : $data['params'])
            : null;

        return new self(
            $params
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

        $result['method'] = $this->method;
        if ($this->params !== null) {
            $result['params'] = $this->params->toArray();
        }

        return $result;
    }

    /**
     * @return 'roots/list'
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return \WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequestParams|null
     */
    public function getParams(): ?ListRootsRequestParams
    {
        return $this->params;
    }
}

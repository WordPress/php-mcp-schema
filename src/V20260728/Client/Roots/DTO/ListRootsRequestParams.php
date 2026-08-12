<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Roots\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * @mcp-domain Client
 * @mcp-subdomain Roots
 * @mcp-version 2026-07-28
 */
class ListRootsRequestParams extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $_meta;

    /**
     * @param array<string, mixed>|null $_meta
     */
    public function __construct(
        ?array $_meta = null
    ) {
        $this->_meta = $_meta;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::asArrayOrNull($data['_meta'] ?? null)
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
            $result['_meta'] = $this->_meta;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get_meta(): ?array
    {
        return $this->_meta;
    }
}

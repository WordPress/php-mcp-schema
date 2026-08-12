<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Returned when a server rejects a request because the values in the HTTP
 * headers do not match the corresponding values in the request body, or
 * because required headers are missing or malformed. For HTTP, the response
 * status code MUST be `400 Bad Request`.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class HeaderMismatchError extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * @since 2026-07-28
     *
     * @var mixed
     */
    protected $error;

    /**
     * @param mixed $error @since 2026-07-28
     */
    public function __construct(
        $error
    ) {
        $this->error = $error;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     error: mixed
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['error']);

        return new self(
            $data['error']
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

        $result['error'] = $this->error;

        return $result;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
}

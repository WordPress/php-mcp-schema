<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\Error;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A JSON-RPC error indicating that the request is not a valid request object. This error is returned when the message structure does not conform to the JSON-RPC 2.0 specification requirements for a request (e.g., missing required fields like `jsonrpc` or `method`, or using invalid types for these fields).
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class InvalidRequestError extends Error
{
    use ValidatesRequiredFields;

    /**
     * @var -32600
     */
    protected int $typedCode;

    /**
     * @param string $message @since 2026-07-28
     * @param -32600 $code @since 2026-07-28
     * @param mixed|null $data @since 2026-07-28
     */
    public function __construct(
        string $message,
        int $code,
        $data = null
    ) {
        parent::__construct($code, $message, $data);
        $this->typedCode = $code;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     message: string,
     *     data?: mixed|null,
     *     code: -32600
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['message', 'code']);

        /** @var -32600 $code */
        $code = self::asInt($data['code']);

        return new self(
            self::asString($data['message']),
            $code,
            $data['data'] ?? null
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

        $result['code'] = $this->typedCode;

        return $result;
    }

    /**
     * @return -32600
     */
    public function getTypedCode(): int
    {
        return $this->typedCode;
    }
}

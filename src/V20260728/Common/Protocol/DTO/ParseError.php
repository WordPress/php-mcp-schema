<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\Error;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A JSON-RPC error indicating that invalid JSON was received by the server. This error is returned when the server cannot parse the JSON text of a message.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class ParseError extends Error
{
    use ValidatesRequiredFields;

    /**
     * @var -32700
     */
    protected int $typedCode;

    /**
     * @param string $message @since 2026-07-28
     * @param -32700 $code @since 2026-07-28
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
     *     code: -32700
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['message', 'code']);

        /** @var -32700 $code */
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
     * @return -32700
     */
    public function getTypedCode(): int
    {
        return $this->typedCode;
    }
}

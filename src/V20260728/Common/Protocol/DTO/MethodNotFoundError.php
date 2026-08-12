<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\Error;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A JSON-RPC error indicating that the requested method does not exist or is not available.
 *
 * In MCP, a server returns this error when a client invokes a method the server does not implement — either a genuinely unknown method, or one gated behind a server capability the server did not advertise (e.g., calling `prompts/list` when the `prompts` capability was not advertised).
 *
 * A request that requires a client capability the client did not declare is signalled instead by {@link MissingRequiredClientCapabilityError} (`-32021`).
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class MethodNotFoundError extends Error
{
    use ValidatesRequiredFields;

    /**
     * @var -32601
     */
    protected int $typedCode;

    /**
     * @param string $message @since 2026-07-28
     * @param -32601 $code @since 2026-07-28
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
     *     code: -32601
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['message', 'code']);

        /** @var -32601 $code */
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
     * @return -32601
     */
    public function getTypedCode(): int
    {
        return $this->typedCode;
    }
}

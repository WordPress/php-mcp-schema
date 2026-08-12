<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Prompts\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCResultResponse;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A successful response from the server for a {@link GetPromptRequest | prompts/get} request.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Server
 * @mcp-subdomain Prompts
 * @mcp-version 2026-07-28
 */
class GetPromptResultResponse extends JSONRPCResultResponse
{
    use ValidatesRequiredFields;

    /**
     * @var \WP\McpSchema\V20260728\Server\Prompts\DTO\GetPromptResult|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequiredResult
     */
    protected $typedResult;

    /**
     * @param '2.0' $jsonrpc @since 2026-07-28
     * @param string|number $id @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Server\Prompts\DTO\GetPromptResult|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequiredResult $result @since 2026-07-28
     */
    public function __construct(
        string $jsonrpc,
        $id,
        $result
    ) {
        parent::__construct($jsonrpc, $id, $result);
        $this->typedResult = $result;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     jsonrpc: '2.0',
     *     id: string|number,
     *     result: \WP\McpSchema\V20260728\Server\Prompts\DTO\GetPromptResult|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequiredResult
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['jsonrpc', 'id', 'result']);

        /** @var '2.0' $jsonrpc */
        $jsonrpc = self::asString($data['jsonrpc']);

        /** @var string|number $id */
        $id = self::asStringOrNumber($data['id']);

        /** @var \WP\McpSchema\V20260728\Server\Prompts\DTO\GetPromptResult|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequiredResult $result */
        $result = $data['result'];

        return new self(
            $jsonrpc,
            $id,
            $result
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

        $result['result'] = (is_object($this->typedResult) && method_exists($this->typedResult, 'toArray')) ? $this->typedResult->toArray() : $this->typedResult;

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Prompts\DTO\GetPromptResult|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequiredResult
     */
    public function getTypedResult()
    {
        return $this->typedResult;
    }
}

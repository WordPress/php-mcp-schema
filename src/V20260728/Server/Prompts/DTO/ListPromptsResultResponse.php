<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Prompts\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\JSONRPCResultResponse;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A successful response from the server for a {@link ListPromptsRequest | prompts/list} request.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Server
 * @mcp-subdomain Prompts
 * @mcp-version 2026-07-28
 */
class ListPromptsResultResponse extends JSONRPCResultResponse
{
    use ValidatesRequiredFields;

    /**
     * @var \WP\McpSchema\V20260728\Server\Prompts\DTO\ListPromptsResult
     */
    protected ListPromptsResult $typedResult;

    /**
     * @param '2.0' $jsonrpc @since 2026-07-28
     * @param string|number $id @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Server\Prompts\DTO\ListPromptsResult $result @since 2026-07-28
     */
    public function __construct(
        string $jsonrpc,
        $id,
        ListPromptsResult $result
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
     *     result: array<string, mixed>|\WP\McpSchema\V20260728\Server\Prompts\DTO\ListPromptsResult
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

        /** @var \WP\McpSchema\V20260728\Server\Prompts\DTO\ListPromptsResult $result */
        $result = is_array($data['result'])
            ? ListPromptsResult::fromArray(self::asArray($data['result']))
            : $data['result'];

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

        $result['result'] = $this->typedResult->toArray();

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Prompts\DTO\ListPromptsResult
     */
    public function getTypedResult(): ListPromptsResult
    {
        return $this->typedResult;
    }
}

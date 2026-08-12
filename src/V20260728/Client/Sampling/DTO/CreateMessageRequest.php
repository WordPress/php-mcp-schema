<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Sampling\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A request from the server to sample an LLM via the client. The client has full discretion over which model to select. The client should also inform the user before beginning sampling, to allow them to inspect the request (human in the loop) and decide whether to approve it.
 *
 * @since 2024-11-05
 * @last-updated 2025-11-25 (modified property: params)
 *
 * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
 *
 * @mcp-domain Client
 * @mcp-subdomain Sampling
 * @mcp-version 2026-07-28
 */
class CreateMessageRequest extends AbstractDataTransferObject implements InputRequestInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'sampling/createMessage';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'sampling/createMessage';

    /**
     * @since 2024-11-05
     *
     * @var 'sampling/createMessage'
     */
    protected string $method;

    /**
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageRequestParams
     */
    protected CreateMessageRequestParams $params;

    /**
     * @param \WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageRequestParams $params @since 2024-11-05
     */
    public function __construct(
        CreateMessageRequestParams $params
    ) {
        $this->method = self::METHOD;
        $this->params = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     method: 'sampling/createMessage',
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageRequestParams
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['params']);

        /** @var \WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageRequestParams $params */
        $params = is_array($data['params'])
            ? CreateMessageRequestParams::fromArray(self::asArray($data['params']))
            : $data['params'];

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
        $result['params'] = $this->params->toArray();

        return $result;
    }

    /**
     * @return 'sampling/createMessage'
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return \WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageRequestParams
     */
    public function getParams(): CreateMessageRequestParams
    {
        return $this->params;
    }
}

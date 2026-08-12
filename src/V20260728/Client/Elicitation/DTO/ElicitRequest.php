<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Elicitation\DTO;

use WP\McpSchema\V20260728\Client\Elicitation\Factory\ElicitRequestParamsFactory;
use WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface;
use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * A request from the server to elicit additional information from the user via the client.
 *
 * @since 2025-06-18
 * @last-updated 2025-11-25 (modified property: params)
 *
 * @mcp-domain Client
 * @mcp-subdomain Elicitation
 * @mcp-version 2026-07-28
 */
class ElicitRequest extends AbstractDataTransferObject implements InputRequestInterface
{
    use ValidatesRequiredFields;

    public const METHOD = 'elicitation/create';

    public const DISCRIMINATOR_FIELD = 'method';
    public const DISCRIMINATOR_VALUE = 'elicitation/create';

    /**
     * @since 2025-06-18
     *
     * @var 'elicitation/create'
     */
    protected string $method;

    /**
     * @since 2025-06-18
     *
     * @var \WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface
     */
    protected ElicitRequestParamsInterface $params;

    /**
     * @param \WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface $params @since 2025-06-18
     */
    public function __construct(
        ElicitRequestParamsInterface $params
    ) {
        $this->method = self::METHOD;
        $this->params = $params;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     method: 'elicitation/create',
     *     params: array<string, mixed>|\WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['params']);

        /** @var \WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface $params */
        $params = is_array($data['params'])
            ? ElicitRequestParamsFactory::fromArray(self::asArray($data['params']))
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
     * @return 'elicitation/create'
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return \WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface
     */
    public function getParams(): ElicitRequestParamsInterface
    {
        return $this->params;
    }
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Prompts\DTO;

use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponseRequestParams;
use WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Parameters for a `prompts/get` request.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (added properties: inputResponses, requestState; modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Prompts
 * @mcp-version 2026-07-28
 */
class GetPromptRequestParams extends InputResponseRequestParams
{
    use ValidatesRequiredFields;

    /**
     * The name of the prompt or prompt template.
     *
     * @since 2025-11-25
     *
     * @var string
     */
    protected string $name;

    /**
     * Arguments to use for templating the prompt.
     *
     * @since 2025-11-25
     *
     * @var array<string, string>|null
     */
    protected ?array $arguments;

    /**
     * @param \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta @since 2025-11-25
     * @param string $name @since 2025-11-25
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null $inputResponses @since 2026-07-28
     * @param string|null $requestState @since 2026-07-28
     * @param array<string, string>|null $arguments @since 2025-11-25
     */
    public function __construct(
        RequestMetaObject $_meta,
        string $name,
        ?InputResponses $inputResponses = null,
        ?string $requestState = null,
        ?array $arguments = null
    ) {
        parent::__construct($_meta, $inputResponses, $requestState);
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     inputResponses?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null,
     *     requestState?: string|null,
     *     _meta: array<string, mixed>|\WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject,
     *     name: string,
     *     arguments?: array<string, string>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['_meta', 'name']);

        /** @var \WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject $_meta */
        $_meta = is_array($data['_meta'])
            ? RequestMetaObject::fromArray(self::asArray($data['_meta']))
            : $data['_meta'];

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses|null $inputResponses */
        $inputResponses = isset($data['inputResponses'])
            ? (is_array($data['inputResponses'])
                ? InputResponses::fromArray(self::asArray($data['inputResponses']))
                : $data['inputResponses'])
            : null;

        return new self(
            $_meta,
            self::asString($data['name']),
            $inputResponses,
            self::asStringOrNull($data['requestState'] ?? null),
            self::asStringMapOrNull($data['arguments'] ?? null)
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

        $result['name'] = $this->name;
        if ($this->arguments !== null) {
            $result['arguments'] = $this->arguments;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }
}

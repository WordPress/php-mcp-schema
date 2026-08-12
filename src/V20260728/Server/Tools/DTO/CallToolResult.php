<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Tools\DTO;

use WP\McpSchema\V20260728\Common\Protocol\DTO\Result;
use WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject;
use WP\McpSchema\V20260728\Common\Protocol\Factory\ContentBlockFactory;
use WP\McpSchema\V20260728\Common\Protocol\Union\ContentBlockInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * The result returned by the server for a {@link CallToolRequest | tools/call} request.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: resultType; modified properties: _meta, structuredContent)
 *
 * @mcp-domain Server
 * @mcp-subdomain Tools
 * @mcp-version 2026-07-28
 */
class CallToolResult extends Result implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType', 'content', 'structuredContent', 'isError'];

    /**
     * A list of content objects that represent the unstructured result of the tool call.
     *
     * @since 2024-11-05
     *
     * @var array<\WP\McpSchema\V20260728\Common\Protocol\Union\ContentBlockInterface>
     */
    protected array $content;

    /**
     * An optional JSON value that represents the structured result of the tool call.
     *
     * This can be any JSON value (object, array, string, number, boolean, or null)
     * that conforms to the tool's outputSchema if one is defined.
     *
     * @since 2025-06-18
     *
     * @var mixed|null
     */
    protected $structuredContent;

    /**
     * Whether the tool call ended in an error.
     *
     * If not set, this is assumed to be false (the call was successful).
     *
     * Any errors that originate from the tool SHOULD be reported inside the result
     * object, with `isError` set to true, _not_ as an MCP protocol-level error
     * response. Otherwise, the LLM would not be able to see that an error occurred
     * and self-correct.
     *
     * However, any errors in _finding_ the tool, an error indicating that the
     * server does not support tool calls, or any other exceptional conditions,
     * should be reported as an MCP error response.
     *
     * @since 2024-11-05
     *
     * @var bool|null
     */
    protected ?bool $isError;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param array<\WP\McpSchema\V20260728\Common\Protocol\Union\ContentBlockInterface> $content @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2024-11-05
     * @param mixed|null $structuredContent @since 2025-06-18
     * @param bool|null $isError @since 2024-11-05
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        array $content,
        ?ResultMetaObject $_meta = null,
        $structuredContent = null,
        ?bool $isError = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $_meta, $additionalProperties);
        $this->content = $content;
        $this->structuredContent = $structuredContent;
        $this->isError = $isError;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     content: array<array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\Union\ContentBlockInterface>,
     *     structuredContent?: mixed|null,
     *     isError?: bool|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['resultType', 'content']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var array<\WP\McpSchema\V20260728\Common\Protocol\Union\ContentBlockInterface> $content */
        $content = array_map(
            static fn($item) => is_array($item)
                ? ContentBlockFactory::fromArray($item)
                : $item,
            self::asArray($data['content'])
        );

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? ResultMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $resultType,
            $content,
            $_meta,
            $data['structuredContent'] ?? null,
            self::asBoolOrNull($data['isError'] ?? null),
            self::additionalFields($data, self::KNOWN_KEYS)
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

        $result['content'] = array_map(static fn($item) => $item->toArray(), $this->content);
        if ($this->structuredContent !== null) {
            $result['structuredContent'] = $this->structuredContent;
        }
        if ($this->isError !== null) {
            $result['isError'] = $this->isError;
        }

        return $result;
    }

    /**
     * @return array<\WP\McpSchema\V20260728\Common\Protocol\Union\ContentBlockInterface>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return mixed|null
     */
    public function getStructuredContent()
    {
        return $this->structuredContent;
    }

    /**
     * @return bool|null
     */
    public function getIsError(): ?bool
    {
        return $this->isError;
    }
}

<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Prompts\DTO;

use WP\McpSchema\V20260728\Common\Protocol\DTO\Result;
use WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * The result returned by the server for a {@link GetPromptRequest | prompts/get} request.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: resultType; modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Prompts
 * @mcp-version 2026-07-28
 */
class GetPromptResult extends Result implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType', 'description', 'messages'];

    /**
     * An optional description for the prompt.
     *
     * @since 2024-11-05
     *
     * @var string|null
     */
    protected ?string $description;

    /**
     * @since 2024-11-05
     *
     * @var array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptMessage>
     */
    protected array $messages;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptMessage> $messages @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2024-11-05
     * @param string|null $description @since 2024-11-05
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        array $messages,
        ?ResultMetaObject $_meta = null,
        ?string $description = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $_meta, $additionalProperties);
        $this->messages = $messages;
        $this->description = $description;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     description?: string|null,
     *     messages: array<array<string, mixed>|\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptMessage>
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['resultType', 'messages']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptMessage> $messages */
        $messages = array_map(
            static fn($item) => is_array($item)
                ? PromptMessage::fromArray($item)
                : $item,
            self::asArray($data['messages'])
        );

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? ResultMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $resultType,
            $messages,
            $_meta,
            self::asStringOrNull($data['description'] ?? null),
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

        if ($this->description !== null) {
            $result['description'] = $this->description;
        }
        $result['messages'] = array_map(static fn($item) => $item->toArray(), $this->messages);

        return $result;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array<\WP\McpSchema\V20260728\Server\Prompts\DTO\PromptMessage>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}

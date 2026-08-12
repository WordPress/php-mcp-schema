<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Sampling\DTO;

use WP\McpSchema\V20260728\Client\Sampling\Factory\SamplingMessageContentBlockFactory;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * The result returned by the client for a {@link CreateMessageRequest | sampling/createMessage} request.
 * The client should inform the user before returning the sampled message, to allow them
 * to inspect the response (human in the loop) and decide whether to allow the server to see it.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (modified property: _meta)
 *
 * @deprecated Deprecated as of protocol version 2026-07-28 (SEP-2577). Remains in the specification for at least twelve months; see the deprecated features registry.
 *
 * @mcp-domain Client
 * @mcp-subdomain Sampling
 * @mcp-version 2026-07-28
 */
class CreateMessageResult extends SamplingMessage implements InputResponseInterface
{
    use ValidatesRequiredFields;

    /**
     * The name of the model that generated the message.
     *
     * @since 2024-11-05
     *
     * @var string
     */
    protected string $model;

    /**
     * The reason why sampling stopped, if known.
     *
     * Standard values:
     * - `"endTurn"`: Natural end of the assistant's turn
     * - `"stopSequence"`: A stop sequence was encountered
     * - `"maxTokens"`: Maximum token limit was reached
     * - `"toolUse"`: The model wants to use one or more tools
     *
     * This field is an open string to allow for provider-specific stop reasons.
     *
     * @since 2024-11-05
     *
     * @var "endTurn"|"stopSequence"|"maxTokens"|"toolUse"|string|null
     */
    protected $stopReason;

    /**
     * @param 'user'|'assistant' $role @since 2024-11-05
     * @param array<\WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface|\WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface> $content @since 2024-11-05
     * @param string $model @since 2024-11-05
     * @param array<string, mixed>|null $_meta @since 2024-11-05
     * @param "endTurn"|"stopSequence"|"maxTokens"|"toolUse"|string|null $stopReason @since 2024-11-05
     */
    public function __construct(
        string $role,
        array $content,
        string $model,
        ?array $_meta = null,
        $stopReason = null
    ) {
        parent::__construct($role, $content, $_meta);
        $this->model = $model;
        $this->stopReason = $stopReason;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     role: 'user'|'assistant',
     *     content: array<\WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface|\WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface>,
     *     _meta?: array<string, mixed>|null,
     *     model: string,
     *     stopReason?: "endTurn"|"stopSequence"|"maxTokens"|"toolUse"|string|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['role', 'content', 'model']);

        /** @var 'user'|'assistant' $role */
        $role = self::asString($data['role']);

        /** @var array<\WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface|\WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface> $content */
        $content = array_map(
            static fn($item) => is_array($item)
                ? SamplingMessageContentBlockFactory::fromArray($item)
                : $item,
            self::asArray($data['content'])
        );

        /** @var "endTurn"|"stopSequence"|"maxTokens"|"toolUse"|string|null $stopReason */
        $stopReason = isset($data['stopReason'])
            ? $data['stopReason']
            : null;

        return new self(
            $role,
            $content,
            self::asString($data['model']),
            self::asArrayOrNull($data['_meta'] ?? null),
            $stopReason
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

        $result['model'] = $this->model;
        if ($this->stopReason !== null) {
            $result['stopReason'] = $this->stopReason;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return "endTurn"|"stopSequence"|"maxTokens"|"toolUse"|string|null
     */
    public function getStopReason()
    {
        return $this->stopReason;
    }
}

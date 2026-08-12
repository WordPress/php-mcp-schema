<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Server\Core\DTO;

use WP\McpSchema\V20260728\Common\Protocol\DTO\Result;
use WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;
use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * The result returned by the server for a {@link CompleteRequest | completion/complete} request.
 *
 * @since 2024-11-05
 * @last-updated 2026-07-28 (added properties: resultType; modified property: _meta)
 *
 * @mcp-domain Server
 * @mcp-subdomain Core
 * @mcp-version 2026-07-28
 */
class CompleteResult extends Result implements ServerResultInterface
{
    use ValidatesRequiredFields;

    /**
     * Wire keys this class models. Anything else is kept in $additionalProperties.
     *
     * @var array<int, string>
     */
    private const KNOWN_KEYS = ['_meta', 'resultType', 'completion'];

    /**
     * @since 2024-11-05
     *
     * @var \WP\McpSchema\V20260728\Server\Core\DTO\CompleteResultCompletion
     */
    protected CompleteResultCompletion $completion;

    /**
     * @param "complete"|"input_required"|string $resultType @since 2026-07-28
     * @param \WP\McpSchema\V20260728\Server\Core\DTO\CompleteResultCompletion $completion @since 2024-11-05
     * @param \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta @since 2024-11-05
     * @param array<string, mixed>|null $additionalProperties
     */
    public function __construct(
        $resultType,
        CompleteResultCompletion $completion,
        ?ResultMetaObject $_meta = null,
        ?array $additionalProperties = null
    ) {
        parent::__construct($resultType, $_meta, $additionalProperties);
        $this->completion = $completion;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     _meta?: array<string, mixed>|\WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null,
     *     resultType: "complete"|"input_required"|string,
     *     completion: array<string, mixed>|\WP\McpSchema\V20260728\Server\Core\DTO\CompleteResultCompletion
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['resultType', 'completion']);

        /** @var "complete"|"input_required"|string $resultType */
        $resultType = $data['resultType'];

        /** @var \WP\McpSchema\V20260728\Server\Core\DTO\CompleteResultCompletion $completion */
        $completion = is_array($data['completion'])
            ? CompleteResultCompletion::fromArray(self::asArray($data['completion']))
            : $data['completion'];

        /** @var \WP\McpSchema\V20260728\Common\Protocol\DTO\ResultMetaObject|null $_meta */
        $_meta = isset($data['_meta'])
            ? (is_array($data['_meta'])
                ? ResultMetaObject::fromArray(self::asArray($data['_meta']))
                : $data['_meta'])
            : null;

        return new self(
            $resultType,
            $completion,
            $_meta,
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

        $result['completion'] = $this->completion->toArray();

        return $result;
    }

    /**
     * @return \WP\McpSchema\V20260728\Server\Core\DTO\CompleteResultCompletion
     */
    public function getCompletion(): CompleteResultCompletion
    {
        return $this->completion;
    }
}

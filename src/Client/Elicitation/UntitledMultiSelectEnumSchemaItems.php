<?php

declare(strict_types=1);

namespace WP\McpSchema\Client\Elicitation;

use WP\McpSchema\Common\AbstractDataTransferObject;

/**
 * Schema for the array items.
 *
 * @mcp-domain Client
 * @mcp-subdomain Elicitation
 * @mcp-version 2025-11-25
 */
class UntitledMultiSelectEnumSchemaItems extends AbstractDataTransferObject
{
    public const TYPE = 'string';

    /**
     * @var 'string'
     */
    protected string $type;

    /**
     */
    public function __construct()
    {
        $this->type = self::TYPE;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     type: 'string'
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self();
    }

    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [];

        $result['type'] = $this->type;

        return $result;
    }

    /**
     * @return 'string'
     */
    public function getType(): string
    {
        return $this->type;
    }
}

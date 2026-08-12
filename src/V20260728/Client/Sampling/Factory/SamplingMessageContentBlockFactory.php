<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Sampling\Factory;

use WP\McpSchema\V20260728\Client\Sampling\Union\SamplingMessageContentBlockInterface;
use WP\McpSchema\V20260728\Common\Content\DTO\TextContent;
use WP\McpSchema\V20260728\Common\Content\DTO\ImageContent;
use WP\McpSchema\V20260728\Common\Content\DTO\AudioContent;
use WP\McpSchema\V20260728\Client\Sampling\DTO\ToolUseContent;
use WP\McpSchema\V20260728\Client\Sampling\DTO\ToolResultContent;

/**
 * Factory for creating SamplingMessageContentBlock union type instances.
 *
 * @mcp-domain Client
 * @mcp-subdomain Sampling
 * @mcp-version 2026-07-28
 */
final class SamplingMessageContentBlockFactory
{
    /**
     * Registry mapping discriminator values to implementation classes.
     *
     * @var array<string, class-string<SamplingMessageContentBlockInterface>>
     */
    public const REGISTRY = [
        'text' => TextContent::class,
        'image' => ImageContent::class,
        'audio' => AudioContent::class,
        'tool_use' => ToolUseContent::class,
        'tool_result' => ToolResultContent::class,
    ];

    /**
     * Creates an instance from an array.
     *
     * @param array<string, mixed> $data
     * @return SamplingMessageContentBlockInterface
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): SamplingMessageContentBlockInterface
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('Missing discriminator field: type');
        }

        /** @var string $type */
        $type = $data['type'];
        if (!isset(self::REGISTRY[$type])) {
            throw new \InvalidArgumentException(sprintf(
                "Unknown type value '%s'. Valid values: %s",
                $type,
                implode(', ', array_keys(self::REGISTRY))
            ));
        }

        $class = self::REGISTRY[$type];
        return $class::fromArray($data);
    }

    /**
     * Checks if a type value is supported by this factory.
     *
     * @param string $type
     * @return bool
     */
    public static function supports(string $type): bool
    {
        return isset(self::REGISTRY[$type]);
    }

    /**
     * Returns all supported type values.
     *
     * @return array<string>
     */
    public static function types(): array
    {
        return array_keys(self::REGISTRY);
    }
}

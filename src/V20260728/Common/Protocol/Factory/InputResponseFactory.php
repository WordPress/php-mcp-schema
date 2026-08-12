<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\Factory;

use WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface;
use WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageResult;
use WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsResult;
use WP\McpSchema\V20260728\Client\Elicitation\DTO\ElicitResult;

/**
 * Factory for creating InputResponse union type instances from exact object shapes.
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
final class InputResponseFactory
{
    /**
     * Creates an instance from an array.
     *
     * @param array<string, mixed> $data
     * @return InputResponseInterface
     * @throws \InvalidArgumentException When the object shape is unknown or ambiguous.
     */
    public static function fromArray(array $data): InputResponseInterface
    {
        /** @var array<class-string<InputResponseInterface>> $matches */
        $matches = [];

        if (array_key_exists('model', $data)) {
            $matches[] = CreateMessageResult::class;
        }
        if (array_key_exists('roots', $data)) {
            $matches[] = ListRootsResult::class;
        }
        if (array_key_exists('action', $data)) {
            $matches[] = ElicitResult::class;
        }

        if (count($matches) !== 1) {
            throw new \InvalidArgumentException(
                'Unable to determine InputResponse type from object shape.'
            );
        }

        switch ($matches[0]) {
            case CreateMessageResult::class:
                return CreateMessageResult::fromArray($data);
            case ListRootsResult::class:
                return ListRootsResult::fromArray($data);
            case ElicitResult::class:
                return ElicitResult::fromArray($data);
        }

        throw new \LogicException('Matched union member has no hydration route.');
    }
}

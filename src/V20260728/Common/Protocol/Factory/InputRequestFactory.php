<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\Factory;

use WP\McpSchema\V20260728\Common\Protocol\Union\InputRequestInterface;
use WP\McpSchema\V20260728\Client\Sampling\DTO\CreateMessageRequest;
use WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequest;
use WP\McpSchema\V20260728\Client\Elicitation\DTO\ElicitRequest;

/**
 * Factory for creating InputRequest union type instances.
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
final class InputRequestFactory
{
    /**
     * Registry mapping discriminator values to implementation classes.
     *
     * @var array<string, class-string<InputRequestInterface>>
     */
    public const REGISTRY = [
        'sampling/createMessage' => CreateMessageRequest::class,
        'roots/list' => ListRootsRequest::class,
        'elicitation/create' => ElicitRequest::class,
    ];

    /**
     * Creates an instance from an array.
     *
     * @param array<string, mixed> $data
     * @return InputRequestInterface
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): InputRequestInterface
    {
        if (!isset($data['method'])) {
            throw new \InvalidArgumentException('Missing discriminator field: method');
        }

        /** @var string $method */
        $method = $data['method'];
        if (!isset(self::REGISTRY[$method])) {
            throw new \InvalidArgumentException(sprintf(
                "Unknown method value '%s'. Valid values: %s",
                $method,
                implode(', ', array_keys(self::REGISTRY))
            ));
        }

        $class = self::REGISTRY[$method];
        return $class::fromArray($data);
    }

    /**
     * Checks if a method value is supported by this factory.
     *
     * @param string $method
     * @return bool
     */
    public static function supports(string $method): bool
    {
        return isset(self::REGISTRY[$method]);
    }

    /**
     * Returns all supported method values.
     *
     * @return array<string>
     */
    public static function methods(): array
    {
        return array_keys(self::REGISTRY);
    }
}

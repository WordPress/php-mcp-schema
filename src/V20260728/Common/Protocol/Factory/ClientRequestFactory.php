<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\Factory;

use WP\McpSchema\V20260728\Common\Protocol\Union\ClientRequestInterface;
use WP\McpSchema\V20260728\Common\Protocol\DTO\DiscoverRequest;
use WP\McpSchema\V20260728\Server\Core\DTO\CompleteRequest;
use WP\McpSchema\V20260728\Server\Prompts\DTO\GetPromptRequest;
use WP\McpSchema\V20260728\Server\Prompts\DTO\ListPromptsRequest;
use WP\McpSchema\V20260728\Server\Resources\DTO\ListResourcesRequest;
use WP\McpSchema\V20260728\Server\Resources\DTO\ListResourceTemplatesRequest;
use WP\McpSchema\V20260728\Server\Resources\DTO\ReadResourceRequest;
use WP\McpSchema\V20260728\Common\Protocol\DTO\SubscriptionsListenRequest;
use WP\McpSchema\V20260728\Server\Tools\DTO\CallToolRequest;
use WP\McpSchema\V20260728\Server\Tools\DTO\ListToolsRequest;

/**
 * Factory for creating ClientRequest union type instances.
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
final class ClientRequestFactory
{
    /**
     * Registry mapping discriminator values to implementation classes.
     *
     * @var array<string, class-string<ClientRequestInterface>>
     */
    public const REGISTRY = [
        'server/discover' => DiscoverRequest::class,
        'completion/complete' => CompleteRequest::class,
        'prompts/get' => GetPromptRequest::class,
        'prompts/list' => ListPromptsRequest::class,
        'resources/list' => ListResourcesRequest::class,
        'resources/templates/list' => ListResourceTemplatesRequest::class,
        'resources/read' => ReadResourceRequest::class,
        'subscriptions/listen' => SubscriptionsListenRequest::class,
        'tools/call' => CallToolRequest::class,
        'tools/list' => ListToolsRequest::class,
    ];

    /**
     * Creates an instance from an array.
     *
     * @param array<string, mixed> $data
     * @return ClientRequestInterface
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): ClientRequestInterface
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

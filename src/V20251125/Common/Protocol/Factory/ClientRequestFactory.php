<?php

declare(strict_types=1);

namespace WP\McpSchema\V20251125\Common\Protocol\Factory;

use WP\McpSchema\V20251125\Common\Protocol\Union\ClientRequestInterface;
use WP\McpSchema\V20251125\Common\Protocol\DTO\PingRequest;
use WP\McpSchema\V20251125\Common\Protocol\DTO\InitializeRequest;
use WP\McpSchema\V20251125\Server\Core\DTO\CompleteRequest;
use WP\McpSchema\V20251125\Server\Logging\DTO\SetLevelRequest;
use WP\McpSchema\V20251125\Server\Prompts\DTO\GetPromptRequest;
use WP\McpSchema\V20251125\Server\Prompts\DTO\ListPromptsRequest;
use WP\McpSchema\V20251125\Server\Resources\DTO\ListResourcesRequest;
use WP\McpSchema\V20251125\Server\Resources\DTO\ListResourceTemplatesRequest;
use WP\McpSchema\V20251125\Server\Resources\DTO\ReadResourceRequest;
use WP\McpSchema\V20251125\Server\Resources\DTO\SubscribeRequest;
use WP\McpSchema\V20251125\Server\Resources\DTO\UnsubscribeRequest;
use WP\McpSchema\V20251125\Server\Tools\DTO\CallToolRequest;
use WP\McpSchema\V20251125\Server\Tools\DTO\ListToolsRequest;
use WP\McpSchema\V20251125\Common\Tasks\DTO\GetTaskRequest;
use WP\McpSchema\V20251125\Common\Protocol\DTO\GetTaskPayloadRequest;
use WP\McpSchema\V20251125\Common\Tasks\DTO\ListTasksRequest;
use WP\McpSchema\V20251125\Common\Tasks\DTO\CancelTaskRequest;

/**
 * Factory for creating ClientRequest union type instances.
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2025-11-25
 */
final class ClientRequestFactory
{
    /**
     * Registry mapping discriminator values to implementation classes.
     *
     * @var array<string, class-string<ClientRequestInterface>>
     */
    public const REGISTRY = [
        'ping' => PingRequest::class,
        'initialize' => InitializeRequest::class,
        'completion/complete' => CompleteRequest::class,
        'logging/setLevel' => SetLevelRequest::class,
        'prompts/get' => GetPromptRequest::class,
        'prompts/list' => ListPromptsRequest::class,
        'resources/list' => ListResourcesRequest::class,
        'resources/templates/list' => ListResourceTemplatesRequest::class,
        'resources/read' => ReadResourceRequest::class,
        'resources/subscribe' => SubscribeRequest::class,
        'resources/unsubscribe' => UnsubscribeRequest::class,
        'tools/call' => CallToolRequest::class,
        'tools/list' => ListToolsRequest::class,
        'tasks/get' => GetTaskRequest::class,
        'tasks/result' => GetTaskPayloadRequest::class,
        'tasks/list' => ListTasksRequest::class,
        'tasks/cancel' => CancelTaskRequest::class,
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

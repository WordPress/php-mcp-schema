<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

use WP\McpSchema\Common\Protocol\DTO\InitializeResult;
use WP\McpSchema\Server\Prompts\DTO\Prompt;
use WP\McpSchema\Server\Prompts\DTO\PromptArgument;
use WP\McpSchema\Server\Resources\DTO\Resource;
use WP\McpSchema\Server\Tools\DTO\Tool;

$argument = PromptArgument::fromArray(['name' => 'topic']);
$prompt = Prompt::fromArray(['name' => 'explain', 'arguments' => [$argument]]);
$resource = Resource::fromArray(['name' => 'guide', 'uri' => 'https://example.test/guide']);
$tool = Tool::fromArray([
    'name' => 'search',
    'inputSchema' => ['type' => 'object', 'properties' => []],
]);
$initialize = InitializeResult::fromArray([
    'protocolVersion' => '2025-11-25',
    'capabilities' => [],
    'serverInfo' => ['name' => 'wordpress', 'version' => '1.0.0'],
]);

assertType('string', $argument->getName());
assertType('string|null', $argument->getTitle());
assertType('string|null', $argument->getDescription());
assertType('bool|null', $argument->getRequired());
assertType('array<int, WP\\McpSchema\\Server\\Prompts\\DTO\\PromptArgument>|null', $prompt->getArguments());
assertType('string', $resource->getUri());
assertType('int|null', $resource->getSize());
assertType('array<string, mixed>', $tool->getInputSchema());
assertType('array<string, mixed>|null', $tool->getAnnotations());
assertType('array<string, mixed>', $initialize->getCapabilities());
assertType('array<string, mixed>', $initialize->getServerInfo());
assertType('array<string, mixed>', $initialize->toArray());

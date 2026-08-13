<?php

declare(strict_types=1);

use WP\McpSchema\Common\Protocol\DTO\InitializeResult;
use WP\McpSchema\Server\Prompts\DTO\Prompt;
use WP\McpSchema\Server\Prompts\DTO\PromptArgument;
use WP\McpSchema\Server\Resources\DTO\Resource;
use WP\McpSchema\Server\Tools\DTO\Tool;

$toolWire = [
    'name' => 'weather',
    'title' => 'Weather',
    'description' => 'Read the forecast.',
    'inputSchema' => [
        'type' => 'object',
        'properties' => [],
    ],
    'annotations' => [
        'readOnlyHint' => true,
    ],
    '_meta' => [
        'example.test/category' => 'forecast',
    ],
    'icons' => [
        ['src' => 'https://example.test/weather.png', 'mimeType' => 'image/png'],
    ],
];
$tool = Tool::fromArray($toolWire);
assertSameValue('weather', $tool->getName(), 'The Tool facade lost its name.');
assertSameValue('Weather', $tool->getTitle(), 'The Tool facade lost its title.');
assertSameValue('Read the forecast.', $tool->getDescription(), 'The Tool facade lost its description.');
assertSameValue($toolWire['inputSchema'], $tool->getInputSchema(), 'The Tool facade lost its input schema.');
assertSameValue($toolWire['annotations'], $tool->getAnnotations(), 'The Tool facade lost its annotations.');
assertSameValue($toolWire['_meta'], $tool->get_meta(), 'The Tool facade lost its metadata.');
assertSameValue($toolWire['icons'], $tool->getIcons(), 'The Tool facade lost its icons.');
assertSameValue($toolWire, $tool->toArray(), 'The Tool facade did not round trip.');

assertValidationFails(
    static function (): void {
        Tool::fromArray([
            'name' => 'invalid',
            'inputSchema' => ['type' => 'string'],
        ]);
    },
    'The Tool facade bypassed descriptor validation.'
);

$resourceWire = [
    'name' => 'guide',
    'title' => 'Guide',
    'uri' => 'https://example.test/guide',
    'description' => 'The user guide.',
    'mimeType' => 'text/markdown',
    'annotations' => ['audience' => ['user']],
    'size' => 1024,
    '_meta' => ['example.test/version' => 2],
    'icons' => [['src' => 'https://example.test/guide.png']],
];
$resource = Resource::fromArray($resourceWire);
assertSameValue('guide', $resource->getName(), 'The Resource facade lost its name.');
assertSameValue('Guide', $resource->getTitle(), 'The Resource facade lost its title.');
assertSameValue('https://example.test/guide', $resource->getUri(), 'The Resource facade lost its URI.');
assertSameValue('The user guide.', $resource->getDescription(), 'The Resource facade lost its description.');
assertSameValue('text/markdown', $resource->getMimeType(), 'The Resource facade lost its MIME type.');
assertSameValue($resourceWire['annotations'], $resource->getAnnotations(), 'The Resource facade lost its annotations.');
assertSameValue(1024, $resource->getSize(), 'The Resource facade lost its size.');
assertSameValue($resourceWire['_meta'], $resource->get_meta(), 'The Resource facade lost its metadata.');
assertSameValue($resourceWire['icons'], $resource->getIcons(), 'The Resource facade lost its icons.');
assertSameValue($resourceWire, $resource->toArray(), 'The Resource facade did not round trip.');

$promptArgument = PromptArgument::fromArray([
    'name' => 'city',
    'title' => 'City',
    'description' => 'The city to forecast.',
    'required' => true,
]);
$promptWire = [
    'name' => 'weather-prompt',
    'title' => 'Weather prompt',
    'description' => 'Build a weather request.',
    'arguments' => [$promptArgument->toArray()],
    '_meta' => ['example.test/category' => 'weather'],
    'icons' => [['src' => 'https://example.test/prompt.png']],
];
$prompt = Prompt::fromArray([
    'name' => $promptWire['name'],
    'title' => $promptWire['title'],
    'description' => $promptWire['description'],
    'arguments' => [$promptArgument],
    '_meta' => $promptWire['_meta'],
    'icons' => $promptWire['icons'],
]);
$arguments = $prompt->getArguments();
if ($arguments === null || !isset($arguments[0])) {
    throw new RuntimeException('The Prompt facade lost its arguments.');
}
assertSameValue('weather-prompt', $prompt->getName(), 'The Prompt facade lost its name.');
assertSameValue('Weather prompt', $prompt->getTitle(), 'The Prompt facade lost its title.');
assertSameValue('Build a weather request.', $prompt->getDescription(), 'The Prompt facade lost its description.');
assertSameValue('city', $arguments[0]->getName(), 'The PromptArgument facade lost its name.');
assertSameValue('City', $arguments[0]->getTitle(), 'The PromptArgument facade lost its title.');
assertSameValue('The city to forecast.', $arguments[0]->getDescription(), 'The PromptArgument facade lost its description.');
assertSameValue(true, $arguments[0]->getRequired(), 'The PromptArgument facade lost its required flag.');
assertSameValue($promptWire['_meta'], $prompt->get_meta(), 'The Prompt facade lost its metadata.');
assertSameValue($promptWire['icons'], $prompt->getIcons(), 'The Prompt facade lost its icons.');
assertSameValue($promptWire, $prompt->toArray(), 'The Prompt facade did not round trip builder arguments.');

$initializeWire = [
    'protocolVersion' => '2025-11-25',
    'capabilities' => [
        'tools' => ['listChanged' => true],
        'experimental' => [],
    ],
    'serverInfo' => [
        'name' => 'wordpress',
        'version' => '1.0.0',
    ],
    'instructions' => 'Use WordPress abilities.',
    '_meta' => ['example.test/server' => true],
];
$initialize = InitializeResult::fromArray($initializeWire);
assertSameValue('2025-11-25', $initialize->getProtocolVersion(), 'The InitializeResult facade lost its revision.');
assertSameValue($initializeWire['capabilities'], $initialize->getCapabilities(), 'The InitializeResult facade lost its capabilities.');
assertSameValue($initializeWire['serverInfo'], $initialize->getServerInfo(), 'The InitializeResult facade lost its implementation.');
assertSameValue('Use WordPress abilities.', $initialize->getInstructions(), 'The InitializeResult facade lost its instructions.');
assertSameValue($initializeWire['_meta'], $initialize->get_meta(), 'The InitializeResult facade lost its metadata.');
assertSameValue($initializeWire, $initialize->toArray(), 'The InitializeResult facade did not round trip.');

$filteredInitialize = $initialize->toArray();
$filteredInitialize['instructions'] = 'Changed by a public hook.';
$filteredInitialize = InitializeResult::fromArray($filteredInitialize);
assertSameValue(
    'Changed by a public hook.',
    $filteredInitialize->getInstructions(),
    'The InitializeResult facade could not rehydrate a public-hook result.'
);

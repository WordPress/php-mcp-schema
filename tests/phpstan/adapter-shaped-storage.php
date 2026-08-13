<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

use WP\McpSchema\Contract\Record;
use WP\McpSchema\Generated\V20260728Schema;
use WP\McpSchema\Schemas;

/**
 * An Adapter-shaped object that retains hydrated records between request phases.
 *
 * @phpstan-import-type ContentBlockWire from V20260728Schema
 * @phpstan-import-type ContentBlockFields from V20260728Schema
 * @phpstan-import-type ListToolsResultWire from V20260728Schema
 * @phpstan-import-type ListToolsResultFields from V20260728Schema
 * @phpstan-import-type ToolWire from V20260728Schema
 * @phpstan-import-type ToolFields from V20260728Schema
 */
final class AdapterSchemaStore
{
    /** @var Record<ToolWire, ToolFields> */
    private Record $tool;

    /** @var Record<ListToolsResultWire, ListToolsResultFields> */
    private Record $listToolsResult;

    /** @var Record<ContentBlockWire, ContentBlockFields> */
    private Record $contentBlock;

    /**
     * @param Record<ToolWire, ToolFields> $tool
     * @param Record<ListToolsResultWire, ListToolsResultFields> $listToolsResult
     * @param Record<ContentBlockWire, ContentBlockFields> $contentBlock
     */
    public function __construct(Record $tool, Record $listToolsResult, Record $contentBlock)
    {
        $this->tool = $tool;
        $this->listToolsResult = $listToolsResult;
        $this->contentBlock = $contentBlock;
    }

    /** @return Record<ToolWire, ToolFields> */
    public function tool(): Record
    {
        return $this->tool;
    }

    /** @return Record<ListToolsResultWire, ListToolsResultFields> */
    public function listToolsResult(): Record
    {
        return $this->listToolsResult;
    }

    /** @return Record<ContentBlockWire, ContentBlockFields> */
    public function contentBlock(): Record
    {
        return $this->contentBlock;
    }
}

$schema = Schemas::v20260728();
$toolWire = [
    'name' => 'weather',
    'icons' => [
        [
            'src' => 'https://example.com/weather.png',
            'mimeType' => 'image/png',
        ],
    ],
    'annotations' => [
        'title' => 'Weather',
        'readOnlyHint' => true,
    ],
    'inputSchema' => [
        'type' => 'object',
        'properties' => new stdClass(),
    ],
];

$store = new AdapterSchemaStore(
    $schema->tool()->fromArray($toolWire),
    $schema->listToolsResult()->fromArray([
        'resultType' => 'complete',
        'cacheScope' => 'private',
        'ttlMs' => 0,
        'tools' => [$toolWire],
    ]),
    $schema->contentBlock()->fromArray([
        'type' => 'text',
        'text' => 'Sunny',
    ])
);

$storedTool = $store->tool();
assertType('string', $storedTool->get('name'));
$icon = $storedTool->get('icons')[0];
assertType('string', $icon->get('src'));
assertType('string', $icon->get('mimeType'));
$annotations = $storedTool->get('annotations');
assertType('string', $annotations->get('title'));
assertType('bool', $annotations->get('readOnlyHint'));

$listedTool = $store->listToolsResult()->get('tools')[0];
assertType('string', $listedTool->get('name'));
assertType('string', $listedTool->get('icons')[0]->get('src'));

$contentBlock = $store->contentBlock();
assertType("'audio'|'image'|'resource'|'resource_link'|'text'", $contentBlock->get('type'));
$typeName = $contentBlock->typeName();
if ($typeName === 'TextContent') {
    assertType("'TextContent'", $typeName);
}

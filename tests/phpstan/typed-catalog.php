<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

use WP\McpSchema\Schemas;

$result = Schemas::v20260728()->callToolResult()->fromArray([
    'resultType' => 'complete',
    'content' => [
        ['type' => 'text', 'text' => 'typed'],
    ],
    'isError' => false,
]);

assertType('string', $result->get('resultType'));
$content = $result->get('content')[0];
assertType("'audio'|'image'|'resource'|'resource_link'|'text'", $content->get('type'));
assertType('bool', $result->get('isError'));

$wire = $result->toArray();
assertType('string', $wire['resultType']);
assertType('array<int, array<string, mixed>>', $wire['content']);

$wireReady = $result->toWireArray();
assertType('array<string, mixed>', $wireReady);

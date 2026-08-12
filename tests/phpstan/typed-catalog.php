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
assertType(
    'array<int, WP\McpSchema\Contract\Record<array<string, mixed>, array<string, mixed>>>',
    $result->get('content')
);
assertType('bool', $result->get('isError'));

$wire = $result->toArray();
assertType('string', $wire['resultType']);
assertType('array<int, array<string, mixed>>', $wire['content']);

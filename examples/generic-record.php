<?php

declare(strict_types=1);

use WP\McpSchema\Revision;
use WP\McpSchema\Schemas;

require dirname(__DIR__) . '/vendor/autoload.php';

$examples = [
    Revision::V20251125 => [
        'content' => [
            ['type' => 'text', 'text' => 'Legacy result'],
        ],
        'structuredContent' => ['revision' => 'legacy'],
    ],
    Revision::V20260728 => [
        'resultType' => 'complete',
        'content' => [
            ['type' => 'text', 'text' => 'Modern result'],
        ],
        'structuredContent' => 'A primitive is valid in this revision',
    ],
];

foreach ($examples as $revision => $wire) {
    $schema = Schemas::revision($revision);
    $result = $schema->type('CallToolResult')->fromArray($wire);
    $content = $result->get('content')[0];

    echo sprintf(
        "%s: %s -> nested %s (%s)\n",
        $result->revision(),
        $result->typeName(),
        $content->typeName(),
        get_class($content)
    );
    echo 'Revision fingerprint: ' . $schema->fingerprint() . "\n";
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n\n";
}

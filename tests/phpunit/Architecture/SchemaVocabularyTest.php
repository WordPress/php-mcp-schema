<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Internal\Catalog\V2025_11_25;
use WP\McpSchema\Internal\Catalog\V2026_07_28;
use WP\McpSchema\Internal\TypeRegistry;

final class SchemaVocabularyTest extends TestCase
{
    public function test_generated_catalogs_use_exactly_the_reviewed_runtime_vocabulary(): void
    {
        $used = array();
        foreach (array(V2025_11_25::document(), V2026_07_28::document()) as $document) {
            foreach ($document['$defs'] as $schema) {
                self::collectKeywords($schema, $used);
            }
        }

        $actual = array_keys($used);
        sort($actual);
        $expected = TypeRegistry::schemaKeywords();
        sort($expected);

        self::assertSame($expected, $actual);
    }

    /**
     * @param array<string, mixed> $schema
     * @param array<string, true>  $keywords
     */
    private static function collectKeywords(array $schema, array &$keywords): void
    {
        foreach (array_keys($schema) as $keyword) {
            $keywords[$keyword] = true;
        }

        /** @var array<string, array<string, mixed>> $properties */
        $properties = $schema['properties'] ?? array();
        foreach ($properties as $child) {
            self::collectKeywords($child, $keywords);
        }
        if (isset($schema['additionalProperties']) && is_array($schema['additionalProperties'])) {
            self::collectKeywords($schema['additionalProperties'], $keywords);
        }
        if (isset($schema['items']) && is_array($schema['items'])) {
            self::collectKeywords($schema['items'], $keywords);
        }
        foreach (array('allOf', 'anyOf') as $combinator) {
            /** @var array<int, array<string, mixed>> $members */
            $members = $schema[$combinator] ?? array();
            foreach ($members as $child) {
                self::collectKeywords($child, $keywords);
            }
        }
    }
}

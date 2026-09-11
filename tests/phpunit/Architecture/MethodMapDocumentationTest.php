<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Internal\Catalog\V2025_11_25;
use WP\McpSchema\Internal\Catalog\V2026_07_28;

final class MethodMapDocumentationTest extends TestCase
{
    public function test_every_catalog_method_is_documented_with_its_record(): void
    {
        $document = (string) file_get_contents(dirname(__DIR__, 3) . '/docs/methods.md');

        foreach (array(V2025_11_25::messageAvailability(), V2026_07_28::messageAvailability()) as $availability) {
            $maps = array(
                $availability['clientToServer']['requests'],
                $availability['clientToServer']['notifications'],
                $availability['serverToClient']['requests'],
                $availability['serverToClient']['notifications'],
                $availability['embeddedInputs'],
            );
            foreach ($maps as $map) {
                foreach ($map as $method => $definition) {
                    self::assertStringContainsString(sprintf('| `%s` |', $method), $document, $method);
                    self::assertStringContainsString(sprintf('`%s`', $definition), $document, $method);
                }
            }
        }
    }
}

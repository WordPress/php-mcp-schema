<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Generated\Registry;

final class ProductionSurfaceTest extends TestCase
{
    public function test_removed_production_trees_do_not_exist(): void
    {
        $root = dirname(__DIR__, 3);

        self::assertDirectoryDoesNotExist($root . '/src/Client');
        self::assertDirectoryDoesNotExist($root . '/src/Common');
        self::assertDirectoryDoesNotExist($root . '/src/Server');
        self::assertDirectoryDoesNotExist($root . '/skill');
    }

    public function test_production_source_has_no_removed_runtime_path(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                dirname(__DIR__, 3) . '/src',
                \FilesystemIterator::SKIP_DOTS
            )
        );
        $forbidden = array(
            'namespace WP\\McpSchema\\Client',
            'namespace WP\\McpSchema\\Common',
            'namespace WP\\McpSchema\\Server',
            'AbstractDataTransferObject',
            'AbstractEnum',
            'class_alias(',
            'function toArray(',
            'function toArrayWithSkippedNullValues(',
        );

        foreach ($iterator as $file) {
            if (! $file instanceof \SplFileInfo || $file->getExtension() !== 'php') {
                continue;
            }
            $contents = (string) file_get_contents($file->getPathname());
            foreach ($forbidden as $needle) {
                self::assertStringNotContainsString($needle, $contents, $file->getPathname());
            }
        }
    }

    public function test_every_generated_public_symbol_is_autoloadable(): void
    {
        foreach (array_keys(Registry::records()) as $class) {
            self::assertTrue(class_exists($class), $class);
        }
        foreach (array_keys(Registry::contracts()) as $interface) {
            self::assertTrue(interface_exists($interface), $interface);
        }
    }
}

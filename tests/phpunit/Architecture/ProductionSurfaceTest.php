<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Internal\TypeRegistry;

final class ProductionSurfaceTest extends TestCase
{
    public function test_removed_production_trees_do_not_exist(): void
    {
        $root = dirname(__DIR__, 3);

        self::assertDirectoryDoesNotExist($root . '/src/Client');
        self::assertDirectoryDoesNotExist($root . '/src/Common');
        self::assertDirectoryDoesNotExist($root . '/src/Generated');
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
            'namespace WP\\McpSchema\\Generated',
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
        foreach (array_keys(TypeRegistry::records()) as $class) {
            self::assertTrue(class_exists($class), $class);
        }
        foreach (array_keys(TypeRegistry::contracts()) as $interface) {
            self::assertTrue(interface_exists($interface), $interface);
        }
    }

    public function test_composer_uses_one_psr_4_root(): void
    {
        $contents = (string) file_get_contents(dirname(__DIR__, 3) . '/composer.json');
        /** @var array{autoload: array{psr-4: array<string, string>}} $composer */
        $composer = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(array('WP\\McpSchema\\' => 'src/'), $composer['autoload']['psr-4']);
    }
}

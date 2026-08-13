<?php

declare(strict_types=1);

namespace WP\McpSchema;

use LogicException;
use WP\McpSchema\Contract\RevisionSchema;
use WP\McpSchema\Generated\V20251125Schema;
use WP\McpSchema\Generated\V20260728Schema;

/**
 * Explicit entry point for immutable revision catalogs.
 *
 * @phpstan-type SupportedRevisionSchema V20251125Schema|V20260728Schema
 */
final class Schemas
{
    /** @var array<string, SupportedRevisionSchema> */
    private static array $instances = [];

    /** @return SupportedRevisionSchema */
    public static function revision(string $revision): RevisionSchema
    {
        if (isset(self::$instances[$revision])) {
            return self::$instances[$revision];
        }

        switch ($revision) {
            case Revision::V20251125:
                $schema = new V20251125Schema();
                break;
            case Revision::V20260728:
                $schema = new V20260728Schema();
                break;
            default:
                throw new LogicException('Unsupported MCP revision: ' . $revision);
        }

        self::$instances[$revision] = $schema;
        return $schema;
    }

    public static function v20251125(): V20251125Schema
    {
        $schema = self::revision(Revision::V20251125);
        if (!$schema instanceof V20251125Schema) {
            throw new LogicException('Revision catalog mismatch for 2025-11-25');
        }
        return $schema;
    }

    public static function v20260728(): V20260728Schema
    {
        $schema = self::revision(Revision::V20260728);
        if (!$schema instanceof V20260728Schema) {
            throw new LogicException('Revision catalog mismatch for 2026-07-28');
        }
        return $schema;
    }
    private function __construct()
    {
    }
}

<?php

declare(strict_types=1);

use function PHPStan\Testing\assertType;

use WP\McpSchema\Contract\RevisionSchema;
use WP\McpSchema\Generated\V20251125Schema;
use WP\McpSchema\Generated\V20260728Schema;
use WP\McpSchema\Revision;
use WP\McpSchema\Schemas;

/**
 * An Adapter-shaped context that retains the schema selected after negotiation.
 *
 * @phpstan-import-type SupportedRevisionSchema from Schemas
 */
final class NegotiatedSchemaContext
{
    /** @var SupportedRevisionSchema */
    private RevisionSchema $schema;

    /** @param SupportedRevisionSchema $schema */
    public function __construct(RevisionSchema $schema)
    {
        $this->schema = $schema;
    }

    /** @return SupportedRevisionSchema */
    public function schema(): RevisionSchema
    {
        return $this->schema;
    }
}

$selected = Schemas::revision(Revision::V20260728);
assertType(
    'WP\McpSchema\Generated\V20251125Schema|WP\McpSchema\Generated\V20260728Schema',
    $selected
);

$context = new NegotiatedSchemaContext($selected);
$schema = $context->schema();
assertType(
    'WP\McpSchema\Generated\V20251125Schema|WP\McpSchema\Generated\V20260728Schema',
    $schema
);

if ($schema instanceof V20251125Schema) {
    assertType(V20251125Schema::class, $schema);
} elseif ($schema instanceof V20260728Schema) {
    assertType(V20260728Schema::class, $schema);
}

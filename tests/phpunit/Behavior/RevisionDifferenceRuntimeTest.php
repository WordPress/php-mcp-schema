<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Record\CallToolResult;
use WP\McpSchema\Record\ElicitRequest;
use WP\McpSchema\Record\InputRequests;
use WP\McpSchema\Record\NumberSchema;
use WP\McpSchema\Record\UnsupportedProtocolVersionError;
use WP\McpSchema\Schemas;

final class RevisionDifferenceRuntimeTest extends TestCase
{
    public function test_structured_content_widens_to_any_json_value_only_in_2026(): void
    {
        $schemas = Schemas::create();
        $v2025   = $schemas->forVersion(Schemas::V2025_11_25);
        $v2026   = $schemas->forVersion(Schemas::V2026_07_28);

        $new = $v2026->fromArray(CallToolResult::class, array(
            'content'           => array(),
            'resultType'        => 'complete',
            'structuredContent' => 'scalar result',
        ));
        self::assertSame('scalar result', $new->getStructuredContent());

        $list = $v2026->fromArray(CallToolResult::class, array(
            'content'           => array(),
            'resultType'        => 'complete',
            'structuredContent' => array(1, 2),
        ));
        self::assertSame(array(1, 2), $list->getStructuredContent());
        self::assertSame('[1,2]', json_encode($list->getStructuredContent()));

        $object = $v2026->fromArray(CallToolResult::class, array(
            'content'           => array(),
            'resultType'        => 'complete',
            'structuredContent' => (object) array(
                'nested' => array('key' => 'value'),
            ),
        ));
        self::assertInstanceOf(\stdClass::class, $object->getStructuredContent()->nested);
        self::assertSame('value', $object->getStructuredContent()->nested->key);

        $this->expectException(ValidationException::class);
        $v2025->fromArray(CallToolResult::class, array(
            'content'           => array(),
            'structuredContent' => 'scalar result',
        ));
    }

    public function test_number_schema_bounds_widen_without_coercion(): void
    {
        $schemas = Schemas::create();
        $v2025   = $schemas->forVersion(Schemas::V2025_11_25);
        $v2026   = $schemas->forVersion(Schemas::V2026_07_28);

        $number = $v2026->fromArray(NumberSchema::class, array(
            'type'    => 'number',
            'default' => 1.5,
            'minimum' => 0.5,
            'maximum' => 2.5,
        ));
        self::assertSame(1.5, $number->getDefault());

        $this->expectException(ValidationException::class);
        $v2025->fromArray(NumberSchema::class, array(
            'type'    => 'number',
            'default' => 1.5,
        ));
    }

    public function test_embedded_input_maps_hydrate_union_members_without_becoming_rpcs(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $inputs = $schema->fromArray(InputRequests::class, array(
            'question' => array(
                'method' => 'elicitation/create',
                'params' => array(
                    'message'         => 'Please answer',
                    'mode'            => 'form',
                    'requestedSchema' => array(
                        'type'       => 'object',
                        'properties' => array(),
                    ),
                ),
            ),
        ));

        self::assertInstanceOf(ElicitRequest::class, $inputs->get('question'));
        self::assertTrue($schema->allowsEmbeddedInput('elicitation/create'));
        self::assertFalse($schema->allowsClientRequest('elicitation/create'));
    }

    public function test_2026_error_intersections_validate_and_hydrate(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $error  = $schema->fromArray(UnsupportedProtocolVersionError::class, array(
            'jsonrpc' => '2.0',
            'id'      => 1,
            'error'   => array(
                'code'    => -32022,
                'message' => 'Unsupported protocol version',
                'data'    => array(
                    'requested' => '2027-01-01',
                    'supported' => array(Schemas::V2025_11_25, Schemas::V2026_07_28),
                ),
            ),
        ));

        self::assertSame(-32022, $error->getError()->code);
        self::assertSame('2027-01-01', $error->getError()->data->requested);
    }
}

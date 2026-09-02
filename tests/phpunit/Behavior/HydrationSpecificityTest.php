<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Contract\ClientResult as ClientResultContract;
use WP\McpSchema\Contract\JSONRPCMessage;
use WP\McpSchema\Contract\PrimitiveSchemaDefinition;
use WP\McpSchema\Contract\ServerResult;
use WP\McpSchema\Record\CallToolResult;
use WP\McpSchema\Record\ElicitRequestFormParams;
use WP\McpSchema\Record\ElicitResult;
use WP\McpSchema\Record\Error;
use WP\McpSchema\Record\HeaderMismatchError;
use WP\McpSchema\Record\JSONRPCRequest;
use WP\McpSchema\Record\MissingRequiredClientCapabilityError;
use WP\McpSchema\Record\StringSchema;
use WP\McpSchema\Record\UnsupportedProtocolVersionError;
use WP\McpSchema\Record\UntitledSingleSelectEnumSchema;
use WP\McpSchema\Record\URLElicitationRequiredError;
use WP\McpSchema\Schemas;

final class HydrationSpecificityTest extends TestCase
{
    /** @dataProvider revisions */
    public function test_all_overlapping_object_unions_choose_the_most_declared_input_keys(string $revision): void
    {
        $schema = Schemas::create()->forVersion($revision);

        $toolResult = array('content' => array());
        if ($revision === Schemas::V2026_07_28) {
            $toolResult['resultType'] = 'complete';
        }
        self::assertInstanceOf(
            CallToolResult::class,
            $schema->fromArray(ServerResult::class, $toolResult)
        );

        self::assertInstanceOf(
            UntitledSingleSelectEnumSchema::class,
            $schema->fromArray(PrimitiveSchemaDefinition::class, array(
                'type'  => 'string',
                'title' => 'Choice',
                'enum'  => array('one', 'two'),
            ))
        );
        self::assertInstanceOf(
            StringSchema::class,
            $schema->fromArray(PrimitiveSchemaDefinition::class, array(
                'type'   => 'string',
                'format' => 'email',
                'enum'   => array('one', 'two'),
            ))
        );

        self::assertInstanceOf(
            JSONRPCRequest::class,
            $schema->fromArray(JSONRPCMessage::class, array(
                'jsonrpc' => '2.0',
                'id'      => 1,
                'method'  => 'example',
            ))
        );

        $params = $schema->fromArray(ElicitRequestFormParams::class, array(
            'message'         => 'Choose one',
            'requestedSchema' => array(
                'type'       => 'object',
                'properties' => array(
                    'choice' => array(
                        'type' => 'string',
                        'enum' => array('one', 'two'),
                    ),
                ),
            ),
        ));
        self::assertInstanceOf(
            UntitledSingleSelectEnumSchema::class,
            $params->getRequestedSchema()->properties->choice
        );
    }

    public function test_2025_client_result_overlap_chooses_the_concrete_result(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $result = $schema->fromArray(ClientResultContract::class, array(
            'action'  => 'accept',
            'content' => array('quantity' => 1.5),
        ));

        self::assertInstanceOf(ElicitResult::class, $result);
        self::assertSame(1.5, $result->getContent()->quantity);
    }

    public function test_cached_repeated_shapes_preserve_byte_identical_wire_output(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $item = array(
            'type'     => 'resource',
            'resource' => array(
                'uri'  => 'file:///example.txt',
                'text' => 'example',
            ),
        );
        $value = array('content' => array_fill(0, 1000, $item));
        $json = (string) json_encode($value, JSON_THROW_ON_ERROR);

        self::assertSame(
            $json,
            json_encode($schema->fromJson(CallToolResult::class, $json))
        );
    }

    public static function revisions(): array
    {
        return array(
            Schemas::V2025_11_25 => array(Schemas::V2025_11_25),
            Schemas::V2026_07_28 => array(Schemas::V2026_07_28),
        );
    }

    /**
     * @dataProvider nominalErrorCases
     * @param class-string $rootClass
     * @param array<string, mixed> $value
     */
    public function test_safe_single_reference_all_of_fields_hydrate_nominal_errors(
        string $revision,
        string $rootClass,
        array $value,
        int $expectedCode
    ): void {
        $response = Schemas::create()->forVersion($revision)->fromArray($rootClass, $value);
        $error = $response->getError();

        self::assertInstanceOf(Error::class, $error);
        self::assertSame($expectedCode, $error->getCode());
    }

    /**
     * @return array<string, array{0: string, 1: class-string, 2: array<string, mixed>, 3: int}>
     */
    public static function nominalErrorCases(): array
    {
        return array(
            '2026 header mismatch' => array(
                Schemas::V2026_07_28,
                HeaderMismatchError::class,
                array(
                    'jsonrpc' => '2.0',
                    'error'   => array('code' => -32020, 'message' => 'Header mismatch'),
                ),
                -32020,
            ),
            '2026 missing capability' => array(
                Schemas::V2026_07_28,
                MissingRequiredClientCapabilityError::class,
                array(
                    'jsonrpc' => '2.0',
                    'error'   => array(
                        'code'    => -32021,
                        'message' => 'Missing capability',
                        'data'    => array('requiredCapabilities' => array()),
                    ),
                ),
                -32021,
            ),
            '2026 unsupported protocol' => array(
                Schemas::V2026_07_28,
                UnsupportedProtocolVersionError::class,
                array(
                    'jsonrpc' => '2.0',
                    'error'   => array(
                        'code'    => -32022,
                        'message' => 'Unsupported protocol version',
                        'data'    => array(
                            'requested' => '2027-01-01',
                            'supported' => array(Schemas::V2025_11_25, Schemas::V2026_07_28),
                        ),
                    ),
                ),
                -32022,
            ),
            '2025 URL elicitation required' => array(
                Schemas::V2025_11_25,
                URLElicitationRequiredError::class,
                array(
                    'jsonrpc' => '2.0',
                    'error'   => array(
                        'code'    => -32042,
                        'message' => 'URL elicitation required',
                        'data'    => array('elicitations' => array()),
                    ),
                ),
                -32042,
            ),
        );
    }
}

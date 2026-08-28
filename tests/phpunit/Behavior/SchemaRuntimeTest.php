<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Contract\ClientNotification as ClientNotificationContract;
use WP\McpSchema\Contract\ContentBlock;
use WP\McpSchema\Exception\UnavailableTypeException;
use WP\McpSchema\Exception\UnsupportedRevisionException;
use WP\McpSchema\Exception\UnknownFieldException;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Record\ClientNotification;
use WP\McpSchema\Record\ClientResult;
use WP\McpSchema\Record\DiscoverRequest;
use WP\McpSchema\Record\EmptyResult;
use WP\McpSchema\Record\InitializedNotification;
use WP\McpSchema\Record\PingRequest;
use WP\McpSchema\Record\TextContent;
use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schema;
use WP\McpSchema\Schemas;

final class SchemaRuntimeTest extends TestCase
{
    public function test_exact_revision_selection_is_cached_and_unknown_versions_fail(): void
    {
        $schemas = Schemas::create();
        $first   = $schemas->forVersion(Schemas::V2025_11_25);

        self::assertTrue((new \ReflectionMethod(Schema::class, '__construct'))->isPrivate());
        self::assertSame($first, $schemas->forVersion(Schemas::V2025_11_25));
        self::assertSame(Schemas::V2025_11_25, $first->version());

        $this->expectException(UnsupportedRevisionException::class);
        $schemas->forVersion('2026-08-01');
    }

    public function test_revision_only_records_and_kind_specific_roots_are_exact(): void
    {
        $schemas = Schemas::create();
        $v2025   = $schemas->forVersion(Schemas::V2025_11_25);
        $v2026   = $schemas->forVersion(Schemas::V2026_07_28);

        self::assertInstanceOf(PingRequest::class, $v2025->fromArray(PingRequest::class, array(
            'id'      => 1,
            'jsonrpc' => '2.0',
            'method'  => 'ping',
        )));
        self::assertInstanceOf(DiscoverRequest::class, $v2026->fromArray(DiscoverRequest::class, array(
            'id'      => 1,
            'jsonrpc' => '2.0',
            'method'  => 'server/discover',
            'params'  => array(
                '_meta' => array(
                    'io.modelcontextprotocol/protocolVersion'    => Schemas::V2026_07_28,
                    'io.modelcontextprotocol/clientCapabilities' => array(),
                ),
            ),
        )));

        $notification = $v2025->fromArray(ClientNotificationContract::class, array(
            'jsonrpc' => '2.0',
            'method'  => 'notifications/initialized',
        ));
        self::assertInstanceOf(InitializedNotification::class, $notification);

        self::assertInstanceOf(ClientNotification::class, $v2026->fromArray(ClientNotification::class, array(
            'jsonrpc' => '2.0',
            'method'  => 'notifications/cancelled',
            'params'  => array('requestId' => 1),
        )));
        self::assertInstanceOf(ClientResult::class, $v2026->fromArray(ClientResult::class, array(
            'resultType' => 'complete',
        )));

        try {
            $v2026->fromArray(PingRequest::class, array());
            self::fail('2026 unexpectedly accepted PingRequest.');
        } catch (UnavailableTypeException $exception) {
            self::assertStringContainsString(Schemas::V2026_07_28, $exception->getMessage());
        }

        try {
            $v2025->fromArray(ClientNotification::class, array());
            self::fail('2025 unexpectedly accepted the 2026 ClientNotification record root.');
        } catch (UnavailableTypeException $exception) {
            self::assertStringContainsString(Schemas::V2025_11_25, $exception->getMessage());
        }

        $this->expectException(UnavailableTypeException::class);
        $v2026->fromArray(ClientNotificationContract::class, array());
    }

    public function test_shared_records_keep_removed_fields_typed_only_when_declared(): void
    {
        $schemas = Schemas::create();
        $v2025   = $schemas->forVersion(Schemas::V2025_11_25);
        $v2026   = $schemas->forVersion(Schemas::V2026_07_28);

        $tool2025 = $v2025->fromArray(Tool::class, array(
            'name'        => 'weather',
            'inputSchema' => array('type' => 'object'),
            'execution'   => array('taskSupport' => 'optional'),
        ));
        self::assertNotNull($tool2025->getExecution());

        $tool2026 = $v2026->fromArray(Tool::class, array(
            'name'        => 'weather',
            'inputSchema' => array('type' => 'object'),
            'execution'   => array('vendorData' => true),
        ));
        self::assertNull($tool2026->getExecution());
        self::assertTrue($tool2026->has('execution'));
        self::assertInstanceOf(\stdClass::class, $tool2026->get('execution'));
        self::assertTrue($tool2026->jsonSerialize()->execution->vendorData);
    }

    public function test_presence_object_identity_and_defensive_copying_are_preserved(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $tool   = $schema->fromArray(Tool::class, array(
            'name'        => 'identity',
            'inputSchema' => array(
                'type'       => 'object',
                'properties' => array(),
            ),
            'extensionNull' => null,
        ));

        self::assertFalse($tool->has('description'));
        self::assertNull($tool->getDescription());
        self::assertTrue($tool->has('extensionNull'));
        self::assertNull($tool->get('extensionNull'));
        self::assertInstanceOf(\stdClass::class, $tool->getInputSchema());
        self::assertInstanceOf(\stdClass::class, $tool->getInputSchema()->properties);

        $first = $tool->getInputSchema();
        $first->changed = true;
        self::assertObjectNotHasProperty('changed', $tool->getInputSchema());

        $serialized = $tool->jsonSerialize();
        $serialized->inputSchema->changed = true;
        self::assertObjectNotHasProperty('changed', $tool->jsonSerialize()->inputSchema);

        $empty = $schema->fromArray(EmptyResult::class, array());
        self::assertSame('{}', json_encode($empty));

        try {
            $tool->get('unknown');
            self::fail('An unknown field was readable.');
        } catch (UnknownFieldException $exception) {
            self::assertStringContainsString('unknown', $exception->getMessage());
        }
    }

    public function test_union_roots_hydrate_concrete_members_in_canonical_order(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $block  = $schema->fromArray(ContentBlock::class, array(
            'type' => 'text',
            'text' => 'Hello',
        ));

        self::assertInstanceOf(TextContent::class, $block);
        self::assertSame('Hello', $block->getText());
    }

    public function test_cross_revision_records_are_serialized_and_revalidated(): void
    {
        $schemas = Schemas::create();
        $v2025   = $schemas->forVersion(Schemas::V2025_11_25);
        $v2026   = $schemas->forVersion(Schemas::V2026_07_28);
        $old     = $v2025->fromArray(Tool::class, array(
            'name'        => 'cross',
            'inputSchema' => array('type' => 'object'),
            'execution'   => array('taskSupport' => 'optional'),
        ));
        $new = $v2026->fromValue(Tool::class, $old);

        self::assertInstanceOf(Tool::class, $new);
        self::assertNull($new->getExecution());
        self::assertTrue($new->has('execution'));
    }

    public function test_validation_errors_include_json_pointer_paths(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);

        try {
            $schema->fromArray(Tool::class, array('inputSchema' => array()));
            self::fail('Missing required field was accepted.');
        } catch (ValidationException $exception) {
            self::assertSame('/name', $exception->getPointer());
        }

        $this->expectException(ValidationException::class);
        $schema->fromArray(PingRequest::class, array(
            'id'      => 1,
            'jsonrpc' => '2.0',
            'method'  => 'not-ping',
        ));
    }
}

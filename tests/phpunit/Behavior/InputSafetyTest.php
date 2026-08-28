<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Exception\InvalidJsonException;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Record\CompleteResult;
use WP\McpSchema\Record\JSONObject;
use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

final class InputSafetyTest extends TestCase
{
    public function test_raw_json_preserves_object_identity_and_numeric_string_keys(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $tool   = $schema->fromJson(
            Tool::class,
            '{"name":"json","inputSchema":{"type":"object","properties":{"0":{"type":"string"}}}}'
        );

        self::assertInstanceOf(\stdClass::class, $tool->getInputSchema()->properties);
        self::assertTrue(property_exists($tool->getInputSchema()->properties, '0'));
    }

    public function test_raw_json_rejects_invalid_and_overflowing_integers(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);

        foreach (array(
            '{',
            '{"name":"bad","inputSchema":{},"value":9223372036854775808}',
            '{"name":"bad","inputSchema":{},"value":-9223372036854775809}',
        ) as $json) {
            try {
                $schema->fromJson(Tool::class, $json);
                self::fail(sprintf('Invalid JSON was accepted: %s', $json));
            } catch (InvalidJsonException $exception) {
                self::assertNotSame('', $exception->getMessage());
            }
        }
    }

    public function test_programmatic_values_reject_non_finite_unsupported_and_cyclic_values(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);

        foreach (array(INF, NAN, static function (): void {}) as $value) {
            try {
                $schema->fromArray(Tool::class, array(
                    'name'        => 'unsafe',
                    'inputSchema' => array('type' => 'object', 'value' => $value),
                ));
                self::fail('Unsafe value was accepted.');
            } catch (ValidationException $exception) {
                self::assertNotSame('', $exception->getMessage());
            }
        }

        $object       = new \stdClass();
        $object->type = 'object';
        $object->self = $object;
        try {
            $schema->fromValue(Tool::class, (object) array(
                'name'        => 'cycle',
                'inputSchema' => $object,
            ));
            self::fail('Cyclic object was accepted.');
        } catch (ValidationException $exception) {
            self::assertStringContainsString('Cyclic', $exception->getMessage());
        }

        $array         = array('type' => 'object');
        $array['self'] =& $array;
        $this->expectException(ValidationException::class);
        $schema->fromArray(Tool::class, array(
            'name'        => 'cycle',
            'inputSchema' => $array,
        ));
    }

    public function test_programmatic_values_reject_malformed_utf8_resources_and_excessive_depth(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $stream = fopen('php://memory', 'r');
        self::assertIsResource($stream);

        try {
            foreach (array("\xB1\x31", $stream) as $value) {
                try {
                    $schema->fromArray(Tool::class, array(
                        'name'        => 'unsafe',
                        'inputSchema' => array('type' => 'object', 'value' => $value),
                    ));
                    self::fail('Unsafe value was accepted.');
                } catch (ValidationException $exception) {
                    self::assertNotSame('', $exception->getMessage());
                }
            }
        } finally {
            fclose($stream);
        }

        $nested = 'leaf';
        for ($depth = 0; $depth <= 513; ++$depth) {
            $nested = array('next' => $nested);
        }

        $this->expectException(ValidationException::class);
        $schema->fromArray(Tool::class, array(
            'name'        => 'too-deep',
            'inputSchema' => array('type' => 'object', 'nested' => $nested),
        ));
    }

    public function test_raw_json_rejects_non_finite_numeric_results(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);

        $this->expectException(ValidationException::class);
        $schema->fromJson(
            Tool::class,
            '{"name":"unsafe","inputSchema":{"type":"object","value":1e309}}'
        );
    }

    public function test_programmatic_values_reject_malformed_utf8_object_keys(): void
    {
        $schema      = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $inputSchema = array('type' => 'object');
        $inputSchema["\xB1"] = true;

        try {
            $schema->fromArray(Tool::class, array(
                'name'        => 'bad-array-key',
                'inputSchema' => $inputSchema,
            ));
            self::fail('A malformed UTF-8 array key was accepted.');
        } catch (ValidationException $exception) {
            self::assertStringContainsString('key', $exception->getMessage());
        }

        $inputSchema       = new \stdClass();
        $inputSchema->type = 'object';
        $badKey            = "\xB1";
        $inputSchema->{$badKey} = true;

        $this->expectException(ValidationException::class);
        $schema->fromValue(Tool::class, (object) array(
            'name'        => 'bad-object-key',
            'inputSchema' => $inputSchema,
        ));
    }

    public function test_recursive_json_values_use_the_full_value_depth_budget(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $nested = 'leaf';
        for ($depth = 0; $depth < 512; ++$depth) {
            $nested = array('x' => $nested);
        }

        self::assertInstanceOf(JSONObject::class, $schema->fromArray(JSONObject::class, $nested));

        $nested = array('x' => $nested);
        $this->expectException(ValidationException::class);
        $schema->fromArray(JSONObject::class, $nested);
    }

    public function test_integer_schema_accepts_integral_float_without_coercion(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $result = $schema->fromArray(CompleteResult::class, array(
            'completion' => array(
                'values' => array('one'),
                'total'  => 1.0,
            ),
            'resultType' => 'complete',
        ));

        self::assertSame(1.0, $result->getCompletion()->total);

        $this->expectException(ValidationException::class);
        $schema->fromArray(CompleteResult::class, array(
            'completion' => array(
                'values' => array('one'),
                'total'  => 1.5,
            ),
            'resultType' => 'complete',
        ));
    }

    public function test_max_items_is_enforced(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);

        $this->expectException(ValidationException::class);
        $schema->fromArray(CompleteResult::class, array(
            'completion' => array(
                'values' => array_fill(0, 101, 'value'),
            ),
            'resultType' => 'complete',
        ));
    }
}

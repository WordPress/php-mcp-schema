<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Exception\InvalidJsonException;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Internal\JsonDecoder;
use WP\McpSchema\Record\CompleteResult;
use WP\McpSchema\Record\JSONObject;
use WP\McpSchema\Record\NumberSchema;
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

    public function test_raw_json_bounds_overflow_diagnostics(): void
    {
        $token = str_repeat('9', 10000);

        try {
            (new JsonDecoder())->decode('[' . $token . ']');
            self::fail('An overflowing JSON integer was accepted.');
        } catch (InvalidJsonException $exception) {
            self::assertLessThan(256, strlen($exception->getMessage()));
            self::assertStringContainsString('...', $exception->getMessage());
        }
    }

    public function test_raw_json_numeric_scan_is_linear_for_dense_payloads(): void
    {
        $json    = '[' . implode(',', array_fill(0, 250000, '12345')) . ']';
        $started = microtime(true);
        $decoded = (new JsonDecoder())->decode($json);
        $elapsed = microtime(true) - $started;

        self::assertCount(250000, $decoded);
        self::assertLessThan(
            1.5,
            $elapsed,
            sprintf('Dense 1.5 MB numeric JSON took %.3f seconds to decode.', $elapsed)
        );
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

    public function test_object_graph_normalization_is_deprecation_free_and_preserves_cycle_detection(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $shared = (object) array('value' => 'shared');
        $graph  = (object) array(
            'left'  => $shared,
            'right' => $shared,
        );

        set_error_handler(
            static function (int $severity, string $message, string $file, int $line): bool {
                if (($severity & (E_DEPRECATED | E_USER_DEPRECATED)) !== 0) {
                    throw new \ErrorException($message, 0, $severity, $file, $line);
                }

                return false;
            }
        );

        try {
            $normalized = $schema->fromValue(JSONObject::class, $graph);
            $left       = $normalized->get('left');
            $right      = $normalized->get('right');
            self::assertInstanceOf(JSONObject::class, $left);
            self::assertInstanceOf(JSONObject::class, $right);
            self::assertNotSame($left, $right);
            self::assertSame('shared', $left->get('value'));
            self::assertSame('shared', $right->get('value'));

            $cycle       = new \stdClass();
            $cycle->self = $cycle;

            try {
                $schema->fromValue(JSONObject::class, $cycle);
                self::fail('Cyclic object was accepted.');
            } catch (ValidationException $exception) {
                self::assertStringContainsString('Cyclic', $exception->getMessage());
            }
        } finally {
            restore_error_handler();
        }
    }

    public function test_programmatic_array_references_are_copied_without_mutating_the_caller(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $shared = array('value' => 'original');
        $inputSchema = array('type' => 'object');
        $inputSchema['left'] =& $shared;
        $inputSchema['right'] =& $shared;

        $tool = $schema->fromArray(Tool::class, array(
            'name'        => 'references',
            'inputSchema' => $inputSchema,
        ));

        self::assertSame(array('value' => 'original'), $shared);
        self::assertInstanceOf(\stdClass::class, $tool->getInputSchema()->left);
        self::assertInstanceOf(\stdClass::class, $tool->getInputSchema()->right);
        self::assertNotSame($tool->getInputSchema()->left, $tool->getInputSchema()->right);
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

        $this->expectException(InvalidJsonException::class);
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

    public function test_programmatic_values_reject_nul_prefixed_object_keys(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2025_11_25);

        foreach (array('array', 'object') as $kind) {
            $badKey      = "\0unsafe";
            $inputSchema = array('type' => 'object', $badKey => true);
            if ($kind === 'object') {
                $inputSchema = (object) $inputSchema;
            }

            try {
                if ($kind === 'array') {
                    $schema->fromArray(Tool::class, array(
                        'name'        => 'bad-nul-key',
                        'inputSchema' => $inputSchema,
                    ));
                } else {
                    $schema->fromValue(Tool::class, (object) array(
                        'name'        => 'bad-nul-key',
                        'inputSchema' => $inputSchema,
                    ));
                }
                self::fail(sprintf('A NUL-prefixed %s key was accepted.', $kind));
            } catch (ValidationException $exception) {
                self::assertStringContainsString('NUL', $exception->getMessage());
                self::assertStringContainsString('\\000unsafe', $exception->getMessage());
                self::assertStringNotContainsString("\0", $exception->getMessage());
            }
        }
    }

    public function test_programmatic_diagnostics_escape_and_bound_object_keys(): void
    {
        $schema      = Schemas::create()->forVersion(Schemas::V2025_11_25);
        $inputSchema = array('type' => 'object');
        $key         = "line\n" . str_repeat('x', 10000);
        $inputSchema[$key] = INF;

        try {
            $schema->fromArray(Tool::class, array(
                'name'        => 'bounded-diagnostic',
                'inputSchema' => $inputSchema,
            ));
            self::fail('A non-finite value was accepted.');
        } catch (ValidationException $exception) {
            self::assertLessThan(512, strlen($exception->getMessage()));
            self::assertStringContainsString('line\\n', $exception->getMessage());
            self::assertStringContainsString('...', $exception->getMessage());
            self::assertStringNotContainsString("\n", $exception->getMessage());
        }
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

    public function test_raw_json_uses_the_same_value_depth_budget(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $json   = str_repeat('{"x":', 512) . '"leaf"' . str_repeat('}', 512);

        self::assertInstanceOf(JSONObject::class, $schema->fromJson(JSONObject::class, $json));

        $this->expectException(InvalidJsonException::class);
        $schema->fromJson(JSONObject::class, '{"x":' . $json . '}');
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

    public function test_integer_schema_rejects_out_of_native_range_integral_floats_only(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);

        $numberSchema = $schema->fromArray(NumberSchema::class, array(
            'type'    => 'number',
            'default' => 1.0e20,
        ));
        self::assertSame(1.0e20, $numberSchema->getDefault());

        $this->expectException(ValidationException::class);
        $schema->fromArray(CompleteResult::class, array(
            'completion' => array(
                'values' => array('one'),
                'total'  => -((float) PHP_INT_MIN),
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

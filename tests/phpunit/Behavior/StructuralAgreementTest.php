<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Internal\SchemaInterpreter;
use WP\McpSchema\Record;

final class FixtureRecord extends Record
{
}

final class StructuralAgreementTest extends TestCase
{
    /**
     * @dataProvider structuralCases
     * @param array<string, mixed> $case
     */
    public function test_php_interpreter_matches_the_ajv_fixture_oracle(array $case): void
    {
        $schema      = $case['schema'];
        $definitions = $schema['$defs'] ?? array();
        unset($schema['$defs']);
        $definitions['Fixture'] = array(
            'type'       => 'object',
            'properties' => array('value' => $schema),
            'required'   => array('value'),
        );
        $records = array(
            FixtureRecord::class => array(
                'definition' => 'Fixture',
                'versions'   => array('test'),
            ),
        );
        $interpreter = new SchemaInterpreter($definitions, 'test', $records);

        foreach ($case['valid'] as $value) {
            $record = $interpreter->hydrate('Fixture', FixtureRecord::class, array('value' => $value), true);
            self::assertInstanceOf(FixtureRecord::class, $record, $case['id']);
        }
        foreach ($case['invalid'] as $value) {
            try {
                $interpreter->hydrate('Fixture', FixtureRecord::class, array('value' => $value), true);
                self::fail(sprintf('%s accepted invalid value %s.', $case['id'], json_encode($value)));
            } catch (ValidationException $exception) {
                self::assertStringStartsWith('/value', $exception->getPointer(), $case['id']);
            }
        }
    }

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public function structuralCases(): array
    {
        $fixture = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2) . '/fixtures/structural/schema-runtime.cases.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $cases = array();
        foreach ($fixture['cases'] as $case) {
            $cases[$case['id']] = array($case);
        }

        return $cases;
    }
}

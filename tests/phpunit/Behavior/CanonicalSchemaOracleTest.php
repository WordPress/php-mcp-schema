<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Schemas;

final class CanonicalSchemaOracleTest extends TestCase
{
    /**
     * @dataProvider canonicalCases
     * @param object $case
     */
    public function test_php_runtime_matches_the_canonical_ajv_corpus(object $case): void
    {
        $schema = Schemas::create()->forVersion($case->revision);

        foreach ($case->valid as $value) {
            $json   = (string) json_encode($value, JSON_THROW_ON_ERROR);
            $record = $schema->fromJson($case->rootClass, $json);
            self::assertSame($json, json_encode($record), $case->id);
        }
        foreach ($case->invalid as $value) {
            $json = (string) json_encode($value, JSON_THROW_ON_ERROR);
            try {
                $schema->fromJson($case->rootClass, $json);
                self::fail(sprintf('%s accepted invalid value %s.', $case->id, $json));
            } catch (ValidationException $exception) {
                self::assertNotSame('', $exception->getMessage(), $case->id);
            }
        }
    }

    /**
     * @return array<string, array{0: object}>
     */
    public function canonicalCases(): array
    {
        $fixture = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2) . '/fixtures/canonical/schema-runtime.cases.json'),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
        $cases = array();
        foreach ($fixture->cases as $case) {
            $cases[$case->id] = array($case);
        }

        return $cases;
    }
}

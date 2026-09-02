<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Exception\ValidationException;
use WP\McpSchema\Record\CallToolRequestParams;
use WP\McpSchema\Record\ClientCapabilities;
use WP\McpSchema\Record\ElicitResult;
use WP\McpSchema\Record\InputResponses;
use WP\McpSchema\Record\CreateMessageRequestParams;
use WP\McpSchema\Schemas;

final class ElicitationNumberTest extends TestCase
{
    /** @dataProvider revisions */
    public function test_fractional_elicitation_answers_preserve_values_in_each_revision(string $revision): void
    {
        $schema = Schemas::create()->forVersion($revision);
        $json   = '{"action":"accept","content":{"quantity":1.5,"count":2}}';

        $fromArray = $schema->fromArray(ElicitResult::class, array(
            'action'  => 'accept',
            'content' => array('quantity' => 1.5, 'count' => 2),
        ));
        self::assertSame(1.5, $fromArray->getContent()->quantity);
        self::assertSame(2, $fromArray->getContent()->count);
        self::assertSame($json, json_encode($schema->fromJson(ElicitResult::class, $json)));
    }

    public static function revisions(): array
    {
        return array(
            Schemas::V2025_11_25 => array(Schemas::V2025_11_25),
            Schemas::V2026_07_28 => array(Schemas::V2026_07_28),
        );
    }

    public function test_fractional_answers_preserve_values_through_every_mrtr_boundary(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $answer = array('action' => 'accept', 'content' => array('quantity' => 1.5, 'count' => 2));
        $direct = $schema->fromArray(ElicitResult::class, $answer);
        self::assertSame(1.5, $direct->getContent()->quantity);
        self::assertSame(2, $direct->getContent()->count);
        $json = '{"action":"accept","content":{"quantity":1.5,"count":2}}';
        self::assertSame($json, json_encode($schema->fromJson(ElicitResult::class, $json)));
        $map = $schema->fromArray(InputResponses::class, array('q' => $answer));
        self::assertSame('{"q":' . $json . '}', json_encode($map));
        $params = array(
            'name' => 'quantity',
            '_meta' => array(
                'io.modelcontextprotocol/protocolVersion' => Schemas::V2026_07_28,
                'io.modelcontextprotocol/clientCapabilities' => (object) array(),
            ),
            'inputResponses' => array('q' => $answer),
        );
        $request = $schema->fromArray(CallToolRequestParams::class, $params);
        self::assertSame('{"q":' . $json . '}', json_encode($request->getInputResponses()));
        self::assertSame(json_encode($request), json_encode($schema->fromJson(CallToolRequestParams::class, (string) json_encode($request))));
    }

    /** @dataProvider invalidAnswers */
    public function test_non_primitive_answers_still_reject($value): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $this->expectException(ValidationException::class);
        $schema->fromArray(InputResponses::class, array('q' => array('action' => 'accept', 'content' => array('quantity' => $value))));
    }

    public static function invalidAnswers(): array
    {
        return array('null' => array(null), 'object' => array((object) array('n' => 1.5)), 'numeric-list' => array(array(1.5)));
    }

    public function test_unrelated_integer_fields_remain_integer_only(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $this->expectException(ValidationException::class);
        $schema->fromArray(CreateMessageRequestParams::class, array('messages' => array(), 'maxTokens' => 1.5));
    }

    public function test_2026_json_values_accept_fractional_and_null_members(): void
    {
        $schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
        $capabilities = $schema->fromArray(ClientCapabilities::class, array(
            'experimental' => array(
                'vendor' => array(
                    'fractional' => 1.5,
                    'nullable'   => null,
                ),
            ),
        ));

        self::assertSame(1.5, $capabilities->getExperimental()->vendor->get('fractional'));
        self::assertNull($capabilities->getExperimental()->vendor->get('nullable'));
    }
}

<?php

declare(strict_types=1);

use WP\McpSchema\V20251125\Common\Content\DTO\TextContent as LegacyTextContent;
use WP\McpSchema\V20251125\Server\Tools\DTO\CallToolResult as LegacyCallToolResult;
use WP\McpSchema\V20260728\Common\Content\DTO\TextContent as ModernTextContent;
use WP\McpSchema\V20260728\Common\JsonRpc\DTO\RequestMetaObject;
use WP\McpSchema\V20260728\Common\Protocol\DTO\InputRequests;
use WP\McpSchema\V20260728\Common\Protocol\DTO\InputResponses;
use WP\McpSchema\V20260728\Common\Protocol\DTO\ParseError;
use WP\McpSchema\V20260728\Client\Elicitation\DTO\ElicitRequest;
use WP\McpSchema\V20260728\Client\Elicitation\DTO\ElicitResult;
use WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsRequest;
use WP\McpSchema\V20260728\Client\Roots\DTO\ListRootsResult;
use WP\McpSchema\V20260728\Server\Tools\DTO\CallToolResult as ModernCallToolResult;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @return array<string, mixed> */
function fixture(string $relativePath): array
{
    $contents = file_get_contents(__DIR__ . '/fixtures/' . $relativePath);
    if ($contents === false) {
        throw new RuntimeException('Unable to read fixture: ' . $relativePath);
    }

    /** @var array<string, mixed> $decoded */
    $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    return $decoded;
}

/** @param mixed $value @return mixed */
function normalize_wire_value($value)
{
    if (!is_array($value)) {
        return $value;
    }

    $isList = [] === $value || array_keys($value) === range(0, count($value) - 1);
    if (!$isList) {
        ksort($value);
    }

    return array_map('normalize_wire_value', $value);
}

/** @param mixed $expected @param mixed $actual */
function assert_wire_equals($expected, $actual, string $message): void
{
    if (normalize_wire_value($expected) !== normalize_wire_value($actual)) {
        throw new RuntimeException(
            $message . "\nExpected: " . var_export($expected, true) .
            "\nActual: " . var_export($actual, true)
        );
    }
}

$legacyWire = fixture('V20251125/tools-call-result.json');
$modernWire = fixture('V20260728/tools-call-result.json');
$requestMetaWire = fixture('V20260728/request-meta.json');
$inputRequestsWire = fixture('V20260728/input-requests.json');
$inputResponsesWire = fixture('V20260728/input-responses.json');

$legacyResult = LegacyCallToolResult::fromArray($legacyWire);
$modernResult = ModernCallToolResult::fromArray($modernWire);
$requestMeta = RequestMetaObject::fromArray($requestMetaWire);
$parseError = ParseError::fromArray([
    'code' => -32700,
    'message' => 'Invalid JSON',
]);
$inputRequests = InputRequests::fromArray($inputRequestsWire);
$inputResponses = InputResponses::fromArray($inputResponsesWire);

assert_wire_equals($legacyWire, $legacyResult->toArray(), 'Legacy tools/call did not round trip exactly.');
assert_wire_equals($modernWire, $modernResult->toArray(), 'Modern tools/call did not round trip exactly.');
assert_wire_equals($requestMetaWire, $requestMeta->toArray(), 'Modern request metadata did not preserve exact wire keys.');
assert_wire_equals(-32700, $parseError->toArray()['code'] ?? null, 'Numeric typeof constant became a string.');
assert_wire_equals($inputRequestsWire, $inputRequests->toArray(), 'Dynamic input request IDs did not round trip.');
assert_wire_equals($inputResponsesWire, $inputResponses->toArray(), 'Dynamic input response IDs did not round trip.');
assert_wire_equals($inputRequestsWire, json_decode($inputRequests->toJson(), true, 512, JSON_THROW_ON_ERROR), 'Input requests JSON did not round trip.');
assert_wire_equals($inputResponsesWire, json_decode($inputResponses->toJson(), true, 512, JSON_THROW_ON_ERROR), 'Input responses JSON did not round trip.');

$inputRequestValues = $inputRequests->getAdditionalProperties();
$inputResponseValues = $inputResponses->getAdditionalProperties();

if (!($inputRequestValues['github_login'] ?? null) instanceof ElicitRequest) {
    throw new RuntimeException('The first dynamic input request was not hydrated to ElicitRequest.');
}
if (!($inputRequestValues['workspace_roots'] ?? null) instanceof ListRootsRequest) {
    throw new RuntimeException('The second dynamic input request was not hydrated to ListRootsRequest.');
}
if (!($inputResponseValues['github_login'] ?? null) instanceof ElicitResult) {
    throw new RuntimeException('The first dynamic input response was not hydrated to ElicitResult.');
}
if (!($inputResponseValues['workspace_roots'] ?? null) instanceof ListRootsResult) {
    throw new RuntimeException('The second dynamic input response was not hydrated to ListRootsResult.');
}

$legacyContent = $legacyResult->getContent()[0] ?? null;
$modernContent = $modernResult->getContent()[0] ?? null;

if (!$legacyContent instanceof LegacyTextContent) {
    throw new RuntimeException('Legacy content was not hydrated into the legacy tree.');
}
if (!$modernContent instanceof ModernTextContent) {
    throw new RuntimeException('Modern content was not hydrated into the modern tree.');
}
if ($legacyContent instanceof ModernTextContent || $modernContent instanceof LegacyTextContent) {
    throw new RuntimeException('Revision-specific content types crossed namespace trees.');
}

echo "Dual revision wire and co-loading checks passed.\n";

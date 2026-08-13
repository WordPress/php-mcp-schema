<?php

declare(strict_types=1);

use WP\McpSchema\Revision;
use WP\McpSchema\Schemas;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Generated\V20251125Constants;
use WP\McpSchema\Generated\V20260728Constants;
use WP\McpSchema\Runtime\ValidationException;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @param mixed $expected @param mixed $actual */
function assertSameValue($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            $message . "\nExpected: " . var_export($expected, true) .
            "\nActual: " . var_export($actual, true)
        );
    }
}

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

/** @param callable(): void $operation */
function assertValidationFails(callable $operation, string $message): void
{
    try {
        $operation();
    } catch (ValidationException $exception) {
        return;
    }
    throw new RuntimeException($message);
}

/** @param callable(): void $operation */
function assertLogicFails(callable $operation, string $message): void
{
    try {
        $operation();
    } catch (LogicException $exception) {
        return;
    }
    throw new RuntimeException($message);
}

$wire = [
    'type' => 'text',
    'text' => 'Hello from a generic record',
];

$schema = Schemas::revision(Revision::V20251125);
$record = $schema->type('TextContent')->fromArray($wire);

assertSameValue(
    Revision::V20251125,
    V20251125Constants::LATEST_PROTOCOL_VERSION,
    'The legacy generated protocol revision constant is incorrect.'
);
assertSameValue(
    Revision::V20260728,
    V20260728Constants::LATEST_PROTOCOL_VERSION,
    'The modern generated protocol revision constant is incorrect.'
);
assertSameValue(-32020, V20260728Constants::HEADER_MISMATCH, 'HEADER_MISMATCH was not generated.');
assertSameValue(
    -32022,
    V20260728Constants::UNSUPPORTED_PROTOCOL_VERSION,
    'UNSUPPORTED_PROTOCOL_VERSION was not generated.'
);

assertSameValue(Revision::V20251125, $record->revision(), 'The record lost its revision identity.');
assertSameValue('TextContent', $record->typeName(), 'The record lost its logical type identity.');
assertSameValue('Hello from a generic record', $record->get('text'), 'A typed field was not readable.');
assertSameValue(true, $record->has('type'), 'A present field was reported as absent.');
assertSameValue(false, $record->has('annotations'), 'An omitted field was reported as present.');
assertSameValue($wire, $record->toArray(), 'The record did not round trip to the same wire array.');
assertSameValue($wire, json_decode(json_encode($record, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR), 'The record did not round trip through JSON.');

$legacyWire = fixture('V20251125/tools-call-result.json');
$modernWire = fixture('V20260728/tools-call-result.json');
$legacyResult = Schemas::v20251125()->callToolResult()->fromArray($legacyWire);
$modernResult = Schemas::v20260728()->callToolResult()->fromArray($modernWire);

assertSameValue($legacyWire, $legacyResult->toArray(), 'Legacy tools/call did not round trip exactly.');
assertSameValue($modernWire, $modernResult->toArray(), 'Modern tools/call did not round trip exactly.');

assertValidationFails(
    static function () use ($legacyWire): void {
        Schemas::v20251125()->callToolResult()->fromArray([
            'content' => $legacyWire['content'],
            'structuredContent' => ['one', 'two'],
        ]);
    },
    'A non-empty list was accepted where legacy structuredContent requires an object map.'
);

$legacyContent = $legacyResult->get('content')[0] ?? null;
$modernContent = $modernResult->get('content')[0] ?? null;
if (!$legacyContent instanceof Record || !$modernContent instanceof Record) {
    throw new RuntimeException('Nested content blocks were not hydrated as generic records.');
}

assertSameValue('TextContent', $legacyContent->typeName(), 'The legacy union chose the wrong content type.');
assertSameValue('TextContent', $modernContent->typeName(), 'The modern union chose the wrong content type.');
assertSameValue(Revision::V20251125, $legacyContent->revision(), 'Nested legacy revision identity was lost.');
assertSameValue(Revision::V20260728, $modernContent->revision(), 'Nested modern revision identity was lost.');

assertValidationFails(
    static function () use ($legacyWire): void {
        Schemas::v20260728()->callToolResult()->fromArray($legacyWire);
    },
    'The modern descriptor accepted the legacy CallToolResult contract.'
);
assertValidationFails(
    static function () use ($modernWire): void {
        Schemas::v20251125()->callToolResult()->fromArray($modernWire);
    },
    'The legacy descriptor accepted the modern CallToolResult contract.'
);

$inputRequestsWire = fixture('V20260728/input-requests.json');
$inputResponsesWire = fixture('V20260728/input-responses.json');
$requestMetaWire = fixture('V20260728/request-meta.json');
$modernSchema = Schemas::v20260728();
$inputRequests = $modernSchema->inputRequests()->fromArray($inputRequestsWire);
$inputResponses = $modernSchema->inputResponses()->fromArray($inputResponsesWire);
$requestMeta = $modernSchema->requestMetaObject()->fromArray($requestMetaWire);
$parseError = $modernSchema->parseError()->fromArray([
    'code' => -32700,
    'message' => 'Invalid JSON',
]);

assertSameValue($inputRequestsWire, $inputRequests->toArray(), 'Dynamic input request IDs did not round trip.');
assertSameValue($inputResponsesWire, $inputResponses->toArray(), 'Dynamic input response IDs did not round trip.');
assertSameValue($requestMetaWire, $requestMeta->toArray(), 'Open request metadata lost extension keys.');
assertSameValue(-32700, $parseError->get('code'), 'A numeric typeof constant was not validated as a number.');

$githubRequest = $inputRequests->get('github_login');
$rootsRequest = $inputRequests->get('workspace_roots');
$githubResponse = $inputResponses->get('github_login');
$rootsResponse = $inputResponses->get('workspace_roots');

if (!$githubRequest instanceof Record || !$rootsRequest instanceof Record ||
    !$githubResponse instanceof Record || !$rootsResponse instanceof Record) {
    throw new RuntimeException('Dynamic map values were not hydrated as records.');
}

assertSameValue('ElicitRequest', $githubRequest->typeName(), 'InputRequest chose the wrong elicitation union member.');
assertSameValue('ListRootsRequest', $rootsRequest->typeName(), 'InputRequest chose the wrong roots union member.');
assertSameValue('ElicitResult', $githubResponse->typeName(), 'InputResponse chose the wrong elicitation union member.');
assertSameValue('ListRootsResult', $rootsResponse->typeName(), 'InputResponse chose the wrong roots union member.');

$modernType = $modernSchema->callToolResult();
$explicitNull = $modernType->fromArray([
    'resultType' => 'complete',
    'content' => [],
    'structuredContent' => null,
]);
assertSameValue(true, $explicitNull->has('structuredContent'), 'An explicit null was treated as omission.');
assertSameValue(null, $explicitNull->get('structuredContent'), 'An explicit null changed value.');

assertValidationFails(
    static function () use ($schema): void {
        $schema->type('TextContent')->fromArray([
            'type' => 'text',
            'text' => 'Null is not omission',
            'annotations' => null,
        ]);
    },
    'An optional but non-nullable field accepted explicit null.'
);

$jsonObject = $modernType->fromJson('{"resultType":"complete","content":[],"structuredContent":{}}');
$jsonList = $modernType->fromJson('{"resultType":"complete","content":[],"structuredContent":[]}');
$objectWire = json_decode(json_encode($jsonObject, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
$listWire = json_decode(json_encode($jsonList, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

if (!$objectWire instanceof stdClass || !$objectWire->structuredContent instanceof stdClass) {
    throw new RuntimeException('An empty JSON object lost its object identity.');
}
if (!$listWire instanceof stdClass || !is_array($listWire->structuredContent)) {
    throw new RuntimeException('An empty JSON list lost its list identity.');
}

$arrayObject = $modernType->fromArray([
    'resultType' => 'complete',
    'content' => [],
    'structuredContent' => new stdClass(),
]);
$arrayList = $modernType->fromArray([
    'resultType' => 'complete',
    'content' => [],
    'structuredContent' => [],
]);
$arrayObjectWire = json_decode(json_encode($arrayObject, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
$arrayListWire = json_decode(json_encode($arrayList, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
if (!$arrayObjectWire->structuredContent instanceof stdClass || !is_array($arrayListWire->structuredContent)) {
    throw new RuntimeException('Explicit stdClass/array empty-value identity was not retained.');
}

$wireArray = $arrayObject->toWireArray();
assertSameValue(true, is_array($wireArray), 'The JSON-ready representation is not a top-level array.');
if (!$wireArray['structuredContent'] instanceof stdClass) {
    throw new RuntimeException('toWireArray() lost nested JSON object identity.');
}
$wireArray['structuredContent']->changed = true;
$freshWireArray = $arrayObject->toWireArray();
if (!$freshWireArray['structuredContent'] instanceof stdClass) {
    throw new RuntimeException('toWireArray() changed nested JSON object representation.');
}
assertSameValue(
    false,
    property_exists($freshWireArray['structuredContent'], 'changed'),
    'Mutating toWireArray() output changed the immutable record.'
);

$decodedObject = json_decode(
    '{"resultType":"complete","content":[],"structuredContent":{}}',
    false,
    512,
    JSON_THROW_ON_ERROR
);
$decodedList = json_decode(
    '{"resultType":"complete","content":[],"structuredContent":[]}',
    false,
    512,
    JSON_THROW_ON_ERROR
);
$valueObjectWire = $modernType->fromValue($decodedObject)->toWireArray();
$valueListWire = $modernType->fromValue($decodedList)->toWireArray();
if (!$valueObjectWire['structuredContent'] instanceof stdClass || !is_array($valueListWire['structuredContent'])) {
    throw new RuntimeException('fromValue() lost decoded stdClass/list identity.');
}

$decodedNumericKeys = json_decode(
    '{"resultType":"complete","content":[],"structuredContent":{"0":"a","1":"b"}}',
    false,
    512,
    JSON_THROW_ON_ERROR
);
$numericKeysWire = json_decode(
    json_encode($modernType->fromValue($decodedNumericKeys), JSON_THROW_ON_ERROR),
    false,
    512,
    JSON_THROW_ON_ERROR
);
if (!$numericKeysWire->structuredContent instanceof stdClass || $numericKeysWire->structuredContent->{'0'} !== 'a') {
    throw new RuntimeException('fromValue() re-emitted an all-numeric-key JSON object as a list.');
}

assertValidationFails(
    static function () use ($modernType): void {
        $modernType->fromValue(['one', 'two']);
    },
    'fromValue() accepted a non-empty list where a record is required.'
);

$emptyResult = Schemas::v20251125()->emptyResult()->fromArray([]);
assertSameValue('EmptyResult', $emptyResult->typeName(), 'A direct record alias lost its public logical name.');
assertSameValue(false, $modernSchema->hasType('ProgressToken'), 'A scalar alias leaked into the record catalog.');
assertLogicFails(
    static function () use ($modernSchema): void {
        $modernSchema->type('ProgressToken');
    },
    'A scalar alias was accepted by the record-only Type API.'
);

$legacyEmptyType = Schemas::v20251125()->emptyResult();
$immutable = $legacyEmptyType->fromJson('{"extension":{"nested":1}}');
$extension = $immutable->get('extension');
if (!$extension instanceof stdClass) {
    throw new RuntimeException('An unknown JSON object was not retained as an object.');
}
$extension->nested = 2;
$freshExtension = $immutable->get('extension');
if (!$freshExtension instanceof stdClass) {
    throw new RuntimeException('An unknown JSON object changed representation after reading.');
}
assertSameValue(1, $freshExtension->nested, 'Mutating a returned unknown object changed the immutable record.');

assertValidationFails(
    static function () use ($legacyEmptyType): void {
        $legacyEmptyType->fromArray(['extension' => INF]);
    },
    'A non-finite number was accepted as a JSON wire value.'
);

assertValidationFails(
    static function () use ($modernSchema): void {
        $modernSchema->inputRequiredResult()->fromArray(['resultType' => 'input_required']);
    },
    'An InputRequiredResult with neither inputRequests nor requestState was accepted.'
);
$stateOnly = $modernSchema->inputRequiredResult()->fromArray([
    'resultType' => 'input_required',
    'requestState' => 'opaque-state',
]);
assertSameValue('opaque-state', $stateOnly->get('requestState'), 'A requestState-only InputRequiredResult failed to hydrate.');

$cacheable = $modernSchema->cacheableResult()->fromArray([
    'resultType' => 'complete',
    'ttlMs' => 0,
    'cacheScope' => 'private',
]);
assertSameValue(0, $cacheable->get('ttlMs'), 'A ttlMs at the schema @minimum bound failed to hydrate.');
assertValidationFails(
    static function () use ($modernSchema): void {
        $modernSchema->cacheableResult()->fromArray([
            'resultType' => 'complete',
            'ttlMs' => -5,
            'cacheScope' => 'private',
        ]);
    },
    'A negative ttlMs was accepted despite the schema @minimum 0 bound.'
);
assertValidationFails(
    static function () use ($schema): void {
        $schema->type('Annotations')->fromArray(['priority' => 1.5]);
    },
    'A priority above the schema @maximum 1 bound was accepted.'
);

echo "Generic record, dual-revision, union, map, null, identity, immutability, and constraint seams passed.\n";

# PHP MCP Schema — descriptor-backed record experiment

This branch is a ground-up experiment in representing multiple Model Context Protocol revisions with one immutable generic record runtime. It replaces the generated concrete DTO class trees; it is not a backward-compatible release candidate.

The package currently ships MCP `2025-11-25` and `2026-07-28` as explicit catalogs. The generator compiles 300 revision-bound logical types into 225 structurally unique descriptors and 14 PHP source files.

## Using a revision

Select a revision explicitly, look up a logical MCP type, and hydrate its wire array:

```php
use WP\McpSchema\Revision;
use WP\McpSchema\Schemas;

$schema = Schemas::revision(Revision::V20260728);
$result = $schema->type('CallToolResult')->fromArray([
    'resultType' => 'complete',
    'content' => [
        ['type' => 'text', 'text' => 'Sunny'],
    ],
]);

$result->revision(); // '2026-07-28'
$result->typeName(); // 'CallToolResult'
$result->get('resultType'); // 'complete'
$result->has('isError'); // false: omitted, not present-null
$result->toArray(); // plain PHP array; nested objects become arrays
$result->toWireArray(); // top-level array; nested JSON object/list identity is retained
json_encode($result, JSON_THROW_ON_ERROR); // JSON wire object
```

There is no ambient “current revision.” Records and every nested record retain the revision that hydrated them.

Run `composer demo` to print both revisions flowing through the same runtime with separate identities and wire output.

## Discoverable typed catalogs

Generated catalog methods provide top-level wire and hydrated field shapes to PHPStan:

```php
$result = Schemas::v20260728()
    ->callToolResult()
    ->fromArray($wire);

$content = $result->get('content');
// PHPStan: array<int, Record<array<string, mixed>, array<string, mixed>>>
```

`Type<TWire, TFields>` uses separate generic shapes because the accepted wire value and hydrated value are not identical. `content` enters as a list of arrays but is exposed as a list of nested `Record` instances. `Record<TWire, TFields>::toArray()` returns `TWire`, while `get()` reads from `TFields`.

Every public catalog type also exports importable `<Type>Wire` and `<Type>Fields` PHPStan aliases. They keep records typed when an application stores them instead of consuming them in one expression:

```php
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Generated\V20260728Schema;

/**
 * @phpstan-import-type ToolWire from V20260728Schema
 * @phpstan-import-type ToolFields from V20260728Schema
 */
final class ToolStore
{
    /** @var Record<ToolWire, ToolFields> */
    private Record $tool;
}
```

Nested named records retain their own generated wire and field aliases where PHPStan can resolve them safely. Very large aggregate unions use broad nested record types to avoid recursive or unresolvable aliases.

String lookup remains available for dynamic consumers, but returns broad `array<string, mixed>` types. It exposes only record-compatible logical types; scalar aliases remain internal descriptor references. Prefer the catalog method when the logical type is known in code.

## Exact wire behavior

The shared runtime validates and decodes:

- required fields separately from optional fields;
- explicit `null` separately from omission;
- string, number, boolean, and numeric or string literals;
- lists, tuples, indexed maps, inline objects, unions, intersections, and `Omit` inheritance;
- strict object/map boundaries that reject non-empty sequential arrays instead of silently re-keying them as JSON objects;
- discriminator unions such as `ContentBlock`, `InputRequest`, and `InputResponse`;
- open records that preserve and re-emit extension keys;
- closed records that reject unrecognized keys.

Nested schema objects and maps hydrate to `Record` instances. Values declared as `unknown` remain unchanged.

### Empty JSON objects and lists

PHP arrays cannot distinguish an empty JSON object from an empty JSON list. Use `fromJson()` when the original JSON is available; it preserves that distinction. With `fromArray()`, use `new stdClass()` for an empty object and `[]` for an empty list:

```php
$object = $type->fromArray([
    'resultType' => 'complete',
    'content' => [],
    'structuredContent' => new stdClass(),
]);

$list = $type->fromArray([
    'resultType' => 'complete',
    'content' => [],
    'structuredContent' => [],
]);
```

`toArray()` necessarily normalizes `stdClass` to an array. Use `toWireArray()` when a consumer requires a top-level array but must retain nested JSON objects as `stdClass`; use normal JSON serialization when the top level should also remain an object. Both JSON-ready paths return defensive copies.

For values declared as `unknown`, the schema cannot infer whether an empty PHP array means `{}` or `[]`. Pass `new stdClass()` when caller intent is an object.

## Revision constants

Literal constants exported by each official schema are generated into revision-specific classes:

```php
use WP\McpSchema\Generated\V20260728Constants;

V20260728Constants::LATEST_PROTOCOL_VERSION; // '2026-07-28'
V20260728Constants::JSONRPC_VERSION; // '2.0'
V20260728Constants::HEADER_MISMATCH; // -32020
V20260728Constants::UNSUPPORTED_PROTOCOL_VERSION; // -32022
```

This keeps protocol and error literals revision-bound. It does not make the schema package responsible for negotiation or error policy.

## Architecture

The generated package has five layers:

1. `Revision` and `Schemas` select an immutable revision catalog.
2. Generated catalogs map logical names to content hashes and add typed accessors and importable PHPStan aliases.
3. `DescriptorPool` stores each locally unique descriptor once across revisions.
4. Generated constants classes retain each revision's exported protocol and error literals.
5. One recursive runtime validates, hydrates, and serializes every descriptor.

A descriptor hash identifies a local structural definition. References inside it remain logical type names and resolve through the selected revision manifest. The revision fingerprint hashes the exported constants and complete logical-name-to-descriptor binding, so any constant or definition change changes the revision fingerprint even when referring descriptors are shared.

The generated descriptor pool and revision schemas are cached in-process. There is no mutable global registry and no cross-revision cache key. Composer or Jetpack package-copy selection still determines which package implementation owns the shared `WP\McpSchema` class names; this model does not make two different package releases with the same namespace independently loadable.

The package still owns schema validation, hydration, and serialization only. Protocol negotiation, sessions, retries, revision translation, and downgrade policy remain consumer responsibilities.

## Compatibility consequences

Adopting this model as the package API would require a major release. It removes:

- public DTO constructors and typed getters;
- concrete revision-specific DTO classes and union interfaces;
- DTO `instanceof` checks and reflection over concrete properties;
- static `SomeDto::fromArray()` entry points.

Consumers gain a much smaller runtime surface, explicit revision identity, exact runtime validation, revision constants, and catalog-assisted array shapes. They lose native concrete-class ergonomics. This branch intentionally tests that tradeoff rather than hiding it behind compatibility shims.

## Development

Generate the package from both official TypeScript schemas:

```bash
cd generator
npm install
npm run build
npm run generate
```

Run the executable wire checks and PHPStan contract checks:

```bash
composer check
cd generator && npm test
```

Measure catalog memory and repeated hydrate/serialize throughput locally:

```bash
composer benchmark
# Optional iteration count:
composer benchmark -- 50000
```

The benchmark reports observations for the current PHP runtime and machine. It intentionally has no pass/fail performance threshold; use its output to compare revisions of this experiment under the same environment.

`src/` is generated. Change the compiler or PHP templates under `generator/`, then regenerate; never edit generated PHP directly.

## Scope

This is a throwaway architecture prototype on `try/descriptor-backed-generic-record-model`. It exists to answer whether a generic record/catalog API can preserve exact revision wire contracts with acceptable PHPStan ergonomics. It is not published and does not preserve the current package API.

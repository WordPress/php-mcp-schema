# Migrating from generated DTOs to the schema runtime

The exact-revision schema runtime replaces every generated DTO, union factory,
enum object, validation flag, and `toArray()` variant. There are no aliases or
parallel construction paths.

Ordinary WordPress Ability authors should not add protocol-revision branches.
MCP Adapter owns revision-specific projection, required protocol defaults, and
omission of fields removed by a selected revision. Direct consumers of this
package must migrate as described below.

## Select an exact revision

Construction now starts with a selected schema:

```php
use WP\McpSchema\Schemas;

$schemas = Schemas::create();
$schema = $schemas->forVersion(Schemas::V2025_11_25);
```

Supported identifiers are exactly `2025-11-25` and `2026-07-28`. Unknown
identifiers throw `UnsupportedRevisionException`. `Schema` has no public
constructor, so a custom document or false revision label cannot bypass this
selection boundary.

## Update imports

| Removed import pattern | Replacement |
| --- | --- |
| `WP\McpSchema\Client\...\DTO\Name` | `WP\McpSchema\Record\Name` |
| `WP\McpSchema\Common\...\DTO\Name` | `WP\McpSchema\Record\Name` |
| `WP\McpSchema\Server\...\DTO\Name` | `WP\McpSchema\Record\Name` |
| `...\Union\NameInterface` | `WP\McpSchema\Contract\Name` when generated |
| `...\Enum\Name` | `WP\McpSchema\Value\Name` when generated |

A public symbol can exist in the package but be unavailable under one selected
revision. Construction then throws `UnavailableTypeException`. This is how the
union public roster remains source-compatible without pretending a removed
protocol type is valid.

## Replace constructors and `fromArray()` statics

Before:

```php
use WP\McpSchema\Server\Tools\DTO\Tool;

$tool = Tool::fromArray(array(
    'name' => 'weather',
    'inputSchema' => array('type' => 'object'),
));
```

After:

```php
use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

$schema = Schemas::create()->forVersion(Schemas::V2026_07_28);
$tool = $schema->fromArray(Tool::class, array(
    'name' => 'weather',
    'inputSchema' => array('type' => 'object'),
));
```

Use the entry point that matches the source value:

- `fromArray()` for programmatic PHP associative arrays and lists.
- `fromValue()` for decoded `stdClass`/list graphs or an existing immutable
  record that must be revalidated under the target revision.
- `fromJson()` for raw JSON text. This is the safest ingress path when `{}`
  versus `[]` or numeric-string object keys matter.

Every entry point performs complete canonical validation. Validation-off flags
and filters have no replacement.

## Replace union factories

Construct through a useful generated contract and inspect the concrete record:

```php
use WP\McpSchema\Contract\ContentBlock;
use WP\McpSchema\Record\TextContent;

$block = $schema->fromArray(ContentBlock::class, array(
    'type' => 'text',
    'text' => 'Hello',
));

if ($block instanceof TextContent) {
    echo $block->getText();
}
```

Official union order is preserved. Same-name kind changes are intentionally
represented by different roots. For example,
`Contract\ClientNotification` is a `2025-11-25` union root, while
`Record\ClientNotification` is a `2026-07-28` object root.

## Read and serialize records

Generated named getters remain available for fields declared by the selected
schema. Shared getters cover compatible fields across supported revisions and
return `null` when a field is not declared by the selected revision.

Use generic access for presence and open-schema extensions:

```php
$record->has('field'); // distinguishes omitted from explicit null
$record->get('field'); // declared field or present extension
```

An absent unknown extension throws `UnknownFieldException` rather than silently
returning `null`.

Replace every `toArray()` variant with `jsonSerialize()` or direct JSON
encoding:

```php
$wireObject = $record->jsonSerialize();
$json = json_encode($record, JSON_THROW_ON_ERROR);
```

The output is a defensive `stdClass`. Mutating it cannot mutate the record.

## Account for intentional wire corrections

The runtime now enforces JSON behavior that the DTO implementation could not
represent consistently:

- omitted and explicit `null` are distinct;
- JSON objects remain `stdClass` and lists remain PHP lists;
- non-empty sequential arrays do not satisfy object union members;
- integral JSON Schema numbers preserve their PHP `int` or integral `float`
  kind;
- native-integer overflow, non-finite numbers, malformed UTF-8 values or keys,
  resources, closures, unsupported objects, cycles, and excessive nesting are
  rejected; and
- validation failures include JSON Pointer paths where applicable.

For unconstrained values, use `new stdClass()` when an empty object must be
unambiguous. An empty PHP array is interpreted using the expected schema
position.

## Account for revision removals and replacements

Do not assume that a method or field from one revision exists in another.
Notable differences include:

- `ping`, initialization, logging-level requests, root-list changes, core
  tasks, and the earlier resource-subscription flow are absent or replaced in
  `2026-07-28`.
- `server/discover`, per-request metadata, result discriminators, cache fields,
  embedded input requests, and typed protocol errors are introduced in
  `2026-07-28`.
- `Tool.execution` is declared under `2025-11-25` but not under `2026-07-28`.
  Peer-supplied data with that key can still round-trip as opaque extension data;
  the named getter does not revive removed semantics.
- `CallToolResult.structuredContent` and numeric elicitation bounds widen under
  `2026-07-28` and remain narrow under `2025-11-25`.

Use the selected schema's directional availability methods before dispatch or
advertisement. Schema availability is complete; an application must still
intersect it with handlers it actually implements.

## MCP Adapter integration boundary

MCP Adapter must pass the selected `Schema` into protocol-facing catalog,
metadata, dispatch, decode, and encode paths. Its list filters receive
revision-projected records, and components are validated and cached separately
per revision. A projection failure removes a component only from that revision;
global registration fails only if no supported projection succeeds.

Existing Ability registration, permissions, execution callbacks, hooks, and
logical results remain unchanged where the selected MCP revision still defines
or replaces that capability. Adapter supplies conservative protocol-owned 2026
defaults: `resultType: "complete"`, `ttlMs: 0`, and
`cacheScope: "private"`.

## Removed API checklist

Remove consumer references to:

- `WP\McpSchema\Client`, `WP\McpSchema\Common`, and `WP\McpSchema\Server`;
- `AbstractDataTransferObject`, `AbstractEnum`, DTO contracts, and validation
  traits;
- DTO and union factories;
- DTO constructors and static `fromArray()` calls;
- enum instances;
- validation flags or validation-mode filters; and
- `toArray()`, `toArrayWithSkippedNullValues()`, and similar output variants.

Then select an exact schema, construct through `Schema`, use generated getters
or `get()`/`has()`, and serialize with `jsonSerialize()`.

## WordPress MCP Adapter migration

The Adapter consumes the new runtime at the raw transport boundary. It selects
one exact revision, passes the matching `Schema` in an immutable request
context, hydrates the incoming request before dispatch, and hydrates the exact
result and JSON-RPC response before encoding.

Adapter component definitions remain backward compatible at their logical
Ability-facing input boundary. Each component is projected independently into
the 2025 and 2026 catalogs. A revision-specific projection failure removes only
that projection; registration is rejected globally only when no supported
projection succeeds. Adapter supplies conservative 2026 protocol-owned defaults
of `resultType: "complete"`, `ttlMs: 0`, and `cacheScope: "private"` when the
logical component does not provide revision-specific values.

The migration removes Adapter DTO factories, DTO serialization helpers,
validation-mode filters, and protocol DTO accessors literally. There are no
aliases, facades, class aliases, or revision-neutral substitute DTOs.

The completed Adapter integration is reviewable on branch
`feature/dual-revision-schema-runtime` at exact commit
`2b98fee235220c3771f3726e294a9bba5be6546a`. Its Composer lock deliberately
pins this schema runtime to exact implementation ref `a0fb1ee`; later
documentation-only schema commits do not change that runtime dependency.

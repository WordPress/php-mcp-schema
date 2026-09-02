# Migrating from generated DTOs to the schema runtime

The exact-revision schema runtime replaces every generated DTO, union factory,
enum object, validation flag, and `toArray()` variant. There are no aliases or
parallel construction paths.

Applications select the revision from their own negotiation or request context,
then intersect canonical availability with the handlers and capabilities they
implement. This package does not define application defaults, transport policy,
or dispatch behavior.

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

For overlapping object unions, hydration now chooses the valid member declaring
the most input keys; canonical order breaks a tie. Scalar unions keep canonical
first-match behavior, and JSON Schema `anyOf` validity is unchanged. Same-name
kind changes are intentionally represented by different roots. For example,
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

PHP converts numeric-string array keys such as `"0"` to integers, so
`fromArray()` cannot represent an object consisting only of sequential numeric
keys. Use `fromJson()` or an explicit `stdClass` when those keys matter.

For empty values, `fromArray()` interprets `array()` as an object only where the
selected schema requires an object; otherwise it remains the JSON list `[]`.
For example, empty `structuredContent` becomes `{}` under `2025-11-25`, where
that field is object-constrained, and remains `[]` under unconstrained
`2026-07-28`. Use `new stdClass()` when an unconstrained empty object must be
unambiguous.

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
- `CallToolResult.structuredContent` widens from object-only in `2025-11-25` to
  any JSON value in `2026-07-28`.
- Fractional `ElicitResult.content` values and fractional
  `NumberSchema.default`/`minimum`/`maximum` values are accepted in both
  revisions through reviewed canonical corrections. `JSONValue` additionally
  accepts fractional numbers and `null` in `2026-07-28`.

Use the selected schema's directional availability methods before dispatch or
advertisement. Schema availability is complete; an application must still
intersect it with handlers it actually implements.

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

## Reviewed canonical JSON corrections

The package preserves each downloaded `schema.json` byte-for-byte and verifies
its raw digest. Before audit and generation, one verified loader applies a
digest-pinned patch ledger whose entries name the exact old value, replacement,
rationale, and authoritative `schema.ts` line. Generated catalogs, the AJV
oracle, and PHP tests all consume that same effective document.

The reviewed corrections are:

- `ElicitResult.content` accepts `number` values in both revisions, matching the
  pinned TypeScript definitions for
  [2025-11-25](https://github.com/modelcontextprotocol/modelcontextprotocol/blob/38c84e9f93ad191d9eb26d92b945d17bd0efcaf3/schema/2025-11-25/schema.ts#L2481)
  and
  [2026-07-28](https://github.com/modelcontextprotocol/modelcontextprotocol/blob/5f5440bb26a62e2cf3440b92da5a667efa03b267/schema/2026-07-28/schema.ts#L3148).
- `2026-07-28` `JSONValue` accepts `number` and `null`, matching its
  [TypeScript union](https://github.com/modelcontextprotocol/modelcontextprotocol/blob/5f5440bb26a62e2cf3440b92da5a667efa03b267/schema/2026-07-28/schema.ts#L6-L7).
- `2025-11-25` `NumberSchema.default`, `minimum`, and `maximum` accept `number`,
  matching the
  [TypeScript fields](https://github.com/modelcontextprotocol/modelcontextprotocol/blob/38c84e9f93ad191d9eb26d92b945d17bd0efcaf3/schema/2025-11-25/schema.ts#L2258-L2260).

Other integer-constrained fields remain integer-only, and integral floats that
fit the native PHP integer range retain their float kind.

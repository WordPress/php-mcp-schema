# Proposal: one PHP schema runtime for MCP 2025 and 2026

Status: approved for end-to-end implementation on the proposal branches. This
document describes proposed behavior, not the currently released package.

## Executive summary

`php-mcp-schema` currently generates a concrete DTO tree from one MCP revision.
MCP `2026-07-28` introduces breaking schema and wire changes. Generating a
second revision-specific DTO tree would duplicate a large number of files and
make every future breaking revision progressively harder to maintain.

This proposal replaces the DTO system with one small, dependency-free,
revision-selected runtime. It consumes the canonical `schema.json` files for MCP
`2025-11-25` and `2026-07-28`, generates one PHP catalog per revision, and
hydrates values into one shared set of concrete logical record classes.

The proposal is not complete until MCP Adapter has switched entirely to the new
records and its existing tools, resources, prompts, initialization, errors,
HTTP transport, and STDIO transport work end to end under both revisions.

This is one proposal across two repositories, not a sequence of separately
approved prototypes.

## The problem

The existing DTO approach binds PHP classes to one schema snapshot. A breaking
MCP revision creates three bad choices:

1. Replace the old DTOs and break clients using the previous revision.
2. Generate another complete DTO namespace and maintain parallel trees.
3. Add compatibility facades that hide duplication without removing it.

The second option produces the file growth this project is trying to avoid. The
third creates extra conversions, aliases, and ambiguous ownership. Neither
demonstrates that one PHP package can model breaking MCP revisions cleanly.

The proposal instead separates two concepts:

- **Revision catalogs** describe what is valid for one exact MCP revision.
- **Logical records** represent stable named MCP concepts such as `Tool`,
  `TextContent`, and `CallToolResult` across revisions.

Revision differences live in catalogs and validation. They do not create copies
of every PHP value class.

## Supported revisions

The complete supported set is exactly:

- `2025-11-25`
- `2026-07-28`

MCP Adapter will not advertise or validate `2024-11-05`, `2025-03-26`, or
`2025-06-18`.

When a client requests an unsupported or unknown revision, MCP Adapter
counter-proposes `2025-11-25`. The client either accepts that supported revision
or disconnects. Counter-proposal does not mean the requested revision is
supported.

## What completeness means

`php-mcp-schema` supports the complete canonical structural schemas for both
revisions.

MCP Adapter supports its complete existing server feature set under both
revisions:

- initialization and negotiation;
- ping;
- tool discovery and calls;
- resource and resource-template discovery;
- resource reads;
- prompt discovery and retrieval;
- JSON-RPC success and error responses;
- HTTP and STDIO transports; and
- existing execution, permission, filter, and observability behavior.

The proposal does not add new Adapter product capabilities. Sampling,
elicitation, tasks, continuation, or other optional MCP features remain
unadvertised unless they already exist and work in `trunk`.

## Package ownership

### `php-mcp-schema` owns

- canonical revision sources and SHA-256 verification;
- deterministic generated PHP catalogs;
- exact revision validation;
- union selection and recursive hydration;
- omitted versus present-null identity;
- JSON object versus list identity;
- concrete logical records and useful union contracts;
- native numeric and JSON safety boundaries; and
- exact record serialization.

### MCP Adapter owns

- protocol negotiation and counter-proposal policy;
- the lifetime of a selected revision;
- HTTP, STDIO, headers, and sessions;
- revision-neutral component registration;
- WordPress Abilities integration;
- execution and permissions;
- filters and hooks;
- observability; and
- JSON-RPC and transport error mapping.

`php-mcp-schema` remains WordPress-agnostic. It contains no WordPress functions,
hooks, abilities, transports, sessions, or `WP_Error` values.

Ability argument and result validation remains owned by WordPress Abilities and
the existing Adapter execution paths. The schema package validates the MCP
protocol record that contains `inputSchema` or `outputSchema`; it does not become
a general validator for arbitrary user-authored tool schemas.

## Canonical sources and generated output

The generator vendors two immutable official files:

```text
resources/schema/2025-11-25/schema.json
resources/schema/2026-07-28/schema.json
```

Their reviewed SHA-256 digests are pinned. Normal generation is offline and
fails if a source digest changes unexpectedly.

The generator is a small Node ESM program using built-in Node APIs. AJV is a
development dependency used to validate canonical documents and provide an
independent conformance oracle. The generator does not use TypeScript,
`ts-morph`, Commander, Chalk, Ora, or `fs-extra`.

It generates:

- one deterministic PHP catalog for `2025-11-25`;
- one deterministic PHP catalog for `2026-07-28`;
- one shared concrete class per named record-compatible MCP type;
- the small set of meaningful public union interfaces; and
- small constant classes for named scalar values consumers actually use.

Each catalog is a direct PHP-literal translation of the canonical JSON Schema
vocabulary. `$ref`, `type`, `properties`, `required`, `anyOf`, `allOf`, and other
supported keywords remain recognizable. There is no private descriptor
language, per-definition fragment graph, generated executable validator,
revision delta format, or generated PHPStan shape universe.

The generator audits exactly the schema positions, keywords, reference forms,
and combinations used by the two supported documents. An unknown construct
fails generation instead of being ignored.

Generated files live in a dedicated generated subtree. The generator replaces
only that resolved subtree; handwritten providers, interpreters, exceptions,
and record infrastructure remain outside its deletion boundary.

Before generating getters, the generator compares every same-named definition
across both revisions. Additive optional fields share one record. Compatible
field changes use the narrowest honest union or widened PHPDoc. An incompatible
meaning or callable getter contract either becomes a genuinely different
logical record or fails generation for an explicit design decision. It is never
silently widened to `mixed`.

## Runtime API

Consumers create one provider and select an exact revision explicitly:

```php
use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

$schemas = Schemas::create();
$schema  = $schemas->forVersion(Schemas::V2025_11_25);

$tool = $schema->fromArray(Tool::class, $data);
```

The selected schema exposes three construction boundaries:

```php
$record = $schema->fromArray(Tool::class, $data);
$record = $schema->fromValue(Tool::class, $decodedValue);
$record = $schema->fromJson(Tool::class, $rawJson);
```

- `fromArray()` accepts programmatic PHP arrays. Schema context determines
  whether an empty nested array represents an object or list.
- `fromValue()` accepts already-decoded JSON values while preserving `stdClass`,
  lists, numeric-string object keys, and empty-value identity.
- `fromJson()` owns raw decoding when the package receives JSON directly.

All three methods converge on one internal validator and hydrator. They do not
implement independent conversion paths.

The API accepts concrete record classes and the small set of supported union
interfaces. Union contracts are valid construction roots, so a consumer may
call `fromValue(ContentBlock::class, $value)` and receive a concrete member such
as `TextContent`. A PHPStan `class-string<T>` template preserves useful return
types without generated input-shape files.

There are no public `Type` handles, per-type schema accessors, dynamic logical
type strings, standalone validators, unchecked constructors, or mutable global
revision state.

## Records

Public records use flat namespaces:

```text
WP\McpSchema\Record\Tool
WP\McpSchema\Record\CallToolResult
WP\McpSchema\Record\TextContent
WP\McpSchema\Contract\ContentBlock
WP\McpSchema\Value\Role
```

Definition names are globally unique, so recreating the old
Client/Server/Common/Domain/Subdomain tree adds no runtime meaning.

An abstract `Record` directly owns immutable field values and presence. Concrete
record classes extend it; they do not wrap another generic-record object.

Records expose:

```php
$record->getName();
$record->get('name');
$record->has('name');
$record->jsonSerialize();
```

- Generated named getters preserve IDE discovery, PHPStan value, and surgical
  MCP Adapter changes.
- `get()` supports genuinely dynamic consumers.
- `has()` distinguishes omitted fields from fields explicitly containing null.
- A declared field or an instance-present extension field is accessible through
  `get()`.
- An omitted declared field returns null from `get()` and has `has() === false`.
- An absent undeclared field throws instead of silently returning null.
- `jsonSerialize()` is the only complete record output.

There is no `toArray()`, `toWireArray()`, or `toJsonArray()`. Multiple full
serialization views invite callers to lose JSON object/list identity.

Records have no public constructors or public unchecked hydration methods. A
private hydrator closure, bound to `Record` scope with `Closure::bind()`, invokes
the final protected base constructor only after successful validation and
hydration. This PHP 7.4-compatible mechanism remains inside the runtime; it does
not add a callable unchecked factory to the public API.

Named nested objects become concrete records. Anonymous objects remain
`stdClass`; arrays remain lists. `get()` and `jsonSerialize()` return defensive
copies of mutable native values, while nested records may be returned directly
because records are immutable.

Records retain finalized wire keys and values, not a schema service or revision
reference. A record cannot be silently reinterpreted under another revision. To
move a value between revisions, reconstruct it through the target schema and
let that schema validate it.

Programmatic input may contain nested immutable `Record` instances. The common
hydrator reads their defensive serialized value and validates it again under
the selected target schema. A record is therefore convenient input, but never a
bypass around cross-revision validation.

## Constants, enums, and unions

PHP 7.4 has no native enums. Enum-like MCP fields therefore remain strings or
numbers with precise literal-union PHPDoc. Small constant classes provide names
where useful:

```php
Schemas::V2025_11_25;
Schemas::V2026_07_28;
Role::USER;
Role::ASSISTANT;
```

There is no `AbstractEnum`, enum-instance cache, `from()`, `tryFrom()`, or enum
factory.

The schema interpreter selects union branches in official schema order. There
are no generated union factories. Marker interfaces are generated only for real
object contracts used by consumers, such as content blocks; the generator does
not create an interface for every structural union.

## Validation

Validation is always on. Every call to `fromArray()`, `fromValue()`, or
`fromJson()` validates against the complete selected MCP definition before
returning a record.

This matches the official TypeScript SDK principle: wire codecs validate
requests before dispatch, responses are parsed against result schemas, and
declared tool schemas are enforced at tool boundaries. There is no production
mode in which protocol correctness is disabled.

The PHP interpreter supports exactly the JSON Schema keywords and combinations
used by the two canonical MCP documents. It is intentionally not a reusable
general JSON Schema product.

AJV runs only in development and CI. PHP production has zero runtime
dependencies. A general third-party JSON Schema validator would not remove the
need for schema traversal during union selection and hydration, so adding one
would create a second behavioral owner rather than simplify the runtime.

Canonical `schema.json` is the structural authority. There is no generic
semantic-overlay framework. If an end-to-end test proves one specific normative
MCP rule is absent from the canonical schema and required for correct behavior,
that rule is implemented directly, named, sourced, and tested.

Validation rejects:

- incorrect object/list identity;
- missing required fields;
- illegal or unknown closed-object fields;
- invalid union members;
- schema assertion failures;
- unsupported revisions or record classes;
- non-finite numbers;
- native integer overflow before PHP silently changes the JSON type;
- malformed UTF-8 and Unicode escapes;
- cyclic programmatic structures;
- resources, closures, and unserializable values; and
- values beyond the shared depth boundary.

Errors use a small package exception hierarchy. Legacy DTO exception classes and
exact constructor error messages are not compatibility requirements.

## Exact JSON behavior

Compatibility means preserving what the selected MCP specification requires,
not preserving invalid DTO output.

The new runtime deliberately corrects known representation defects:

- empty records serialize as `{}`, not `[]`;
- empty object fields remain `{}`;
- present null remains present when the selected schema permits null;
- an overflowing integer is rejected instead of becoming a float; and
- `INF` and `NAN` fail during construction.

Valid existing 2025 wire behavior remains unchanged. Every intentional
correction receives a named regression test and is documented in the migration
guide.

## MCP Adapter integration

### Registration

Tools, resources, and prompts are registered before a client revision is known.
Adapter components therefore store revision-neutral configuration, execution
callbacks, permissions, and observability data.

During registration, each component:

1. projects its protocol data through the 2025 schema;
2. projects the same logical component through the 2026 schema;
3. rejects and logs registration if either projection is invalid; and
4. caches one immutable record per supported revision.

This proves dual-revision validity when the component is registered instead of
failing when the first 2026 client arrives. It does not duplicate record classes
or component source. It is intentionally stricter and earlier than current DTO
behavior: a component invalid for either supported revision is unavailable to
both, and its existing registration error/logging path must make that visible.

Because Adapter registration projects every component twice, an Adapter process
intentionally loads both complete revision catalogs during startup. A generic
package consumer selecting one revision still loads only that catalog. Adapter
startup memory and loaded-file cost are measured as part of the proposal.

Filtered list results are validated again when the final list-result record is
constructed.

### Inbound requests

MCP Adapter owns one minimal JSON decoder because initialization must inspect the
requested protocol revision before a selected schema can validate the message.

The decoder:

1. lexically rejects integer tokens outside PHP's safe integer range before
   `json_decode()` can silently turn them into floats;
2. decodes raw JSON once with objects preserved as `stdClass`;
3. rejects malformed JSON, depth violations, invalid numbers, and unsupported
   batches;
4. extracts only the envelope data required for negotiation;
5. selects or counter-proposes a supported revision; and
6. passes the decoded value to the selected schema's `fromValue()` method.

After initialization, transport or session context supplies the selected
revision before request hydration. The decoder does not duplicate schema
validation.

### Handling and output

Handlers receive validated records and continue to own the same execution and
permission behavior as `trunk`. They construct result records through the
selected schema.

One `WireEncoder` emits both revisions. It receives the selected schema and has
small explicit branches only where 2025 and 2026 envelopes genuinely differ.
There is no abstract encoder, encoder factory, or encoder subclass per revision.

HTTP- and STDIO-specific behavior remains in the existing transports. Required
2026 headers, envelopes, and negative behavior are implemented from the
official revision specification without adding unrelated Adapter features.

## Adapter public surface

The switch is total:

- no production DTO imports;
- no DTO aliases, wrappers, proxies, `class_alias`, or dual returns;
- no Adapter-owned record substitutes;
- no compatibility mode; and
- no old DTO generator or DTO documentation in `php-mcp-schema`.

Named record getters remain because current MCP Adapter `trunk` uses 42 schema
getter calls across 11 production files, and its schema-aware tests use hundreds
more. Replacing them with generic `get()` would turn typed values into `mixed`,
require repeated narrowing, and make the Adapter diff larger. Generic `get()`
exists in the package but Adapter continues to use named getters.

Protocol-facing server getters require the selected schema:

```php
$server->get_tools($schema);
$server->get_resources($schema);
$server->get_prompts($schema);
$server->get_prompt($name, $schema);
```

There is no no-argument overload or implicit 2025 default.

Existing list filter names and their first two arguments remain. The selected
schema is added as a third argument:

```php
apply_filters('mcp_adapter_tools_list', $tools, $server, $schema);
```

The same rule applies to resource and prompt list filters. Existing WordPress
callbacks accepting two arguments continue to run; filter payload objects
become the new records.

The old `mcp_adapter_validation_enabled` filter, server validation flag, and
DTO-oriented validators are removed. Canonical MCP validation cannot be safely
disabled. Proven Adapter-specific semantic checks that canonical schemas do not
own remain at their existing boundary and run unconditionally.

Server creation, transport interfaces, execution hooks, permission callbacks,
and observability contracts remain unchanged unless an exact 2026 transport
requirement forces a narrow revision-bound change.

## Documentation and migration

The old DTO documentation and generator documentation are deleted rather than
maintained beside the replacement. Tagged releases preserve historical docs.

The completed proposal keeps:

- one architecture/proposal document describing the implemented system;
- one migration guide mapping old imports and construction to the new API; and
- current README usage examples.

The migration guide documents all intentional public breaks, including removed
DTO classes, removed `toArray()`, required schema arguments, filter payload
types, the validation-filter removal, and corrected JSON behavior.

## Verification and evidence

The proposal is complete only when all of the following are demonstrated.

### Schema package

- Both pinned canonical documents validate against their meta-schema.
- Generation is offline, deterministic, and regenerates without diff.
- Every supported keyword, position, reference form, and combination has
  fixtures.
- AJV and PHP validity results agree for the structural corpus.
- Every named definition is classified and generated or explicitly internal.
- Both revisions hydrate the expected shared concrete classes.
- Omitted/null, object/list, numeric, depth, copying, and serialization behavior
  is exact.
- PHP 7.4 and the current production PHP pass PHPUnit and PHPStan.
- The Composer archive excludes canonical JSON, generator code, AJV, tests,
  research, and local files while including all runtime catalogs and records.
- An extracted no-development-dependency artifact passes optimized-autoloader
  smoke tests.

### MCP Adapter

- A verified 2025-to-2026 difference inventory maps every relevant breaking
  change to its shared-record representation, any explicit Adapter branch, a
  positive wire test, and a negative cross-revision test.
- Existing 2025 behavior conforms to the 2025 specification.
- Every existing Adapter capability works under 2026.
- Unsupported revisions counter-propose 2025.
- Invalid requests fail before handlers execute.
- Invalid results fail before encoding.
- HTTP and STDIO raw-wire corpora cover both revisions and negative cases.
- No production DTO references remain.
- Existing filters, hooks, permissions, execution, and observability behavior
  remain intact apart from documented type changes.
- The full Adapter test, PHPCS, and PHPStan gates pass.

### Distribution and performance

- A local MCP Adapter plugin ZIP vendors the proposal package.
- Its optimized loader contains every runtime class and no removed DTO class.
- Representative 2025 and 2026 calls execute from the extracted plugin ZIP.
- Package size, loaded files, cold selection, first construction, warm
  construction, memory, throughput, and relevant OPcache evidence are measured
  against the DTO baseline.

Performance is reported evidence, not a speculative optimization project. There
are no invented percentage thresholds. Correctness regressions are blocking;
optimization occurs only for an obvious absolute problem.

## Proposal and repository shape

The work uses two dependent branches with the same proposal goal:

1. `php-mcp-schema` implements the complete two-revision runtime.
2. MCP Adapter switches completely to the schema branch.

No package release is required to demonstrate the proposal. MCP Adapter may use
the schema development branch while the proposal is reviewed. If accepted, the
schema package is released and the Adapter dependency is changed to that release.

Implementation uses coherent atomic commits, but commits are not approval gates
and the proposal is not split into multiple staged projects.

## Deliberate deletions and non-goals

Delete or do not introduce:

- every DTO class, namespace, base class, factory, and generator path;
- support for MCP revisions older than `2025-11-25`;
- per-revision record-class trees;
- DTO compatibility facades or Adapter-owned substitutes;
- public Type handles and generated per-type schema accessors;
- per-definition schema fragments and dependency-closure caches;
- generated PHPStan input/hydrated projection files;
- generic semantic-overlay machinery;
- enum instance objects and union factories;
- multiple full-record serialization methods;
- unchecked record constructors;
- validation-disabled modes;
- per-revision encoder hierarchies;
- runtime loading of canonical JSON;
- production validation dependencies;
- new MCP Adapter capabilities unrelated to the revision switch; and
- release work before the proposal is accepted.

## Why this is future-proof enough

A future MCP revision adds one canonical source and one generated catalog. It
reuses existing logical records when their public getter contracts remain
compatible and adds new records only for genuinely new named concepts.

The package does not promise that every future breaking schema can fit a minor
release. If a revision requires an incompatible getter contract, normal package
versioning applies. The important property is that revision support is expressed
through catalogs and validation rather than mechanically duplicating the full
class tree.

This proposal intentionally avoids speculative extension points. Future
requirements should change the design only when an actual canonical revision or
consumer demonstrates the need.

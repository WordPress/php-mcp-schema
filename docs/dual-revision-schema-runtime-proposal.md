# Proposal: one PHP schema runtime for MCP 2025 and 2026

Status: approved for end-to-end implementation on the proposal branches. The
schema-package half is implemented on its proposal branch; MCP Adapter and final
cross-repository artifact evidence remain incomplete. This is not currently
released package behavior.

## Executive summary

At the proposal baseline, `php-mcp-schema` generated a concrete DTO tree from
one MCP revision. MCP `2026-07-28` introduces breaking schema and wire changes.
Generating a second revision-specific DTO tree would duplicate a large number
of files and make every future breaking revision progressively harder to
maintain.

The schema proposal branch replaces the DTO system with one small,
dependency-free,
revision-selected runtime. It consumes the canonical `schema.json` files for MCP
`2025-11-25` and `2026-07-28`, generates one PHP catalog per revision, and
hydrates values into one shared set of concrete logical record classes.

The proposal is not complete until MCP Adapter has switched entirely to the new
records and its revision-valid lifecycle, tools, resources, prompts, errors,
HTTP transport, and STDIO transport flows work end to end.

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
`2025-06-18`. It never treats an unknown identifier as a compatible range or
routes it through the newest catalog. Protocol code uses exact revision names;
it does not group revisions under behavioral labels.

Unsupported-version behavior follows the selected request flow. During a
`2025-11-25` `initialize` exchange, the server may counter-propose exact
`2025-11-25`. A request using the `2026-07-28` per-request envelope with an
unsupported version receives canonical `UnsupportedProtocolVersionError`
`-32022`, including the requested identifier and exact supported identifiers.
The client decides whether to retry through another revision's request flow.

## What completeness means

`php-mcp-schema` supports the complete canonical structural schemas for both
revisions.

MCP Adapter never exposes a method outside the selected revision. Its callable
and advertised surface is the intersection of canonical message availability
and handlers it actually implements. It preserves the same logical product
capability only where that concept exists. Its supported product surface
includes:

- `initialize` and `ping` under `2025-11-25`;
- `server/discover` and per-request metadata under `2026-07-28`;
- tool discovery and calls;
- resource and resource-template discovery;
- resource reads;
- prompt discovery and retrieval;
- JSON-RPC success and error responses;
- HTTP and STDIO transports; and
- existing execution, permission, filter, and observability behavior.

The schema package remains complete even where MCP Adapter does not implement an
optional product capability. MCP Adapter implements mandatory mechanisms needed
to participate in a selected revision and replacements needed for a logical
capability it already exposes. Sampling, elicitation, tasks, continuation, or
other optional features remain unadvertised unless they already exist and work
in `trunk`. A removed method is unavailable even if an old handler still exists;
a schema definition alone does not advertise an unimplemented feature.

## Package ownership

### `php-mcp-schema` owns

- canonical revision sources and SHA-256 verification;
- deterministic generated PHP catalogs;
- exact revision validation;
- exact definition and directional message availability derived from each
  catalog;
- union selection and recursive hydration;
- omitted versus present-null identity;
- JSON object versus list identity;
- concrete logical records and useful union contracts;
- native numeric and JSON safety boundaries; and
- exact record serialization.

### MCP Adapter owns

- exact-revision negotiation policy;
- the lifetime of a selected revision;
- one immutable request context carrying the selected schema, capabilities,
  identity, and transport metadata;
- HTTP, STDIO, headers, and sessions;
- exact-revision wire profiles and implemented-handler availability;
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
- one shared concrete class per compatible named record type present in any
  supported revision;
- kind-specific record and contract symbols when the same canonical name changes
  between an object, union, or alias;
- meaningful public union interfaces for revisions where those unions are
  canonical;
- exact definition availability plus request, notification, and embedded-input
  availability keyed by protocol direction and message kind;
- a reviewable compatibility manifest; and
- small constant classes for named scalar values consumers actually use.

Each catalog is a direct PHP-literal translation of the canonical JSON Schema
vocabulary. `$ref`, `type`, `properties`, `required`, `anyOf`, `allOf`, and other
supported keywords remain recognizable. There is no private descriptor
language, per-definition fragment graph, generated executable validator,
revision delta format, or generated PHPStan shape universe.

The generator audits exactly the schema positions, keywords, reference forms,
and combinations used by the supported documents. It compares a newly added
revision against every still-supported revision. The compatibility manifest
combines a generated structural diff with a checked-in human classification
file. The human input cites exact specification sources for lifecycle,
transport, directional semantics, deprecation, and Adapter projection
requirements that canonical JSON Schema cannot express. The generator verifies
that every structural change is classified and emits the merged reviewable
manifest. An unknown or unclassified construct fails generation instead of
being ignored.

Generated public symbols live at their PSR-4 domain paths under `src/Record/`,
`src/Contract/`, and `src/Value/`. Generated runtime metadata uses role-based
internal paths under `src/Internal/Catalog/` and
`src/Internal/TypeRegistry.php`. Composer maps only `WP\McpSchema\` to `src/`.
There is no `WP\McpSchema\Generated` namespace: generation is provenance, while
namespaces and paths describe domain or runtime responsibility.

The generator writes a complete staging tree before replacing only those five
allowlisted paths. Handwritten providers, interpreters, exceptions,
`src/Record.php`, `src/Schema.php`, and other handwritten runtime files remain
outside its deletion boundary.

Before generating getters, the generator compares every same-named definition
across all supported revisions. Compatible objects share one class whose named
getters cover the union of compatible declared fields. Added, removed, or
optional fields use the narrowest honest nullable or union PHPDoc. A field is
never silently widened to `mixed`. An incompatible meaning or callable getter
contract fails generation for an explicit design decision.

When a canonical name changes kind, the PHP surface remains honest rather than
inventing a cross-revision structural contract. For example,
`Contract\ClientNotification` is a valid construction root under `2025-11-25`,
where the definition is a union, while `Record\ClientNotification` is valid
under `2026-07-28`, where it is an object. Each symbol is accepted only by the
catalogs where that kind is canonical.

A `$ref` alias that resolves transitively to an object receives its own nominal
record class without wrapping the referenced record. For example,
`Contract\ClientResult` is the 2025 union root and `Record\ClientResult` is the
2026 object-alias root hydrated directly with fields declared through `Result`.
Scalar, list, and mixed aliases remain native or internal. This is generated
nominal construction, not `class_alias`, a proxy, or a facade.

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

An abstract `Record` directly owns immutable field values, actual key presence,
and a revision-free declared-field mask captured during hydration. Concrete
record classes extend it; they do not wrap another generic-record object. The
mask records which instance keys were declared by the selected catalog without
retaining a revision identifier or schema service.

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
- A generated named getter reads only a field declared by the schema that
  hydrated that instance. If another revision removed the field, an identically
  named open-schema extension does not regain the removed field's typed meaning.
  For example, a 2026 `Tool` may preserve an opaque `execution` extension through
  `get('execution')`, while `getExecution()` returns null.
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

Canonical objects that permit additional properties retain instance-present
extension keys exactly. Removed standardized fields may therefore arrive as
opaque extensions when the newer canonical object remains open. Generic access
and serialization preserve them, but named getters do not interpret them using
the removed revision's schema. Adapter-owned projections omit removed
standardized fields from newer wire output instead of deliberately advertising
them as extensions.

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

Definition classes and contracts form the union of every still-supported
revision's public roster. Revision-exclusive symbols remain while any supported
catalog needs them. Removing a supported revision, and any symbols used only by
it, is an explicit package breaking change.

## Validation

Validation is always on. Every call to `fromArray()`, `fromValue()`, or
`fromJson()` validates against the complete selected MCP definition before
returning a record.

Requests are validated before dispatch, results before encoding, and declared
tool schemas at their existing Ability-owned boundary. There is no production
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

Compatibility tolerance is not a validation mode. A wire profile may apply a
direction-specific receiver rule only when the specification explicitly requires
it. Senders still produce the selected revision's complete canonical form. Each
receiver exception is sourced and tested; there is no general coercion or loose
validation switch.

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

Errors use a small package exception hierarchy. Removed DTO exception classes and
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

1. stores the existing Ability configuration and callbacks without requiring
   revision-specific changes from the Ability author;
2. projects the logical component independently through every supported schema;
3. supplies revision-required protocol fields, translates safe replacements,
   and omits removed standardized fields from newer wire output;
4. caches every successful immutable projection; and
5. records and logs the exact revision and reason for every failed projection.

A component is exposed only under revisions with a valid projection. Failure in
one revision does not remove it from revisions where it remains valid. Global
registration fails only when no supported revision can represent the component.
This preserves existing Ability behavior while keeping selected-revision wire
validation exact.

Because Adapter registration projects every component twice, an Adapter process
intentionally loads both complete revision catalogs during startup. A generic
package consumer selecting one revision still loads only that catalog. Adapter
startup memory and loaded-file cost are measured as part of the proposal.

Filtered list results are validated again when the final list-result record is
constructed.

### Inbound requests

MCP Adapter owns one minimal JSON decoder because the request flow must identify
an exact revision before a selected schema can validate the message.

The decoder:

1. lexically rejects integer tokens outside PHP's safe integer range before
   `json_decode()` can silently turn them into floats;
2. decodes raw JSON once with objects preserved as `stdClass`;
3. rejects malformed JSON, depth violations, invalid numbers, and unsupported
   batches;
4. extracts only the envelope data required for negotiation;
5. applies the exact negotiation rule for the request flow and selects a
   supported revision; and
6. passes the decoded value to the selected schema's `fromValue()` method.

One immutable request context supplies the exact revision, selected schema,
client capabilities and identity, and transport metadata before hydration. For
`2025-11-25`, initialization establishes the values later supplied by session
context. For `2026-07-28`, each request constructs them from its headers and
`_meta`. Handlers receive the same context contract without global mutable
revision state. The decoder does not duplicate schema validation.

### Handling and output

Handlers receive validated records and continue to own the same execution and
permission behavior as `trunk`. Existing Ability callbacks return the same
logical results. The selected wire profile projects them to the exact revision,
injects mandatory protocol-owned fields, and constructs the final result through
the selected schema.

One decoding/encoding orchestrator selects a function-only wire profile by exact
revision. A profile owns method availability, context construction, required
headers, lifecycle behavior, envelope transformations, and real transport
differences. Shared JSON parsing, validation, hydration, dispatch, and
serialization remain implemented once. Profiles use exact identifiers such as
`V2025_11_25` and `V2026_07_28`; there is no inheritance hierarchy or behavioral
revision label.

For existing Adapter capabilities, the `2026-07-28` profile supplies
`resultType: "complete"`, `ttlMs: 0`, and `cacheScope: "private"` where those
fields are required. It does not produce `input_required` unless Adapter
explicitly implements that optional product capability.

The generator derives canonical message availability from aggregate definitions
and method constants, keyed by sender direction and by request, notification, or
embedded-input kind. Adapter declares the handlers it implements. Only the
applicable request/notification availability and handler intersection is
callable for a selected revision. Embedded 2026 input requests never become
ordinary client-to-server RPCs. An old handler cannot resurrect a removed method
and an unimplemented canonical definition is not advertised.

HTTP- and STDIO-specific behavior remains in the existing transports. Required
2026 headers, envelopes, and negative behavior are implemented from the
official revision specification without adding unrelated Adapter features.

## Adapter public surface

The schema switch is total while the ordinary Ability surface remains
compatible:

- no production DTO imports;
- no DTO aliases, wrappers, proxies, `class_alias`, or dual returns;
- no Adapter-owned record substitutes;
- no compatibility mode; and
- no old DTO generator or DTO documentation in `php-mcp-schema`.

Existing Ability registration data, schemas, permission callbacks, execution
callbacks, and logical results do not require revision-specific rewrites.
Existing hook and filter names, ordering, and original arguments remain.
DTO-specific imports, constructors, factories, serialization methods, and typed
filter payload assumptions are intentional public breaks documented by the
migration guide.

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
and observability contracts remain unchanged unless an exact revision
requirement forces a narrow revision-bound change.

Deprecation is distinct from removal. Canonical deprecated definitions continue
to validate and already-implemented deprecated Adapter behavior continues to
work. Generated documentation records the exact deprecation. Adapter does not
add a deprecated optional capability merely because the schema package models
it. Only actual removal changes availability.

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
- Every addition, removal, kind change, field change, and method change is
  classified by the compatibility manifest.
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
- Each selected revision exposes only its canonical methods intersected with
  Adapter's implemented handlers.
- Existing logical Adapter capabilities work without Ability-author changes
  wherever the selected revision still defines or replaces them.
- `2025-11-25` counter-proposal and `2026-07-28` unsupported-version error flows
  match their exact specifications.
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
- inheritance-based encoder hierarchies or behavioral revision labels;
- runtime loading of canonical JSON;
- production validation dependencies;
- new MCP Adapter capabilities unrelated to the revision switch; and
- release work before the proposal is accepted.

Canonical identifiers are preserved verbatim even when an upstream definition
name contains a word that project-authored revision categories may not use, such
as `LegacyTitledEnumSchema`. The naming prohibition applies to project-authored
revision groupings, variables, classes, and profiles; it never renames canonical
schema definitions.

## Why this is future-proof enough

A future MCP revision adds one canonical source, one generated catalog, and one
exact-revision wire profile. It reuses existing logical records when their
public getter contracts remain compatible, adds new records only for genuinely
new named concepts, and makes removals explicit through availability maps.

The package does not promise that every future breaking schema can fit a minor
release. If a revision requires an incompatible getter contract, normal package
versioning applies. The important property is that breaking changes fail during
the generated compatibility audit and are localized to catalogs, availability,
projection, and wire profiles rather than mechanically duplicating the full
class tree or shifting failures to every consumer.

This proposal intentionally avoids speculative extension points. Future
requirements should change the design only when an actual canonical revision or
consumer demonstrates the need.

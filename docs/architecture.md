# Architecture

`php-mcp-schema` validates protocol values against an explicitly selected Model
Context Protocol (MCP) schema revision and hydrates them into immutable PHP
records. Each supported revision has its own generated catalog, while compatible
named definitions share record classes. This preserves exact wire behavior
without maintaining a parallel PHP class tree for every revision.

This document explains the package's runtime model and source boundaries. See
the [README](../README.md) for public API examples, the
[migration guide](MIGRATION.md) when replacing the former DTO API, and the
[contributor guide](../CONTRIBUTING.md) for development procedures.

## Runtime model

The package separates revision-specific protocol rules from the PHP objects
consumers use:

- **Revision catalogs** describe the definitions and directional message
  availability for one exact MCP revision.
- **Shared records** represent named MCP objects such as `Tool`, `TextContent`,
  and `CallToolResult` wherever their public PHP contracts are compatible.

```text
Pinned schema.json files
          |
       Generator
          +-- exact-revision catalogs
          +-- shared records, contracts, and value constants

Input -> selected Schema -> validation -> hydration -> immutable Record -> JSON
```

Revision differences remain in the catalogs and the validation performed by a
selected `Schema`. They do not require consumers to choose between separate
revision namespaces.

The selected schema is the authority for both validation and construction. The
resulting record retains its finalized wire keys, field presence, and values. It
does not retain the schema service or a revision identifier.

## Exact revision selection

Consumers obtain a `Schema` through `Schemas::create()->forVersion()`. The
provider loads and caches a catalog lazily for each selected revision. Supported
identifiers are exposed as constants on `Schemas` and through
`Schemas::supportedVersions()`.

Selection is exact. An unknown identifier throws
`UnsupportedRevisionException`; the runtime never treats revisions as ranges and
never falls back to the newest catalog.

The package's public record and contract roster is the union of the types needed
by all supported revisions. The existence of a PHP symbol therefore does not
mean that it is valid under every selected schema. Constructing a type that is
not available in the selected revision throws `UnavailableTypeException`.

The same rule applies to protocol methods. A selected schema exposes directional
availability checks for client requests, client notifications, server requests,
server notifications, and embedded input requests. These maps answer whether a
message exists in the canonical revision; an application must still intersect
that result with the handlers and capabilities it implements.

## Records, contracts, and values

The public generated surface has three roles:

- `WP\McpSchema\Record` contains concrete immutable named objects.
- `WP\McpSchema\Contract` contains useful union construction roots.
- `WP\McpSchema\Value` contains constants for canonical enum-like scalar values.

Compatible same-named object definitions share one record class. Generated
getters cover the compatible fields used by any supported revision, while each
record instance retains a mask of the fields declared by the catalog that
created it. A getter returns `null` when its field is not declared or is omitted
for that instance; it does not reinterpret an open-schema extension as a field
removed by another revision.

When a canonical name changes kind, the runtime uses kind-specific public
symbols instead of inventing a false common contract. For example, a definition
that is a union in one revision and an object in another can have a construction
root under `Contract` for the first revision and a concrete symbol under
`Record` for the second. Exact-revision availability determines which root may
be constructed.

Object aliases receive nominal record classes and hydrate directly from the
referenced fields. Scalar, list, and mixed aliases remain native values or
internal implementation details. Contracts are generated only for unions that
provide a useful public object boundary, not for every structural union in the
canonical schemas.

Records also expose generic field access:

- `has()` distinguishes an omitted field from a field explicitly containing
  `null`.
- `get()` reads a declared field or an instance-present extension field. An
  absent unknown field throws `UnknownFieldException`.
- `jsonSerialize()` returns the complete wire representation as a defensive
  `stdClass`.

Named nested objects become records. Anonymous JSON objects remain `stdClass`
instances, and JSON arrays remain PHP lists. Mutable native values returned by a
record are copied so callers cannot mutate the record indirectly.

## Validation and wire identity

`Schema` provides three construction boundaries for different input sources:

- `fromArray()` accepts programmatic PHP arrays and uses schema context to
  interpret ambiguous empty arrays.
- `fromValue()` accepts already-decoded values, including `stdClass`, lists, and
  existing records.
- `fromJson()` decodes raw JSON while preserving object/list identity and
  numeric-string object keys.

All three paths converge on the same validator and hydrator. Validation is
always enabled and completes before a record is returned. Existing records used
as input are serialized defensively and validated again, so passing a record
from one revision into another revision never bypasses the target schema.

The interpreter implements the JSON Schema vocabulary and combinations required
by the pinned MCP documents. It is not a general-purpose JSON Schema library.
Among other canonical constraints, construction preserves or enforces:

- JSON object versus list identity, including `{}` versus `[]`;
- omitted fields versus fields explicitly containing `null`;
- required fields, closed-object fields, and union membership;
- native integer bounds and rejection of non-finite numbers;
- valid UTF-8 and JSON escapes;
- acyclic, serializable input within the shared depth boundary; and
- instance-present extension keys where the canonical object permits them.

Validation failures use the package exception hierarchy and include value paths
where applicable. There is no unchecked public constructor, alternate record
representation, or validation-disabled mode.

## Generated and handwritten source ownership

The canonical inputs are commit-pinned MCP `schema.json` files under
`resources/schema/`. Their SHA-256 digests are reviewed and recorded by the
generator. Normal generation is offline and rejects unexpected source changes.

Generated PHP is restricted to these paths:

```text
src/Record/
src/Contract/
src/Value/
src/Internal/Catalog/
src/Internal/TypeRegistry.php
```

Public generated symbols use domain-oriented namespaces. Generated runtime
metadata remains internal. Handwritten revision selection, validation,
hydration, immutable storage, JSON decoding, and exceptions live outside the
generated paths.

Generation completes in a staging tree and then replaces only the allowlisted
paths. This prevents generation from deleting handwritten siblings such as
`src/Record.php`, `src/Schema.php`, and other files under `src/Internal/`.

The generator audits each new revision against every still-supported revision.
A reviewed compatibility classification accounts for structural changes,
same-name kind changes, getter compatibility, and directional message
availability. Unknown schema constructs and unclassified compatibility changes
fail generation instead of being ignored.

AJV is a development-only conformance oracle for the canonical documents and
structural fixtures. Production Composer installations do not include AJV,
Node.js, the canonical JSON files, or any other runtime dependency. See the
[generator guide](../generator/README.md) for the generation workflow and the
[contributor guide](../CONTRIBUTING.md) for required checks.

## Application boundary

This package owns MCP schema shape, validation, hydration, serialization, type
availability, and directional message availability. It does not implement an
MCP client, server, transport, lifecycle, session, capability policy, handler
registry, permissions, or execution system.

Applications select an exact revision using their negotiation or request
context, construct incoming and outgoing protocol records through the selected
schema, and map package exceptions to their own protocol or transport errors.
They also decide which canonically available methods they implement and
advertise.

The runtime validates the MCP record containing a tool's `inputSchema` or
`outputSchema`; it does not become the validator for arbitrary application data
described by those user-authored schemas. That validation remains the
responsibility of the application or tool system.

## Adding or removing a revision

Adding a revision requires a commit-pinned canonical source, a reviewed digest,
and a compatibility classification against every revision that remains
supported. The generator reuses existing records when their public getter
contracts remain compatible, adds symbols for new named concepts, and records
exact type and message availability.

An incompatible same-named getter contract fails generation for an explicit API
decision. The package does not silently widen the getter to `mixed` or hide the
change behind a compatibility facade. Normal package versioning applies when a
new revision requires a public API break.

Removing a revision is also potentially breaking. Records, contracts, or values
used only by that revision may leave the public roster, and consumers can no
longer select its catalog. Revision support therefore changes through deliberate
package releases rather than runtime version approximation.

## Design consequences

This architecture keeps one consumer-facing record model while preserving exact
revision validation. It avoids mechanically duplicating the complete class tree
for each breaking MCP revision and localizes most protocol differences to
generated catalogs and availability metadata.

The tradeoff is explicit revision context: consumers must select a schema before
construction, and a public symbol can exist even when it is unavailable under
that schema. Cross-revision reuse must pass through the target schema, and some
future canonical changes may still require a normal breaking package release.

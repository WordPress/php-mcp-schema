# MCP Adapter handoff: retain one negotiated schema

## Objective

Update MCP Adapter so protocol negotiation remains Adapter-owned, the resulting exact schema catalog is selected once, and schema-aware code reuses that catalog. Preserve exact PHPStan types only where wire shapes actually differ; keep normal handlers revision-neutral.

```text
negotiate protocol version
    -> map it to an exact schema revision
    -> Schemas::revision()
    -> retain the catalog in McpProtocolContext
    -> reuse it at validation and serialization boundaries
```

Do not add ambient current-version state or call concrete `Schemas::v...()` methods throughout request handling.

## Schema contract available to the Adapter

The descriptor-backed package generates an exportable union of all supported catalogs:

```php
/**
 * @phpstan-type SupportedRevisionSchema V20251125Schema|V20260728Schema
 */
final class Schemas
{
    /** @return SupportedRevisionSchema */
    public static function revision(string $revision): RevisionSchema;
}
```

Consumers should import `SupportedRevisionSchema` instead of duplicating the union. The generator updates the owning alias whenever it adds a revision. The native return type remains the small PHP 7.4-compatible `RevisionSchema` interface; PHPStan sees the finite union.

## Ownership boundary

`php-mcp-schema` owns exact revision shapes and constants, validation, recursive hydration, unknown-field preservation, omitted versus present-null behavior, JSON object/list identity, and record serialization.

MCP Adapter continues to own protocol negotiation and protocol-to-schema mapping, HTTP/session agreement, supported-method and MRTR policy, WordPress Ability normalization, execution classification, downgrade policy, and observability.

Capabilities do not select a revision. The exact negotiated protocol context does.

## Recommended Adapter shape

### 1. Retain the selected catalog in `McpProtocolContext`

```php
use WP\McpSchema\Contract\RevisionSchema;
use WP\McpSchema\Schemas;

/**
 * @phpstan-import-type SupportedRevisionSchema from Schemas
 */
final class McpProtocolContext {

	private string $protocol_version;

	/** @var SupportedRevisionSchema */
	private RevisionSchema $schema;

	/**
	 * @param string                   $protocol_version Negotiated protocol version.
	 * @param SupportedRevisionSchema $schema Selected exact schema catalog.
	 */
	public function __construct( string $protocol_version, RevisionSchema $schema ) {
		$this->protocol_version = $protocol_version;
		$this->schema           = $schema;
	}

	/** @return SupportedRevisionSchema */
	public function get_schema(): RevisionSchema {
		return $this->schema;
	}

	public function get_schema_revision(): string {
		return $this->schema->revision();
	}
}
```

Perform the existing protocol-to-schema mapping once while constructing the context:

```php
$schema_revision = $this->map_protocol_to_schema_revision( $protocol_version );
$schema          = Schemas::revision( $schema_revision );
$context         = new McpProtocolContext( $protocol_version, $schema );
```

The mapping remains explicit in the Adapter. Do not add `Schemas::forProtocolVersion()` to the schema package. If changing the constructor would break a public Adapter API, use a named factory and retain the old constructor only as a documented compatibility wrapper.

### 2. Keep common handlers revision-neutral

Handlers should continue producing stable Adapter-owned values such as `ToolCallOutcome`. They should not construct revision records or inspect generated record fields merely to pass data between request phases.

```php
$outcome = $tools_handler->call_tool_outcome( $params, $request_id );
$result  = ToolCallResultEncoder::encode( $protocol_context, $outcome );
```

### 3. Narrow once where wire shapes differ

PHPStan cannot turn runtime negotiation into one globally concrete compile-time type. Narrow the generated union at the serialization boundary, then pass the concrete catalog into a revision-specific private method:

```php
public static function encode(
	McpProtocolContext $context,
	ToolCallOutcome $outcome
): array {
	$schema = $context->get_schema();

	if ( $schema instanceof V20251125Schema ) {
		return self::encode_2025_11_25( $schema, $outcome );
	}

	if ( $schema instanceof V20260728Schema ) {
		return self::encode_2026_07_28( $schema, $outcome );
	}

	throw new \LogicException( 'Unsupported MCP schema implementation.' );
}
```

Each private method receives a concrete catalog, so its generated accessor and imported wire aliases remain exact:

```php
private static function encode_2026_07_28(
	V20260728Schema $schema,
	ToolCallOutcome $outcome
): array {
	$data = array(
		'resultType' => 'complete',
		'content'    => $outcome->get_content(),
		'isError'    => $outcome->is_error(),
	);

	if ( $outcome->has_structured_content() ) {
		$data['structuredContent'] = $outcome->get_structured_content();
	}

	return $schema
		->callToolResult()
		->fromArray( $data )
		->toWireArray();
}
```

The legacy method omits `resultType`. Let its descriptor hydrate content blocks and enforce the legacy `structuredContent` object/map contract. Use `toWireArray()`, not `toArray()`, at the Adapter boundary so nested empty JSON objects remain objects.

Keep one understandable encoder with two typed private methods unless additional operations demonstrate a real need for separate codec classes. Do not rebuild the deleted codec factory/interface hierarchy pre-emptively.

### 4. Preserve the encoding-error contract

Descriptor failures throw `WP\McpSchema\Runtime\ValidationException`, which reports the revision, logical type, and wire path. Catch it in `RequestRouter`, or translate it inside the encoder to the existing contract:

```php
try {
	return $schema->callToolResult()->fromArray( $data )->toWireArray();
} catch ( ValidationException $exception ) {
	throw new \UnexpectedValueException(
		$exception->getMessage(),
		0,
		$exception
	);
}
```

Do not let a schema exception bypass existing logging, observability, or JSON-RPC error classification.

## Important migration constraints

The descriptor-backed branch is not a drop-in Composer update. It removes concrete DTO trees currently referenced by MCP Adapter production files, public hooks, properties, return types, validators, and compatibility wrappers.

Before replacing the dependency:

1. Inventory every `WP\McpSchema\V202...` import, concrete DTO property, return type, `instanceof`, and public hook contract.
2. Decide which public Adapter contracts can break and which require a temporary compatibility path.
3. Migrate stored schema values to `Record<TWire, TFields>` using generated importable aliases.
4. Keep generic wire arrays confined to dynamic boundaries; do not replace domain objects with arrays throughout the Adapter.
5. Remove concrete DTO code only after all callers and tests have moved.

The direct `ToolsHandler::call_tool()` compatibility wrapper is especially important: the descriptor package cannot return the old concrete legacy DTO class. Treat that as an explicit breaking-API decision, not an incidental encoder refactor.

### Empty object/list decision

For schema-defined object/map fields, the descriptor supplies the expected wire category. For `unknown`, PHP cannot infer whether an empty array means `{}` or `[]`; pass `new \stdClass()` when caller intent is an empty object.

The current legacy Adapter encoder rejects an empty array supplied as `structuredContent`. A descriptor-backed legacy map can interpret an empty value as an object and emit `{}`. Decide and test whether to preserve the prior rejection or adopt descriptor-driven normalization. Do not let this behavior change accidentally.

## Suggested implementation order

1. Update the Adapter's experimental Composer path dependency to the descriptor-backed schema branch and regenerate its lock file.
2. Import `SupportedRevisionSchema` in `McpProtocolContext` and retain the selected catalog there.
3. Update HTTP, stdio, custom transport, and test context construction so schema selection happens once per context.
4. Audit and migrate concrete DTO coupling before deleting compatibility code.
5. Replace `ToolCallResultEncoder` DTO construction with one exhaustive schema narrowing and two typed private methods.
6. Remove manual nested content hydration, global list-to-object coercion, and explicit-null repair now owned by the descriptor runtime.
7. Update router tests to prove negotiation selects once and downstream code reuses the catalog.
8. Run full Adapter gates and inspect the final diff for obsolete concrete DTO references.

## Required tests

- Each supported protocol version maps to the intended schema revision.
- The selected catalog survives through `McpProtocolContext` without reselection.
- Unknown protocol versions fail before handler execution.
- Both catalogs coexist and encode in one PHP process.
- Legacy and modern `tools/call` output match exact wire fixtures.
- Omitted and explicit-null `structuredContent` remain distinct.
- Empty object/list identity survives the final JSON response.
- Non-empty lists are rejected where a legacy object/map is required.
- Modern MRTR continuation fields remain rejected before ability execution.
- PHPStan narrows `SupportedRevisionSchema` in both encoder branches.
- Production handlers do not call concrete `Schemas::v...()` methods merely to recover static types.

## Verification gates

Run the Adapter's normal PHP gates from its active checkout:

```bash
npm run test:php
npm run lint:php
npm run lint:php:stan
composer validate --strict
git diff --check
```

The default lint and PHPStan commands may require `wp-env` to be running separately from the test environment. If PHPCS output is disputed, rerun it with `--no-cache`.

For the schema dependency itself, require:

```bash
composer check
cd generator && npm test
cd generator && npm run lint
cd generator && npm run generate:check
git diff --check
```

## Completion criteria

The migration is complete only when negotiation and mapping remain explicit and Adapter-owned; one selected catalog is retained in context; common handlers stay revision-neutral; revision differences are bounded to validation/serialization; exact records produce final wire output; removed concrete DTO APIs are no longer required; and all gates pass.

# Design Decisions

Key architectural decisions made during development.

## TypeScript AST over JSON Schema

The generator parses the TypeScript schema directly using ts-morph instead of the JSON Schema.

**Why TypeScript:**

- **Single source of truth** - TypeScript is the authoritative MCP schema; JSON Schema is derived from it
- **Richer type information** - Preserves `extends` relationships, JSDoc tags (`@category`, `@internal`), and inline object types
- **Inheritance chains** - TypeScript `extends` maps directly to PHP class inheritance; JSON Schema flattens these into `allOf`
- **Domain classification** - `@category` JSDoc tags enable accurate domain/subdomain organization
- **Union semantics** - TypeScript distinguishes string literal unions (enums) from object type unions (polymorphic interfaces)
- **Constants extraction** - Exported const declarations are preserved with their values

**JSON Schema limitations:**

- Flattens inheritance into `allOf` compositions (loses hierarchy)
- No JSDoc metadata (`@category` tags unavailable)
- Inline objects become anonymous `$defs` entries
- Union types lose semantic context
- Constants are not represented

**Trade-off:** Requires ts-morph dependency for AST parsing, but produces more accurate PHP output.

## PHP 7.4 Compatibility

The generator targets PHP 7.4 for maximum compatibility:

- **No native enums** - Uses class-based enums with constants
- **No union types** - Uses PHPDoc annotations instead
- **No `mixed` type** - Leaves properties untyped when needed
- **No `readonly`** - Uses `protected` properties with getters
- **Typed properties** - Leverages PHP 7.4's typed property support

## True Class Inheritance

PHP DTOs mirror the TypeScript `extends` hierarchy instead of flattening:

```php
// Generated inheritance chain
class Request extends AbstractDataTransferObject { ... }
class JSONRPCRequest extends Request { ... }
class InitializeRequest extends JSONRPCRequest { ... }
```

**Benefits:**

- Eliminates code duplication
- Enables `instanceof` checks
- Mirrors TypeScript structure

**Implementation details:**

- Properties are `protected` (not `private`) for child access
- `fromArray()` returns `static` for late static binding
- `toArray()` merges with `parent::toArray()`
- Constructors call `parent::__construct()`
- Topological sort ensures parents generate before children

## Property Type Narrowing

When child types narrow a property type, we avoid LSP violations:

```php
// Parent
class Request {
    protected ?array $params;
}

// Child with narrowed type uses separate property
class InitializeRequest extends Request {
    protected ?InitializeRequestParams $typedParams;

    public function getTypedParams(): ?InitializeRequestParams { ... }
}
```

## Synthetic DTOs

Inline object types become separate DTO classes:

```typescript
// TypeScript
interface Parent {
    config: { enabled: boolean; timeout: number };
}
```

```php
// Generated PHP
class Parent {
    protected ParentConfig $config;
}

class ParentConfig {
    protected bool $enabled;
    protected int $timeout;
}
```

Naming convention: `{ParentName}{PropertyName}` (PascalCase).

## Union Type Handling

### String Literal Unions → Enums

```typescript
type LoggingLevel = "debug" | "info" | "warning" | "error";
```

```php
class LoggingLevel extends AbstractEnum {
    public const DEBUG = 'debug';
    public const INFO = 'info';
    // ...
    public static function debug(): self { ... }
}
```

### TypeScript Enums → Numeric Enum Classes

```typescript
enum ErrorCode {
    ParseError = -32700,
    InvalidRequest = -32600,
}
```

```php
final class ErrorCode {
    public const ParseError = -32700;
    public const InvalidRequest = -32600;

    public static function values(): array { ... }
    public static function names(): array { ... }
    public static function isValid(int $code): bool { ... }
}
```

### Object Type Unions → Interfaces + Factories

```typescript
type ContentBlock = TextContent | ImageContent | AudioContent;
```

```php
interface ContentBlockInterface {
    public function toArray(): array;
}

class ContentBlockFactory {
    public static function fromArray(array $data): ContentBlockInterface {
        switch ($data['type'] ?? null) {
            case 'text': return TextContent::fromArray($data);
            case 'image': return ImageContent::fromArray($data);
            // ...
        }
    }
}
```

## Type Alias Wrappers

Type aliases that are referenced in unions need concrete classes:

```typescript
// TypeScript
type EmptyResult = Result;
type ServerResult = EmptyResult | InitializeResult | ...;
```

**Problem:** `EmptyResult` is just an alias - no class exists.

**Solution:** Generate wrapper classes:

```php
class EmptyResult extends Result implements ServerResultInterface, ClientResultInterface
{
    // Inherits everything from Result
    // Exists solely for union interface implementation
}
```

This enables:

- Factory routing to `EmptyResult`
- Type-safe union handling
- Documentation accuracy

## Intersection Type Wrappers

Intersection types combine properties from multiple interfaces:

```typescript
// TypeScript
type GetTaskResult = Result & Task;
type ClientResult = EmptyResult | GetTaskResult | ...;
```

**Problem:** PHP only supports single inheritance.

**Solution:** Generate wrapper classes that:

1. Extend the most "generic" type (typically `Result`)
2. Merge properties from other intersected types
3. Implement all union interfaces

```php
class GetTaskResult extends Result implements ClientResultInterface, ServerResultInterface
{
    // Inherited from Result: _meta

    // Merged from Task:
    protected string $taskId;
    protected string $status;
    protected string $createdAt;
    // ...

    public function __construct(...) {
        parent::__construct(...);
        $this->taskId = $taskId;
        // ...
    }
}
```

**Base type selection heuristic:** If `Result` is in the intersection, it's always the base type (most generic). Otherwise, use the first type.

## Protocol Constants

Exported constants from the schema become a dedicated class:

```typescript
// TypeScript
export const LATEST_PROTOCOL_VERSION = "2026-07-28";
export const JSONRPC_VERSION = "2.0";
export const PARSE_ERROR = -32700;
```

```php
final class McpConstants
{
    public const LATEST_PROTOCOL_VERSION = '2026-07-28';
    public const JSONRPC_VERSION = '2.0';
    public const PARSE_ERROR = -32700;

    public static function getErrorCodes(): array { ... }
    public static function isValidErrorCode(int $code): bool { ... }
    public static function getErrorCodeName(int $code): ?string { ... }
    public static function isStandardJsonRpcError(int $code): bool { ... }
}
```

**Benefits:**

- Centralized protocol configuration
- Type-safe error code validation
- Consistent with TypeScript schema

## Discriminator Detection

Factories detect discriminator fields with this priority:

1. `method` - For JSON-RPC requests/notifications
2. `type` - For content blocks, schemas
3. `kind` - For alternative type indicators
4. `role` - For message roles

If no common discriminator exists, no factory is generated.

### Nested Union Handling

Unions can contain other unions as members:

```typescript
type ClientResult = InitializeResult | ResourceResult | ...;
type ResourceResult = ReadResourceResult | ListResourcesResult | ...;
```

Factories handle this by recursively extracting leaf interfaces to detect discriminators across the entire hierarchy.

## Auto-Hydration

`fromArray()` automatically hydrates nested objects:

```php
public static function fromArray(array $data): static {
    return new static(
        // Nested DTO auto-hydrated
        isset($data['config'])
            ? Config::fromArray($data['config'])
            : null,
        // Union type uses factory
        isset($data['content'])
            ? ContentBlockFactory::fromArray($data['content'])
            : null
    );
}
```

`toArray()` recursively serializes:

```php
public function toArray(): array {
    return array_merge(parent::toArray(), [
        'config' => $this->config?->toArray(),
        'content' => $this->content?->toArray(),
    ]);
}
```

## Domain Classification

Types are organized by MCP domain using `@category` JSDoc tags:

| @category | Domain | Subdomain |
|-----------|--------|-----------|
| `Tools` | Server | Tools |
| `Resources` | Server | Resources |
| `Prompts` | Server | Prompts |
| `Sampling` | Client | Sampling |
| `Elicitation` | Client | Elicitation |
| `Tasks` | Client | Tasks |
| `JSON-RPC` | Common | JsonRpc |
| `Protocol` | Common | Protocol |

Fallback: Name-based pattern matching (e.g., `Tool*` → Server/Tools).

## Integer vs Float

TypeScript `number` maps to PHP `int` or `float` based on context:

**Integer patterns:** `*Id`, `*Length`, `*Count`, `*Index`, `*Items`, `*Size`

```php
protected int $maxTokens;    // count pattern
protected float $temperature; // no pattern match
```

## Required Field Validation

DTOs use the `ValidatesRequiredFields` trait:

```php
use ValidatesRequiredFields;

public function __construct(string $method, ?array $params = null) {
    $this->validateRequired(['method' => $method]);
    // ...
}
```

## Union Membership

DTOs implement their union interfaces:

```php
class TextContent extends AbstractDataTransferObject
    implements ContentBlockInterface, SamplingMessageContentBlockInterface
{
    public const DISCRIMINATOR_FIELD = 'type';
    public const DISCRIMINATOR_VALUE = 'text';
}
```

Constants enable runtime type inspection without reflection.

## Version Tracking

The generator tracks schema history for `@since` annotations:

```php
/**
 * @since 2024-11-05
 * @mcp-version 2025-11-25
 */
class CallToolRequest extends Request
{
    /**
     * @since 2024-11-05
     */
    protected string $name;

    /**
     * @since 2025-03-26
     */
    protected ?array $arguments;
}
```

**Implementation:**

1. Fetches all schema versions from GitHub
2. Parses each version to extract definitions
3. Compares versions to detect when each definition/property was introduced
4. Annotates generated code with introduction version

**Benefits:**

- Consumers know API stability
- Migration guidance between versions
- Documentation of schema evolution

## typeof CONSTANT Resolution

TypeScript uses `typeof CONSTANT_NAME` to reference constant types:

```typescript
interface JSONRPCRequest {
    jsonrpc: typeof JSONRPC_VERSION;  // resolves to "2.0"
}
```

The generator resolves these to literal types using a constants map built during parsing.

## Skill File Generation

The generator produces Claude Code skill files for progressive schema discovery:

**Why skill files:**

- Reduce context size for LLM interactions
- Enable progressive discovery (index → domain → type details)
- Provide programmatic access via JSON
- Include search utilities for quick lookups

**Structure:**

- `SKILL.md` - Entry point with navigation
- `reference/*.md` - Domain-organized documentation
- `data/*.json` - Programmatic access (index ~2KB, domains ~20KB each)
- `scripts/*.sh` - Search utilities using jq

**Design principles:**

- Lightweight index for initial discovery
- Split by domain to limit context loading
- JSON for LLM tool use
- Shell scripts for quick human lookups

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

**JSON Schema limitations:**

- Flattens inheritance into `allOf` compositions (loses hierarchy)
- No JSDoc metadata (`@category` tags unavailable)
- Inline objects become anonymous `$defs` entries
- Union types lose semantic context

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

## Discriminator Detection

Factories detect discriminator fields with this priority:

1. `method` - For JSON-RPC requests/notifications
2. `type` - For content blocks, schemas
3. `kind` - For alternative type indicators
4. `role` - For message roles

If no common discriminator exists, no factory is generated.

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

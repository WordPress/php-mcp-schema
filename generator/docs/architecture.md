# Architecture

## Directory Structure

```
generator/
├── src/
│   ├── index.ts              # Main entry point & orchestration
│   ├── cli/
│   │   └── index.ts          # Commander.js CLI interface
│   ├── config/
│   │   └── index.ts          # Configuration management
│   ├── types/
│   │   └── index.ts          # TypeScript type definitions
│   ├── fetcher/
│   │   └── index.ts          # Schema fetching with caching
│   ├── parser/
│   │   └── index.ts          # ts-morph AST parsing
│   ├── extractors/
│   │   ├── index.ts          # Extractor exports
│   │   └── synthetic-dto.ts  # Inline object type extraction
│   ├── generators/
│   │   ├── index.ts          # Generator exports
│   │   ├── domain-classifier.ts   # Type domain/subdomain mapping
│   │   ├── type-mapper.ts         # TypeScript → PHP type mapping
│   │   ├── type-resolver.ts       # Type reference resolution
│   │   ├── inheritance-graph.ts   # Inheritance tracking
│   │   ├── dto.ts                 # DTO class generation
│   │   ├── enum.ts                # Enum class generation
│   │   ├── union.ts               # Union interface generation
│   │   ├── factory.ts             # Factory class generation
│   │   ├── builder.ts             # Builder class generation
│   │   └── contract.ts            # Contract interface generation
│   ├── writers/
│   │   └── index.ts          # File writing & base classes
│   └── version-tracker/
│       └── index.ts          # Schema version tracking
├── config/                   # Version-specific configs
└── dist/                     # Compiled output
```

## Generation Pipeline

```
┌─────────────────────────────────────────────────────────────────┐
│                        INPUT: schema.ts                         │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 1. FETCH SCHEMA                                                 │
│    fetchSchema() / fetchSchemaFresh()                           │
│    - GitHub raw content API                                     │
│    - Cache: .cache/schemas/{repo}_{version}_schema.ts           │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 2. PARSE SCHEMA                                                 │
│    parseSchema() using ts-morph                                 │
│    - Extracts interfaces, type aliases, enums                   │
│    - Handles JSDoc tags (@category, @internal)                  │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 3. EXTRACT SYNTHETIC TYPES                                      │
│    SyntheticDtoExtractor.extract()                              │
│    - Converts inline objects: { foo: string } → synthetic DTOs  │
│    - Recursive extraction for nested objects                    │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 4. BUILD UNION MEMBERSHIP MAP                                   │
│    - Maps DTOs to their union interfaces                        │
│    - Detects discriminator fields and values                    │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 5. BUILD INHERITANCE GRAPH                                      │
│    buildInheritanceGraph()                                      │
│    - Parent-child relationships from extends                    │
│    - Topological sort for generation order                      │
│    - Property classification (own/inherited/narrowed)           │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 6. CLASSIFY DOMAINS                                             │
│    DomainClassifier.classify()                                  │
│    - @category tags → domain/subdomain                          │
│    - Fallback: name-based pattern matching                      │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 7. GENERATE PHP CODE                                            │
│    ├── Base Classes (AbstractDataTransferObject, AbstractEnum)  │
│    ├── DTOs (from interfaces)                                   │
│    ├── Enums (from string literal unions)                       │
│    ├── Union Interfaces (from object type unions)               │
│    ├── Factories (for discriminated unions)                     │
│    ├── Builders (optional, fluent construction)                 │
│    └── Contracts (marker interfaces)                            │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 8. WRITE FILES                                                  │
│    FileWriter.writeFiles()                                      │
│    - Directory structure by domain/subdomain                    │
│    - Dry-run mode support                                       │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│                   OUTPUT: PHP files in src/                     │
└─────────────────────────────────────────────────────────────────┘
```

## Module Responsibilities

### Fetcher (`fetcher/index.ts`)

Retrieves the MCP TypeScript schema from GitHub with local caching.

- `fetchSchema()` - Uses cache if available
- `fetchSchemaFresh()` - Bypasses cache
- `clearCache()` - Removes cached schemas

### Parser (`parser/index.ts`)

Uses ts-morph for TypeScript AST parsing.

- `parseSchema()` - Parse TypeScript content
- `extractInterfaces()` - Get interface declarations
- `extractTypeAliases()` - Get type aliases
- `resolveInheritance()` - Flatten inheritance chain

### Extractors (`extractors/`)

**SyntheticDtoExtractor** - Handles inline object types:

```typescript
// Input: property: { nested: string; value: number }
// Output: Creates ParentPropertyName interface
```

### Generators (`generators/`)

**DtoGenerator** - PHP DTO classes with:
- Constructor with validation
- `fromArray()` static factory (auto-hydrates nested objects)
- `toArray()` serialization (recursive)
- Proper inheritance (`extends`)

**EnumGenerator** - Class-based enums:
- Constants for each value
- Static factory methods
- `values()` method

**UnionGenerator** - Marker interfaces:
- Interface per union type
- DTOs implement their union interfaces

**FactoryGenerator** - Discriminator-based routing:
- Detects discriminator field (`method`, `type`, `kind`, `role`)
- Switch statement routing to concrete types
- Returns `null` if no discriminator detected

**BuilderGenerator** - Fluent builders:
- `withPropertyName()` setters
- `build()` returns DTO

**ContractGenerator** - Marker interfaces:
- `WithArrayTransformation`
- `ResultContract`, `RequestContract`, etc.

### Type Mapping (`generators/type-mapper.ts`)

TypeScript to PHP type conversion:

| TypeScript | PHP |
|------------|-----|
| `string` | `string` |
| `number` | `int` or `float` (context-aware) |
| `boolean` | `bool` |
| `null` | `null` |
| `Type[]` | `array` with PHPDoc |
| `Type \| null` | `?Type` |
| inline object | Synthetic DTO |

Integer detection patterns: `*Id`, `*Length`, `*Count`, `*Index`, `*Items`

### Domain Classifier (`generators/domain-classifier.ts`)

Maps `@category` tags to PHP namespaces:

| Category | Domain | Subdomain |
|----------|--------|-----------|
| `Tools` | Server | Tools |
| `Resources` | Server | Resources |
| `Sampling` | Client | Sampling |
| `JSON-RPC` | Common | JsonRpc |

### Inheritance Graph (`generators/inheritance-graph.ts`)

Manages TypeScript `extends` relationships:

- Builds parent-child maps
- Provides topological sort (parents before children)
- Classifies properties as own/inherited/narrowed

### Writers (`writers/index.ts`)

File output with organization:

- DTOs: `Domain/Subdomain/ClassName.php`
- Others: `Domain/Subdomain/Type/ClassName.php`
- Generates base classes (`AbstractDataTransferObject`, `AbstractEnum`, `ValidatesRequiredFields` trait)

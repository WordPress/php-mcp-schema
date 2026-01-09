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
│   │   ├── index.ts          # TypeScript type definitions
│   │   └── skill-types.ts    # Skill generation types
│   ├── fetcher/
│   │   └── index.ts          # Schema fetching with caching
│   ├── parser/
│   │   └── index.ts          # ts-morph AST parsing
│   ├── extractors/
│   │   ├── index.ts          # Extractor exports
│   │   └── synthetic-dto.ts  # Inline object type extraction
│   ├── generators/
│   │   ├── index.ts               # Generator exports
│   │   ├── domain-classifier.ts   # Type domain/subdomain mapping
│   │   ├── type-mapper.ts         # TypeScript → PHP type mapping
│   │   ├── type-resolver.ts       # Type reference resolution
│   │   ├── inheritance-graph.ts   # Inheritance tracking
│   │   ├── dto.ts                 # DTO class generation
│   │   ├── enum.ts                # String literal enum generation
│   │   ├── numeric-enum.ts        # TypeScript numeric enum generation
│   │   ├── constants.ts           # Protocol constants class generation
│   │   ├── union.ts               # Union interface generation
│   │   ├── factory.ts             # Factory class generation
│   │   ├── builder.ts             # Builder class generation
│   │   ├── contract.ts            # Contract interface generation
│   │   ├── type-alias-wrapper.ts  # Type alias wrapper generation
│   │   ├── intersection-type-wrapper.ts  # Intersection type wrapper generation
│   │   ├── schema-map.ts          # JSON schema map generation
│   │   ├── skill-generator.ts     # Claude Code skill file generation
│   │   └── skill-markdown.ts      # Skill markdown helpers
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
│    - Extracts interfaces, type aliases, enums, constants        │
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
│    - Handles nested unions recursively                          │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 5. BUILD VERSION TRACKER                                        │
│    buildVersionTracker()                                        │
│    - Fetches historical schema versions                         │
│    - Tracks when definitions/properties were introduced         │
│    - Annotates @since tags in generated code                    │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 6. BUILD INHERITANCE GRAPH                                      │
│    buildInheritanceGraph()                                      │
│    - Parent-child relationships from extends                    │
│    - Topological sort for generation order                      │
│    - Property classification (own/inherited/narrowed)           │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 7. CLASSIFY DOMAINS                                             │
│    DomainClassifier.classify()                                  │
│    - @category tags → domain/subdomain                          │
│    - Fallback: name-based pattern matching                      │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 8. GENERATE PHP CODE                                            │
│    ├── Base Classes (AbstractDataTransferObject, AbstractEnum)  │
│    ├── Constants Class (McpConstants)                           │
│    ├── DTOs (from interfaces)                                   │
│    ├── String Enums (from string literal unions)                │
│    ├── Numeric Enums (from TypeScript enums)                    │
│    ├── Union Interfaces (from object type unions)               │
│    ├── Factories (for discriminated unions)                     │
│    ├── Type Alias Wrappers (for union-referenced aliases)       │
│    ├── Intersection Type Wrappers (for A & B types)             │
│    ├── Builders (optional, fluent construction)                 │
│    └── Contracts (marker interfaces)                            │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 9. GENERATE SKILL FILES                                         │
│    SkillGenerator.generateAll()                                 │
│    - SKILL.md entry point                                       │
│    - Domain reference markdown files                            │
│    - JSON data files for programmatic access                    │
│    - Shell scripts for searching                                │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ 10. WRITE FILES                                                 │
│    FileWriter.writeFiles()                                      │
│    - Directory structure by domain/subdomain                    │
│    - Dry-run mode support                                       │
└─────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│                   OUTPUT: PHP files in src/                     │
│                   OUTPUT: Skill files in skill/                 │
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
- `extractEnums()` - Get TypeScript enum declarations
- `extractConstants()` - Get exported const declarations
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
- Union interface implementation (`implements`)
- Version annotations (`@since`)

**EnumGenerator** - Class-based enums from string literal unions:
- Constants for each value
- Static factory methods
- `values()` method

**NumericEnumGenerator** - Class-based enums from TypeScript numeric enums:
- Integer constants for each value
- `values()`, `names()`, `isValid()`, `nameFor()` methods
- Used for error codes like `ErrorCode`

**ConstantsGenerator** - Protocol constants class:
- String constants (protocol versions, JSON-RPC version)
- Numeric constants (error codes)
- Helper methods for error code validation

**UnionGenerator** - Marker interfaces:
- Interface per union type
- DTOs implement their union interfaces
- Detects parent unions for inheritance

**FactoryGenerator** - Discriminator-based routing:
- Detects discriminator field (`method`, `type`, `kind`, `role`)
- Switch statement routing to concrete types
- Handles nested unions recursively
- Returns `null` if no discriminator detected

**TypeAliasWrapperGenerator** - Wrapper classes for type aliases:
- Handles cases like `type EmptyResult = Result`
- Creates wrapper that extends base type
- Implements union interfaces referencing the alias

**IntersectionTypeWrapperGenerator** - Wrapper classes for intersection types:
- Handles cases like `type GetTaskResult = Result & Task`
- Extends one parent (the "base" type)
- Merges properties from other intersected types
- Implements union interfaces referencing the intersection

**BuilderGenerator** - Fluent builders:
- `withPropertyName()` setters
- `build()` returns DTO

**ContractGenerator** - Marker interfaces:
- `WithArrayTransformation`
- `ResultContract`, `RequestContract`, etc.

**SchemaMapGenerator** - JSON schema map for tooling:
- Complete type registry with relationships
- RPC method mappings
- Factory information
- Domain organization

**SkillGenerator** - Claude Code skill files:
- Markdown reference documentation
- JSON data files for programmatic access
- Shell scripts for type searching

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
| `typeof CONSTANT` | Resolved constant value |

Integer detection patterns: `*Id`, `*Length`, `*Count`, `*Index`, `*Items`

### Domain Classifier (`generators/domain-classifier.ts`)

Maps `@category` tags to PHP namespaces:

| Category | Domain | Subdomain |
|----------|--------|-----------|
| `Tools` | Server | Tools |
| `Resources` | Server | Resources |
| `Sampling` | Client | Sampling |
| `JSON-RPC` | Common | JsonRpc |
| `Tasks` | Client | Tasks |
| `Elicitation` | Client | Elicitation |

### Inheritance Graph (`generators/inheritance-graph.ts`)

Manages TypeScript `extends` relationships:

- Builds parent-child maps
- Provides topological sort (parents before children)
- Classifies properties as own/inherited/narrowed

### Version Tracker (`version-tracker/index.ts`)

Tracks schema history for version annotations:

- Fetches historical schema versions
- Detects when definitions were introduced
- Detects when properties were added
- Provides `@since` annotation data

### Writers (`writers/index.ts`)

File output with organization:

- DTOs: `Domain/Subdomain/ClassName.php`
- Others: `Domain/Subdomain/Type/ClassName.php`
- Generates base classes (`AbstractDataTransferObject`, `AbstractEnum`, `ValidatesRequiredFields` trait)

## Generated Output Structure

```
src/
├── Common/
│   ├── AbstractDataTransferObject.php
│   ├── AbstractEnum.php
│   ├── McpConstants.php           # Protocol constants
│   ├── Traits/
│   │   └── ValidatesRequiredFields.php
│   ├── Contracts/                 # Marker interfaces
│   ├── Protocol/                  # Core protocol types
│   ├── JsonRpc/                   # JSON-RPC message types
│   ├── Content/                   # Content block types
│   └── Tasks/                     # Shared task types
├── Server/
│   ├── Tools/                     # Tool definitions
│   ├── Resources/                 # Resource management
│   ├── Prompts/                   # Prompt templates
│   ├── Logging/                   # Logging types
│   ├── Lifecycle/                 # Server lifecycle
│   └── Core/                      # Server core types
└── Client/
    ├── Sampling/                  # LLM sampling
    ├── Elicitation/               # User input elicitation
    ├── Roots/                     # Root directory management
    ├── Tasks/                     # Task execution
    └── Lifecycle/                 # Client lifecycle

skill/
├── SKILL.md                       # Entry point for Claude Code
├── reference/
│   ├── overview.md
│   ├── common.md
│   ├── server.md
│   ├── client.md
│   ├── rpc-methods.md
│   └── factories.md
├── data/
│   ├── schema-index.json          # Lightweight discovery index
│   ├── schema-common.json
│   ├── schema-server.json
│   └── schema-client.json
└── scripts/
    ├── search-types.sh
    ├── get-type.sh
    └── find-rpc.sh
```

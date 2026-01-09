# MCP PHP Schema Generator

A TypeScript application that generates PHP 7.4 DTOs directly from the MCP TypeScript schema.

## Overview

The generator fetches the official MCP TypeScript schema from GitHub, parses it using ts-morph AST analysis, and produces production-quality PHP code including:

- **DTOs** - Data Transfer Objects with `fromArray()`/`toArray()` methods
- **Enums** - Class-based enums (PHP 7.4 compatible) from both string literal unions and TypeScript enums
- **Constants** - Protocol constants class with error codes and version strings
- **Union Interfaces** - Marker interfaces for polymorphic types
- **Factories** - Discriminator-based instantiation for unions
- **Type Alias Wrappers** - Concrete classes for type aliases referenced in unions
- **Intersection Type Wrappers** - Concrete classes for intersection types (A & B)
- **Builders** - Optional fluent builder pattern classes
- **Contracts** - Marker interfaces for type hierarchies
- **Skill Files** - Claude Code reference documentation and search tools

## Quick Start

```bash
# Install dependencies
npm install

# Build the generator
npm run build

# Generate PHP schema (uses latest config)
npm run generate

# Or run directly with a specific config
node dist/cli/index.js generate -c config/2025-11-25.json
```

## CLI Commands

```bash
# Generate PHP files from schema
generate -c <config-file> [options]
  -c, --config <file>     Config file path (required)
  -o, --output <dir>      Override output directory
  -n, --namespace <ns>    Override PHP namespace
  -p, --php-version <v>   PHP version (7.4-8.3)
  --builders              Enable builder generation
  --no-factories          Disable factory generation
  --dry-run               Preview without writing files
  --fresh                 Force fetch from GitHub (ignore cache)
  --verbose               Show detailed progress

# Clear cached schemas
clear-cache

# Show generator info
info

# List available config versions
configs
```

## Configuration

Configuration files are stored in `config/` as JSON:

```json
{
  "schema": {
    "version": "2025-11-25"
  },
  "output": {
    "generateBuilders": false
  }
}
```

See [Configuration Guide](./configuration.md) for all options.

## Generated Output

The generator produces PHP files organized by MCP domain:

```
src/
├── Common/
│   ├── AbstractDataTransferObject.php
│   ├── AbstractEnum.php
│   ├── McpConstants.php       # Protocol constants & error codes
│   ├── Traits/
│   ├── Contracts/             # Marker interfaces
│   ├── Protocol/              # Core protocol types
│   ├── JsonRpc/               # JSON-RPC message types
│   ├── Content/               # Content block types
│   └── Tasks/                 # Shared task types
├── Server/
│   ├── Tools/                 # Tool definitions
│   ├── Resources/             # Resource management
│   ├── Prompts/               # Prompt templates
│   ├── Logging/               # Logging types
│   ├── Lifecycle/             # Server lifecycle
│   └── Core/                  # Server core types
├── Client/
│   ├── Sampling/              # LLM sampling
│   ├── Elicitation/           # User input elicitation
│   ├── Roots/                 # Root directory management
│   ├── Tasks/                 # Background tasks
│   └── Lifecycle/             # Client lifecycle
└── Contracts/                 # Shared interfaces
```

Additionally, skill files are generated for Claude Code integration:

```
skill/
├── SKILL.md                   # Entry point
├── reference/                 # Markdown documentation
│   ├── overview.md
│   ├── common.md
│   ├── server.md
│   ├── client.md
│   ├── rpc-methods.md
│   └── factories.md
├── data/                      # JSON data files
│   ├── schema-index.json
│   ├── schema-common.json
│   ├── schema-server.json
│   └── schema-client.json
└── scripts/                   # Search utilities
    ├── search-types.sh
    ├── get-type.sh
    └── find-rpc.sh
```

## Key Features

### Version Tracking

The generator tracks schema history and annotates generated code with `@since` tags:

```php
/**
 * @since 2024-11-05
 */
class CallToolRequest extends Request
{
    /**
     * @since 2025-03-26
     */
    protected ?array $arguments;
}
```

### Type Alias Wrappers

Type aliases like `type EmptyResult = Result` get wrapper classes when referenced in unions:

```php
class EmptyResult extends Result implements ServerResultInterface, ClientResultInterface
{
    // Inherits everything from Result
}
```

### Intersection Type Wrappers

Intersection types like `type GetTaskResult = Result & Task` become concrete classes:

```php
class GetTaskResult extends Result implements ClientResultInterface
{
    // Properties from Task are merged in
    protected string $taskId;
    protected string $status;
    // ...
}
```

### Protocol Constants

Exported constants from the schema become a PHP constants class:

```php
class McpConstants
{
    public const LATEST_PROTOCOL_VERSION = '2025-11-25';
    public const JSONRPC_VERSION = '2.0';
    public const PARSE_ERROR = -32700;
    public const INVALID_REQUEST = -32600;
    // ...

    public static function isValidErrorCode(int $code): bool { ... }
    public static function getErrorCodeName(int $code): ?string { ... }
}
```

## Documentation

- [Architecture](./architecture.md) - Pipeline and module overview
- [Configuration](./configuration.md) - All configuration options
- [Design Decisions](./design-decisions.md) - Key architectural choices

## Development

```bash
npm run lint      # ESLint
npm run format    # Prettier
npm run build     # Compile TypeScript
```

## Validation

Generated PHP must pass PHPStan level max:

```bash
cd ..
composer analyse
```

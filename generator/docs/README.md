# MCP PHP Schema Generator

A TypeScript application that generates PHP 7.4 DTOs directly from the MCP TypeScript schema.

## Overview

The generator fetches the official MCP TypeScript schema from GitHub, parses it using ts-morph AST analysis, and produces production-quality PHP code including:

- **DTOs** - Data Transfer Objects with `fromArray()`/`toArray()` methods
- **Enums** - Class-based enums (PHP 7.4 compatible)
- **Union Interfaces** - Marker interfaces for polymorphic types
- **Factories** - Discriminator-based instantiation for unions
- **Builders** - Optional fluent builder pattern classes
- **Contracts** - Marker interfaces for type hierarchies

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
│   ├── Protocol/          # Core protocol types
│   ├── JsonRpc/           # JSON-RPC message types
│   └── Content/           # Content block types
├── Server/
│   ├── Tools/             # Tool definitions
│   ├── Resources/         # Resource management
│   ├── Prompts/           # Prompt templates
│   └── Logging/           # Logging types
├── Client/
│   ├── Sampling/          # LLM sampling
│   ├── Elicitation/       # User input elicitation
│   ├── Roots/             # Root directory management
│   └── Tasks/             # Background tasks
└── Contracts/             # Shared interfaces
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

Generated PHP must pass PHPStan level 8:

```bash
cd ..
composer analyse
```

# Configuration

## Config File Format

Configuration files are JSON stored in `config/`:

```json
{
  "schema": {
    "version": "2025-11-25"
  },
  "output": {
    "outputDir": "../src",
    "namespace": "WP\\McpSchema",
    "indentation": "spaces",
    "indentSize": 4
  }
}
```

## Schema Options

The schema is always fetched from the official MCP GitHub repository (`modelcontextprotocol/modelcontextprotocol`).

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `version` | `string` | **required** | Schema version (e.g., `"2025-11-25"`) |

## Output Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `outputDir` | `string` | `"../src"` | Output directory (relative to generator/) |
| `namespace` | `string` | `"WP\\McpSchema"` | Base PHP namespace |
| `indentation` | `"spaces"` \| `"tabs"` | `"spaces"` | Indentation style |
| `indentSize` | `number` | `4` | Spaces per indent (1-8) |

**Note:** Generated code targets PHP 7.4 for maximum compatibility. Factory classes for unions are always generated.

## CLI Overrides

CLI options override config file values:

```bash
# Override output directory
node dist/cli/index.js generate -c config/2025-11-25.json -o /custom/path

# Override namespace
node dist/cli/index.js generate -c config/2025-11-25.json -n "My\\Namespace"
```

## Generation Options

These are runtime options, not persisted in config:

| Option | Description |
|--------|-------------|
| `--dry-run` | Preview files without writing |
| `--fresh` | Force fetch from GitHub (bypass cache) |
| `--verbose` | Show detailed progress messages |

## Minimal Config

Only `schema.version` is required; all other options use defaults:

```json
{
  "schema": {
    "version": "2025-11-25"
  }
}
```

## Version-Specific Configs

The `config/` directory contains version-specific configurations:

- `2024-11-05.json` - MCP 2024-11-05
- `2025-03-26.json` - MCP 2025-03-26
- `2025-06-18.json` - MCP 2025-06-18
- `2025-11-25.json` - MCP 2025-11-25 (latest)

List available versions:

```bash
node dist/cli/index.js configs
```

## Caching

Fetched schemas are cached in `.cache/schemas/`:

```text
.cache/schemas/
└── modelcontextprotocol_modelcontextprotocol_2025-11-25_schema.ts
```

Clear cache:

```bash
node dist/cli/index.js clear-cache
```

## Generated Outputs

The generator always produces:

| Output | Description |
|--------|-------------|
| **PHP DTOs** | Data transfer objects in `src/` |
| **Constants Class** | `McpConstants.php` with protocol constants and error codes |
| **Union Interfaces** | Marker interfaces for polymorphic types |
| **Factory Classes** | Discriminator-based instantiation |
| **Type Alias Wrappers** | Concrete classes for aliases referenced in unions |
| **Intersection Wrappers** | Concrete classes for intersection types |
| **Contracts** | Marker interfaces for type hierarchies |
| **Skill Files** | Claude Code reference docs in `skill/` |

## Skill Files

Skill files are generated automatically to `skill/` (sibling to `src/`):

```text
skill/
├── SKILL.md                   # Entry point for Claude Code
├── reference/                 # Markdown documentation
│   ├── overview.md
│   ├── common.md
│   ├── server.md
│   ├── client.md
│   ├── rpc-methods.md
│   └── factories.md
├── data/                      # JSON data for programmatic access
│   ├── schema-index.json      # Lightweight discovery index (~2KB)
│   ├── schema-common.json
│   ├── schema-server.json
│   └── schema-client.json
└── scripts/                   # Search utilities
    ├── search-types.sh        # Search types by name
    ├── get-type.sh            # Get type details
    └── find-rpc.sh            # Find RPC methods
```

## Version Tracking

The generator automatically tracks schema history for `@since` annotations:

1. Fetches historical schema versions up to the target version
2. Compares definitions and properties across versions
3. Annotates each definition/property with introduction version

This happens automatically during generation (no configuration needed).

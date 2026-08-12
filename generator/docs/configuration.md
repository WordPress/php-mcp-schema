# Configuration

## Config File Format

Shipping configuration files are JSON stored in `config/revisions/`:

```json
{
  "schema": {
    "version": "2026-07-28"
  },
  "skill": {
    "enabled": true,
    "outputDir": "../skill"
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
| `outputDir` | `string` | `"../src/V<YYYYMMDD>"` | Revision tree derived from `schema.version` |
| `namespace` | `string` | `"WP\\McpSchema\\V<YYYYMMDD>"` | Revision namespace derived from `schema.version` |
| `indentation` | `"spaces"` \| `"tabs"` | `"spaces"` | Indentation style |
| `indentSize` | `number` | `4` | Spaces per indent (1-8) |

**Note:** Generated code targets PHP 7.4 for maximum compatibility. Factory classes for unions are always generated.

## CLI Overrides

CLI options override config file values:

```bash
# Relocate one revision tree; the final directory must keep its derived name
node dist/cli/index.js generate -c config/revisions/2025-11-25.json -o /custom/path/V20251125

# Override namespace
node dist/cli/index.js generate -c config/revisions/2025-11-25.json -n "My\\Namespace\\V20251125"
```

## Generation Options

These are runtime options, not persisted in config:

| Option | Description |
|--------|-------------|
| `--dry-run` | Preview files without writing |
| `--fresh` | Force fetch from GitHub (bypass cache) |
| `--verbose` | Show detailed progress messages |

## Minimal Config

Only `schema.version` is required for an isolated config; shipping configs must also declare whether they own skill output:

```json
{
  "schema": {
    "version": "2025-11-25"
  }
}
```

## Shipping Revisions and Version History

`config/revisions/` contains the two trees included in the package:

- `2025-11-25.json`
- `2026-07-28.json` (owns the generated root skill reference)

`config/versions.json` retains the complete revision history used for `@since` annotations; history entries do not automatically become shipping trees.

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
| **PHP DTOs** | Data transfer objects in `src/V<YYYYMMDD>/` |
| **Constants Class** | `McpConstants.php` with protocol constants and error codes |
| **Union Interfaces** | Marker interfaces for polymorphic types |
| **Factory Classes** | Discriminator-based instantiation |
| **Type Alias Wrappers** | Concrete classes for aliases referenced in unions |
| **Intersection Wrappers** | Concrete classes for intersection types |
| **Contracts** | Marker interfaces for type hierarchies |
| **Skill Files** | Revision-labelled reference docs in the configured explicit destination |

## Skill Files

The shipping revision with `skill.enabled: true` generates files to its explicit `skill.outputDir`:

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

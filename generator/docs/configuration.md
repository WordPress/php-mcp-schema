# Configuration

## Config File Format

Configuration files are JSON stored in `config/`:

```json
{
  "schema": {
    "type": "github",
    "repository": "modelcontextprotocol/modelcontextprotocol",
    "branch": "main",
    "path": "schema",
    "version": "2025-11-25"
  },
  "output": {
    "outputDir": "../src",
    "namespace": "WP\\McpSchema",
    "phpVersion": "7.4",
    "indentation": "spaces",
    "indentSize": 4,
    "generateBuilders": false,
    "generateFactories": true
  }
}
```

## Schema Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `type` | `"github"` \| `"local"` | `"github"` | Schema source type |
| `repository` | `string` | `"modelcontextprotocol/modelcontextprotocol"` | GitHub repository |
| `branch` | `string` | `"main"` | Git branch |
| `path` | `string` | `"schema"` | Path within repository |
| `version` | `string` | **required** | Schema version (e.g., `"2025-11-25"`) |

## Output Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `outputDir` | `string` | `"../src"` | Output directory (relative to generator/) |
| `namespace` | `string` | `"WP\\McpSchema"` | Base PHP namespace |
| `phpVersion` | `string` | `"7.4"` | Target PHP version (`"7.4"`-`"8.3"`) |
| `indentation` | `"spaces"` \| `"tabs"` | `"spaces"` | Indentation style |
| `indentSize` | `number` | `4` | Spaces per indent (1-8) |
| `generateBuilders` | `boolean` | `false` | Generate fluent builder classes |
| `generateFactories` | `boolean` | `true` | Generate factory classes for unions |

## CLI Overrides

CLI options override config file values:

```bash
# Override output directory
node dist/cli/index.js generate -c config/2025-11-25.json -o /custom/path

# Override namespace
node dist/cli/index.js generate -c config/2025-11-25.json -n "My\\Namespace"

# Enable builders (overrides config)
node dist/cli/index.js generate -c config/2025-11-25.json --builders

# Disable factories (overrides config)
node dist/cli/index.js generate -c config/2025-11-25.json --no-factories
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

```
.cache/schemas/
└── modelcontextprotocol_modelcontextprotocol_2025-11-25_schema.ts
```

Clear cache:

```bash
node dist/cli/index.js clear-cache
```

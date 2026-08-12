# MCP PHP Schema Generator

TypeScript generator that creates PHP DTOs from the official MCP TypeScript schema.

## Setup

```bash
npm install
npm run build
```

## Running the Generator

`config/revisions/` is the shipping build list. A normal run generates both revision trees; `-c` remains available for isolated development.

**Generate every shipping revision:**

```bash
npm run generate
```

**Generate and run PHPStan validation:**

```bash
npm run generate:check
```

**Generate one revision:**

```bash
node dist/cli/index.js generate -c config/revisions/2026-07-28.json
```

## CLI Options

```bash
npx mcp-php-generator generate --help

Options:
  -c, --config <file>      Generate one revision from a configuration file
  -o, --output <dir>       Output directory (overrides config)
  -n, --namespace <ns>     PHP namespace (overrides config)
  --dry-run                Show what would be generated without writing files
  --fresh                  Force fresh fetch from GitHub (ignore cache)
  --verbose                Enable verbose output
```

## Other Commands

```bash
npm run build                        # Compile TypeScript
npm run lint                         # Run ESLint
npm run format                       # Run Prettier
npx mcp-php-generator info           # Show generator info
npx mcp-php-generator configs        # List available config files
npx mcp-php-generator clear-cache    # Clear schema cache
```

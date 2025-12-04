# MCP PHP Schema Generator

TypeScript generator that creates PHP DTOs from the official MCP TypeScript schema.

## Setup

```bash
npm install
npm run build
```

## Running the Generator

The generator requires a configuration file that specifies the schema version. Config files are located in `config/`.

**Available versions:**
- `2024-11-05.json` - Initial MCP release
- `2025-03-26.json`
- `2025-06-18.json`
- `2025-11-25.json` - Latest

**Generate PHP schema:**

```bash
npx mcp-php-generator generate -c config/2025-11-25.json
```

**Generate and run PHPStan validation:**

```bash
npm run generate:check -- -c config/2025-11-25.json
```

## CLI Options

```bash
npx mcp-php-generator generate --help

Options:
  -c, --config <file>      Configuration file (required)
  -o, --output <dir>       Output directory (overrides config)
  -n, --namespace <ns>     PHP namespace (overrides config)
  -p, --php-version <ver>  PHP version (overrides config)
  --builders               Generate builder classes
  --no-factories           Disable factory generation
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
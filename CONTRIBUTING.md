# Contributing to PHP MCP Schema

Thank you for your interest in contributing! Please [open an issue](https://github.com/WordPress/php-mcp-schema/issues) to discuss bugs, questions, or feature ideas before sending a pull request.

---

## Architecture Overview

`src/` contains the generic record runtime, content-addressed descriptor pool, and revision catalogs generated from the official [MCP TypeScript schema](https://github.com/modelcontextprotocol/modelcontextprotocol). The generator lives in `generator/` and is excluded from the Composer package.

> **Warning: Never edit files in `src/` directly.**
> Any manual changes will be overwritten the next time the generator runs.
> All PHP output changes must go through the generator.

---

## Contributing Changes to PHP Output

Use this workflow when your change affects the generated PHP code, descriptor compilation, catalog types, validation, or serialization.

**Requirements:** Node.js >= 18, npm

```bash
# 1. Navigate to the generator
cd generator

# 2. Install dependencies (first time only)
npm install

# 3. Change descriptor compilation in generator/src/record-model/
#    or shared PHP behavior in generator/templates/

# 4. Build the TypeScript
npm run build

# 5. Regenerate PHP files into src/
npm run generate

# 6. Validate PHP behavior and static contracts from the repo root
cd ..
composer check
```

The `npm run generate:check` convenience script (run from `generator/`) combines steps 5 and 6 in one command.

---

## Contributing Non-Generated Changes

For changes to the README, CI configuration, `composer.json`, or other non-generated files, use the standard fork-and-PR workflow — no generator steps needed.

---

## Running Validation

```bash
# Runtime wire checks and PHPStan level max
composer check

# Generator determinism, descriptor closure, and catalog coverage
cd generator && npm test

# Generator lint
cd generator && npm run lint

# Validate composer.json
composer validate --strict
```

Composer commands run from the repository root. Generator commands run from `generator/`.

---

## Pull Request Guidelines

- **One concern per PR.** Keep changes focused.
- **Descriptive title.** Summarize what changed and why.
- **CI must pass.** Runtime checks, PHPStan, generator tests, lint, and regeneration checks run automatically on every PR.
- **For PHP output changes:** include the regenerated `src/` files in the same commit as the generator changes.

---

## Requirements

| Tool | Minimum version |
|------|----------------|
| PHP | 7.4 |
| Composer | 2.x |
| Node.js | 18 (generator only) |

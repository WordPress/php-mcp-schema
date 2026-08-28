# Contributing to PHP MCP Schema

Please open an issue before sending a pull request for a new feature or public
API change.

## Architecture

The package has two source boundaries:

- `resources/schema/` contains commit-pinned canonical MCP JSON Schemas.
- `src/Generated/` contains deterministic catalogs, records, contracts, value
  constants, and the registry produced from those schemas.

Handwritten revision selection, validation, hydration, immutable storage, and
exceptions live outside `src/Generated/`. The generator may replace only the
resolved generated subtree.

Read [the architecture proposal](docs/dual-revision-schema-runtime-proposal.md)
before changing the public record model, revision availability, schema
interpretation, or Adapter boundary.

## Requirements and setup

| Tool | Minimum version |
| --- | --- |
| PHP | 7.4 |
| Composer | 2.x |
| Node.js | 18, development only |

```bash
composer install
cd generator
npm install
```

Production Composer installs have no runtime dependency. AJV and Node are used
only to audit canonical inputs and generated output.

## Generated output changes

Do not edit `src/Generated/` directly. Change the plain Node generator or its
reviewed inputs, then run:

```bash
cd generator
npm run verify
```

`verify` checks JavaScript syntax, both canonical source digests, AJV fixtures,
the all-pairs compatibility manifest, and deterministic generation.

Adding or removing a supported revision requires:

1. A commit-pinned official source and reviewed SHA-256 digest.
2. A compatibility comparison against every still-supported revision.
3. Explicit source-cited classifications for every structural, getter, kind,
   and directional message change.
4. Updated behavioral and cross-revision tests.

Unknown schema constructs and unclassified changes must fail generation.

## Handwritten runtime changes

The selected schema is the only validation and construction authority. Keep the
runtime PHP 7.4-compatible and dependency-free. Do not add unchecked public
construction, validation-disabled modes, aliases for removed APIs, or alternate
record representations.

Run from the repository root:

```bash
composer test
composer analyse
composer validate --strict
git diff --check
```

Tests should exercise public construction and raw JSON boundaries. When a PHP
interpreter boundary changes, run the suite on PHP 7.4 and a current supported
PHP version.

## Pull requests

- Keep each commit coherent and include generated output with its generator
  change.
- Explain revision-specific behavior with the exact MCP identifier.
- Include only test results actually run.
- Preserve canonical schema bytes; refresh them through
  `generator/update-schemas.mjs` only.

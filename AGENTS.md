# php-mcp-schema agent guide

This repository provides a dependency-free PHP 7.4+ runtime for exact MCP
schema revisions.

## Build, test, and analyze

- Install PHP development dependencies: `composer install`
- Install generator development dependencies: `cd generator && npm install`
- Verify the generator: `cd generator && npm run verify`
- Run PHP tests: `composer test`
- Run static analysis: `composer analyse`
- Validate package metadata: `composer validate --strict`
- Verify PSR-4 paths and duplicate classes: `composer autoload:verify`

## Source boundaries

- Canonical inputs are the commit-pinned files under `resources/schema/`.
  Refresh them only with `cd generator && npm run schemas:update`.
- Generated PHP lives only in `src/Record/`, `src/Contract/`, `src/Value/`,
  `src/Internal/Catalog/`, and `src/Internal/TypeRegistry.php`. Change the
  generator and regenerate; never edit those outputs directly.
- Generation stages a complete output and replaces only those allowlisted paths.
  It must never delete handwritten siblings such as `src/Record.php`,
  `src/Schema.php`, or other files under `src/Internal/`.
- Normal generation is offline. AJV and all Node packages remain development
  dependencies and Composer production installs remain dependency-free.

## Revision rules

- Use exact identifiers such as `2025-11-25` and `2026-07-28`. Do not create
  project-authored behavioral labels or version-range assumptions.
- Preserve canonical definition names verbatim, including names containing
  terminology prohibited for project-authored revision groupings.
- Adding a revision requires a pinned source, reviewed digest, and a source-cited
  compatibility classification against every still-supported revision.
- Unknown schema constructs or unclassified compatibility changes must fail
  generation.

## Architecture

- Read `docs/architecture.md` before changing public
  records, revision availability, validation, hydration, or application
  boundaries.
- Catalogs own exact structural validation and directional message availability.
- Compatible named objects share records; same-name kind changes use
  kind-specific symbols with exact-revision availability.
- Records preserve wire identity and extensions but carry no revision or schema
  reference. Cross-revision reuse always validates through the target schema.
- Do not add DTO aliases, compatibility facades, public Type handles, unchecked
  construction, validation-disabled modes, or production validation dependencies.

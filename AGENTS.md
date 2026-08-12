# php-mcp-schema agent guide

This branch is a ground-up descriptor-backed generic record experiment for explicit MCP revisions. It does not preserve the concrete DTO API.

## Generated PHP invariant

- Never edit `src/` directly.
- Change PHP contracts or runtime behavior in `generator/templates/`.
- Change schema compilation, descriptors, catalogs, or generated PHPDoc in `generator/src/record-model/`.
- Run `cd generator && npm run generate` after either kind of change, and include the regenerated `src/` output.

## Commands

- PHP behavior and static contract: `composer check`
- Interactive example: `composer demo`
- Generator build and tests: `cd generator && npm test`
- Generator lint: `cd generator && npm run lint`
- Full regeneration check: `cd generator && npm run generate:check`

## Architecture boundaries

- Keep revision selection explicit through `Schemas`; do not add ambient current-version state.
- Keep protocol negotiation, sessions, retries, revision translation, and downgrade policy outside this package.
- Treat wire shapes and hydrated field shapes as separate types. `Type<TWire, TFields>` and `Record<TWire, TFields>` must match runtime hydration.
- Preserve omitted versus present-null and JSON object versus list identity. For ambiguous empty values passed through `fromArray()`, `stdClass` means object and `[]` means list.
- The public `RevisionSchema::type()` catalog exposes record-compatible logical types only. Scalar aliases remain internal descriptor references.

## Generator invariants

- Shipping schema source digests are pinned in `generator/src/record-model/compiler.ts`; do not update them without verifying the corresponding official immutable revision source.
- Descriptor output must be deterministic and reference-closed within each revision.
- The writer may replace only the repository's resolved `src/` directory; keep its path guard intact.

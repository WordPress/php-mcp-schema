# Descriptor record generator

The generator fetches the official MCP `2025-11-25` and `2026-07-28` TypeScript schemas, compiles their type syntax into canonical descriptors, deduplicates identical descriptors by SHA-256, and replaces `../src` with the generic PHP runtime and revision catalogs.

`../src` is a destructive generated-output boundary. The writer verifies that the resolved destination is exactly the repository's `src/` directory before replacing it.

## Commands

```bash
npm install
npm run build
npm run generate
npm test
```

`npm run generate:check` regenerates the package, then runs the PHP wire checks and PHPStan analysis from the repository root.

Downloaded immutable schema revisions are cached under `.cache/schemas/`. Remove that ignored cache to force a new download.

Each shipping source schema has a pinned SHA-256 digest in the compiler. Generation fails instead of silently accepting changed content at the same upstream revision URL.

## Source layout

- `src/record-model/compiler.ts` compiles TypeScript AST nodes into descriptor data.
- `src/record-model/php-renderer.ts` writes the content-addressed pool, typed catalogs, and entry point.
- `templates/` owns the shared PHP 7.4 runtime and public contracts.
- `tests/record-model.test.mjs` verifies determinism, deduplication, reference closure, and representative catalog coverage.

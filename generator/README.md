# MCP PHP schema generator

This directory contains the development-only generator for
`wordpress/php-mcp-schema`. Production installs do not include it or its AJV
development dependency.

The generator consumes only the commit-pinned canonical files under
`resources/schema/`. Normal generation is offline. It writes one guarded
`src/Generated/` subtree containing:

- one complete PHP-literal catalog per exact revision;
- shared records for compatible named objects;
- kind-specific record and contract symbols;
- enum value constants; and
- exact-revision definition and directional message availability.

Handwritten runtime files live outside `src/Generated/` and are never part of
the generator's deletion boundary.

## Install and verify

```bash
cd generator
npm install
npm run verify
```

`npm run verify` checks JavaScript syntax, AJV fixtures, pinned source digests,
the all-pairs compatibility audit, and deterministic generated output.

## Update canonical sources

```bash
cd generator
npm run schemas:update
npm run audit
npm run generate
```

`schemas:update` downloads only the commit-pinned URLs in
`schema-sources.json` and rejects a digest mismatch. Adding a revision also
requires a source-cited compatibility classification for every comparison with
the still-supported revisions. `audit` fails until every generated difference
has an explicit classification.

Review generated changes and run `npm run verify` before committing them. Never
edit files under `src/Generated/` directly.

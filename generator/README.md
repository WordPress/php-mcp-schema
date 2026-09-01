# MCP PHP schema generator

This directory contains the development-only generator for
`wordpress/php-mcp-schema`. Production installs do not include it or its AJV
development dependency.

The generator consumes only the commit-pinned canonical files under
`resources/schema/`. Normal generation is offline. It writes:

- public records under `src/Record/`;
- public union contracts under `src/Contract/`;
- public enum value constants under `src/Value/`;
- one complete PHP-literal catalog per exact revision under
  `src/Internal/Catalog/`; and
- exact-revision type metadata in `src/Internal/TypeRegistry.php`.

The generator completes all writes in a staging tree, then replaces only those
five allowlisted paths. Handwritten siblings such as `src/Record.php`,
`src/Schema.php`, and other files under `src/Internal/` are never part of the
deletion boundary. The legacy `src/Generated/` path is removed during generation
and must not be recreated. Generated provenance is carried by the file header
and this ownership allowlist, not by a `Generated` namespace layer.

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
edit generated PHP directly.

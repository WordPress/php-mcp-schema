# Contributing to PHP MCP Schema

Please open an issue before sending a pull request for a new feature or public
API change.

## Architecture

The package has explicit source boundaries:

- `resources/schema/` contains commit-pinned canonical MCP JSON Schemas.
- `src/Record/`, `src/Contract/`, and `src/Value/` contain deterministic public
  symbols produced from those schemas.
- `src/Internal/Catalog/` and `src/Internal/TypeRegistry.php` contain generated
  revision and type metadata used by the handwritten runtime.

Handwritten revision selection, validation, hydration, immutable storage, and
exceptions live outside those generated paths. The generator stages a complete
output before replacing only that explicit allowlist; handwritten siblings such
as `src/Record.php`, `src/Schema.php`, and other `src/Internal/` files are never
part of its deletion boundary.

Read [the architecture](docs/architecture.md)
before changing the public record model, revision availability, schema
interpretation, or application boundary.

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

Do not edit generated PHP directly. Change the plain Node generator or its
reviewed inputs, then run:

```bash
cd generator
npm run generate
npm run verify
```

`verify` checks JavaScript syntax, both canonical source digests, AJV fixtures,
the all-pairs compatibility manifest, and deterministic generation.

Adding or removing a supported revision requires:

1. A commit-pinned official source and reviewed SHA-256 digest.
2. A compatibility comparison against every still-supported revision.
3. Generated structural, field, and directional-message inventories, plus a
   rationale-bearing review decision for each native getter-category or
   same-name kind change.
4. Updated behavioral and cross-revision tests.

Unknown schema constructs, semantic `$ref` siblings, and unreviewed getter or
kind changes must fail generation. The executable file layout and commands are
documented in [the generator guide](generator/README.md#add-a-supported-revision).

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
composer autoload:verify
composer artifact:verify
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

### Merging PRs

All members of the [php-mcp-schema-maintainers team](https://github.com/orgs/WordPress/teams/php-mcp-schema-maintainers) within the WordPress org on GitHub have the ability to review and merge PRs.  If you are not a maintainer but have reviewed a PR and are confident in the code, approve the PR and comment pinging @wordpress/php-mcp-schema-maintainers or a specific maintainer who has been involved in the PR.  Once a maintainer confirms there are no objections, you are free to merge the PR into `trunk`.

Most PRs will be automatically assigned a release milestone, but please make sure your merged PR was assigned one.  Doing so creates the historical legacy of what code landed when, and makes it possible for all project contributors (even non-technical ones) to access this information.

### Joining the php-mcp-schema-maintainers team

If you have 2-3 meaningful accepted contributions, ask in the [#core-ai Slack channel](https://wordpress.slack.com/archives/C08TJ8BPULS) to get added to a specific section of the CODEOWNERS file so you're auto-assigned for PR reviews.

If you have 3-5 meaningful PR reviews after being added to CODEOWNERS, ask in the [#core-ai Slack channel](https://wordpress.slack.com/archives/C08TJ8BPULS) to get added to the php-mcp-schema-maintainers team.

### Closing PRs

Sometimes, a PR may not be mergeable, no matter how much additional effort is applied to it (e.g. out of scope).  In these cases, it’s best to communicate with the contributor graciously while describing why the PR was closed, this encourages productive future involvement.

Make sure to:

1. Thank the contributor for their time and effort.
2. Fully explain the reasoning behind the decision to close the PR.
3. Link to as much supporting documentation as possible.

If you’d like a template to follow:

> Thanks \_\_\_\_ for the time you’ve spent on this PR.
>
> I’m closing this PR because \_\_\_\_. To clarify further, \_\_\_\_.
>
> For more details, please see \_\_\_\_ and \_\_\_\_.

# PHP MCP Schema

A dependency-free PHP 7.4+ runtime for the canonical
[Model Context Protocol](https://modelcontextprotocol.io/) schemas.

The package validates and hydrates exact MCP revisions into immutable shared
records. It is a schema package, not an MCP client, server, or transport SDK.

Supported revisions:

- `2025-11-25`
- `2026-07-28`

Unknown identifiers are rejected. The runtime never selects a revision by a
range or nearest-version rule.

## Installation

> **Unreleased branch API:** the examples below describe the current development
> branch.
> The latest tagged Composer release still exposes the previous public API.
> Until this work is released, consume only an exact reviewed branch commit
> through a VCS repository reference.

After the runtime is released:

```bash
composer require wordpress/php-mcp-schema
```

## Select and use a schema

Choose the exact revision before constructing any protocol value:

```php
use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

$schema = Schemas::create()->forVersion(Schemas::V2026_07_28);

$tool = $schema->fromArray(Tool::class, array(
    'name' => 'get_weather',
    'description' => 'Get current weather for a location',
    'inputSchema' => array(
        'type' => 'object',
        'properties' => array(
            'location' => array('type' => 'string'),
        ),
        'required' => array('location'),
    ),
));

echo $tool->getName();
echo json_encode($tool, JSON_THROW_ON_ERROR);
```

`fromArray()` is for PHP associative-array construction. `fromValue()` accepts
decoded `stdClass`/list graphs and existing immutable records. `fromJson()`
accepts raw JSON and preserves object/list identity and numeric-string object
keys:

```php
use WP\McpSchema\Contract\ClientRequest;
use WP\McpSchema\Record\CallToolRequest;
use WP\McpSchema\Schemas;

$schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
$request = $schema->fromJson(
    ClientRequest::class,
    '{"jsonrpc":"2.0","id":1,"method":"tools/call","params":{"name":"get_weather","arguments":{"location":"Paris"}}}'
);

if ($request instanceof CallToolRequest) {
    echo $request->getParams()->getName();
    $arguments = $request->getParams()->getArguments();
    if ($arguments !== null) {
        echo $arguments->location;
    }
}
```

Useful union construction roots live under `WP\McpSchema\Contract`. Official
union order determines which concrete `WP\McpSchema\Record` is returned.

## Records and wire identity

Records are immutable. Named getters read fields declared by the selected
revision. Generic access preserves open-schema extension data:

```php
$hasDescription = $tool->has('description');
$description = $tool->get('description');
$wireObject = $tool->jsonSerialize();
```

`has()` distinguishes omission from an explicit `null`. `get()` reads declared
fields and present extension keys, and rejects unknown absent keys.
`jsonSerialize()` returns a defensive `stdClass` and is the complete wire-output
API.

Sequential PHP arrays are JSON lists. Associative arrays are JSON objects. Use
`new stdClass()` when an unconstrained empty JSON object must be unambiguous.

## Exact message availability

The selected schema exposes generated directional checks:

```php
$schema->allowsClientRequest('ping');
$schema->allowsClientNotification('notifications/initialized');
$schema->allowsServerRequest('sampling/createMessage');
$schema->allowsServerNotification('notifications/tools/list_changed');
$schema->allowsEmbeddedInput('elicitation/create');
```

For example, `ping` is valid under `2025-11-25` and absent under `2026-07-28`;
`server/discover` is valid under `2026-07-28` and absent under `2025-11-25`.

## Public namespaces

- `WP\McpSchema\Schemas` and `WP\McpSchema\Schema` — exact revision selection
  and construction.
- `WP\McpSchema\Record` — immutable named objects shared where compatible.
- `WP\McpSchema\Contract` — useful union construction roots.
- `WP\McpSchema\Value` — string constants for canonical enum-like values.
- `WP\McpSchema\Exception` — stable selection, validation, JSON, availability,
  and field-access failures.

See [the migration guide](docs/MIGRATION.md)
when moving from the removed DTO API.

## Development

Canonical schemas are pinned under `resources/schema/`. The development-only
plain Node generator stages and replaces only `src/Record/`, `src/Contract/`,
`src/Value/`, `src/Internal/Catalog/`, and
`src/Internal/TypeRegistry.php`. Handwritten runtime files remain separate,
including `src/Record.php` and the other files under `src/Internal/`.

```bash
composer install
composer test
composer analyse
composer validate --strict
composer autoload:verify

cd generator
npm install
npm run generate
npm run verify
```

Never edit generated PHP directly. See the
[generator guide](generator/README.md) and the
[architecture](docs/architecture.md).

## License

GPL-2.0-or-later. See [LICENSE.md](LICENSE.md).

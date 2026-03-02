# PHP MCP Schema

A PHP representation of the [Model Context Protocol (MCP)](https://modelcontextprotocol.io/) schema types.

This package provides Data Transfer Objects (DTOs), Enums, and Unions that mirror the official MCP TypeScript schema.
It is **not** an SDK, client, or server implementation;
just the type definitions for building your own MCP-compatible applications in PHP.

## Installation

```bash
composer require wordpress/php-mcp-schema
```

Requires PHP 7.4 or higher.

## Usage

```php
use WP\McpSchema\Server\Tools\Tool;
use WP\McpSchema\Server\Tools\CallToolRequest;
use WP\McpSchema\Common\Content\TextContent;

// Create a tool definition
$tool = Tool::fromArray([
    'name' => 'get_weather',
    'description' => 'Get current weather for a location',
    'inputSchema' => [
        'type' => 'object',
        'properties' => [
            'location' => ['type' => 'string', 'description' => 'City name'],
        ],
        'required' => ['location'],
    ],
]);
```

## Available Types

### Server Types (`WP\McpSchema\Server\`)

- **Tools** - `Tool`, `CallToolRequest`, `CallToolResult`, `ListToolsRequest`, `ListToolsResult`
- **Resources** - `Resource`, `ResourceTemplate`, `ReadResourceRequest`, `ReadResourceResult`
- **Prompts** - `Prompt`, `PromptMessage`, `GetPromptRequest`, `GetPromptResult`
- **Logging** - `LoggingMessageNotification`, `SetLevelRequest`

### Client Types (`WP\McpSchema\Client\`)

- **Sampling** - `CreateMessageRequest`, `CreateMessageResult`, `SamplingMessage`
- **Elicitation** - `ElicitRequest`, `ElicitResult`
- **Roots** - `ListRootsRequest`, `ListRootsResult`, `Root`

### Common Types (`WP\McpSchema\Common\`)

- **Protocol** - `InitializeRequest`, `InitializeResult`, `PingRequest`
- **Content** - `TextContent`, `ImageContent`, `AudioContent`
- **JSON-RPC** - `JSONRPCRequest`, `JSONRPCNotification`, `JSONRPCResultResponse`, `JSONRPCErrorResponse`

## Generator

The PHP code in `src/` is auto-generated from the official MCP TypeScript schema. The generator is located in the `generator/` directory and is not included in the Composer package.

See [generator/README.md](generator/README.md) for setup and usage instructions.

## License

GPL-2.0-or-later - see [LICENSE.md](LICENSE.md) for details.

## Links

- [Model Context Protocol Specification](https://spec.modelcontextprotocol.io/)
- [MCP GitHub Repository](https://github.com/modelcontextprotocol/modelcontextprotocol)

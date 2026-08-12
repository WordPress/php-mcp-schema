# MCP PHP Schema Overview

Version: 2026-07-28
Namespace: `WP\McpSchema\V20260728`

## Architecture

The schema follows the Model Context Protocol specification.
Types are organized into three domains:

- **Common**: Base types, JSON-RPC, content blocks
- **Server**: Resources, tools, prompts, logging
- **Client**: Sampling, elicitation, roots, tasks

## Type Hierarchy

```
Request (base for all requests)
├── PaginatedRequest
├── [Domain]Request types

Result (base for all results)
├── PaginatedResult
├── [Domain]Result types

Notification (base for notifications)
├── [Domain]Notification types
```

## Union Interfaces

Union types are represented as interfaces with factory classes:

| Union | Purpose |
| --- | --- |
| `ClientRequestInterface` | All requests from client to server |
| `ServerRequestInterface` | All requests from server to client |
| `ClientResultInterface` | All results for client requests |
| `ServerResultInterface` | All results for server requests |
| `ContentBlockInterface` | Text, image, audio, resource content |

# RPC Methods Reference

## Client → Server

| Method | Direction | Request | Result |
| --- | --- | --- | --- |
| `server/discover` | client→server | DiscoverRequest | DiscoverResult |
| `resources/list` | client→server | ListResourcesRequest | ListResourcesResult |
| `resources/templates/list` | client→server | ListResourceTemplatesRequest | ListResourceTemplatesResult |
| `resources/read` | client→server | ReadResourceRequest | ReadResourceResult |
| `subscriptions/listen` | client→server | SubscriptionsListenRequest | SubscriptionsListenResult |
| `prompts/list` | client→server | ListPromptsRequest | ListPromptsResult |
| `prompts/get` | client→server | GetPromptRequest | GetPromptResult |
| `tools/list` | client→server | ListToolsRequest | ListToolsResult |
| `tools/call` | client→server | CallToolRequest | CallToolResult |
| `sampling/createMessage` | client→server | CreateMessageRequest | CreateMessageResult |
| `completion/complete` | client→server | CompleteRequest | CompleteResult |
| `roots/list` | client→server | ListRootsRequest | ListRootsResult |
| `elicitation/create` | client→server | ElicitRequest | ElicitResult |

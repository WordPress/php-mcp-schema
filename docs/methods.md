# Method to record map

Which record class carries each JSON-RPC method, and in which revision. Every
record lives under `WP\McpSchema\Record`. Construct requests and notifications
with the request record; select the result record from the originating method,
as described in the [migration guide](MIGRATION.md#validate-results-for-the-originating-method).

A method is valid in a direction only where the row marks it. The same checks
are available at runtime through `Schema::allowsClientRequest()` and its
siblings.

## Client to server requests

| Method | Request record | Result record | 2025-11-25 | 2026-07-28 |
| --- | --- | --- | --- | --- |
| `completion/complete` | `CompleteRequest` | `CompleteResult` | yes | yes |
| `initialize` | `InitializeRequest` | `InitializeResult` | yes | no |
| `logging/setLevel` | `SetLevelRequest` | `EmptyResult` | yes | no |
| `ping` | `PingRequest` | `EmptyResult` | yes | no |
| `prompts/get` | `GetPromptRequest` | `GetPromptResult` | yes | yes |
| `prompts/list` | `ListPromptsRequest` | `ListPromptsResult` | yes | yes |
| `resources/list` | `ListResourcesRequest` | `ListResourcesResult` | yes | yes |
| `resources/read` | `ReadResourceRequest` | `ReadResourceResult` | yes | yes |
| `resources/subscribe` | `SubscribeRequest` | `EmptyResult` | yes | no |
| `resources/templates/list` | `ListResourceTemplatesRequest` | `ListResourceTemplatesResult` | yes | yes |
| `resources/unsubscribe` | `UnsubscribeRequest` | `EmptyResult` | yes | no |
| `server/discover` | `DiscoverRequest` | `DiscoverResult` | no | yes |
| `subscriptions/listen` | `SubscriptionsListenRequest` | `SubscriptionsListenResult` | no | yes |
| `tasks/cancel` | `CancelTaskRequest` | `CancelTaskResult` | yes | no |
| `tasks/get` | `GetTaskRequest` | `GetTaskResult` | yes | no |
| `tasks/list` | `ListTasksRequest` | `ListTasksResult` | yes | no |
| `tasks/result` | `GetTaskPayloadRequest` | `GetTaskPayloadResult` | yes | no |
| `tools/call` | `CallToolRequest` | `CallToolResult` | yes | yes |
| `tools/list` | `ListToolsRequest` | `ListToolsResult` | yes | yes |

Under `2025-11-25`, a task-augmented `tools/call` returns `CreateTaskResult`
instead of `CallToolResult`. Under `2026-07-28`, `tools/call`, `prompts/get`,
and `resources/read` may return `InputRequiredResult` instead of their result
record; see the migration guide section on protocol workflow rules.

## Server to client requests

| Method | Request record | Result record | 2025-11-25 | 2026-07-28 |
| --- | --- | --- | --- | --- |
| `elicitation/create` | `ElicitRequest` | `ElicitResult` | yes | embedded |
| `ping` | `PingRequest` | `EmptyResult` | yes | no |
| `roots/list` | `ListRootsRequest` | `ListRootsResult` | yes | embedded |
| `sampling/createMessage` | `CreateMessageRequest` | `CreateMessageResult` | yes | embedded |
| `tasks/cancel` | `CancelTaskRequest` | `CancelTaskResult` | yes | no |
| `tasks/get` | `GetTaskRequest` | `GetTaskResult` | yes | no |
| `tasks/list` | `ListTasksRequest` | `ListTasksResult` | yes | no |
| `tasks/result` | `GetTaskPayloadRequest` | `GetTaskPayloadResult` | yes | no |

`2026-07-28` has no server to client JSON-RPC requests. The three methods marked
"embedded" travel inside an `InputRequiredResult` and are checked with
`Schema::allowsEmbeddedInput()`.

## Client to server notifications

| Method | Record | 2025-11-25 | 2026-07-28 |
| --- | --- | --- | --- |
| `notifications/cancelled` | `CancelledNotification` | yes | `ClientNotification` |
| `notifications/initialized` | `InitializedNotification` | yes | no |
| `notifications/progress` | `ProgressNotification` | yes | no |
| `notifications/roots/list_changed` | `RootsListChangedNotification` | yes | no |
| `notifications/tasks/status` | `TaskStatusNotification` | yes | no |

Under `2026-07-28`, `notifications/cancelled` is the only client notification
and the catalog binds it to the object root `Record\ClientNotification`.
`Record\CancelledNotification` remains available in both revisions for the
concrete message.

## Server to client notifications

| Method | Record | 2025-11-25 | 2026-07-28 |
| --- | --- | --- | --- |
| `notifications/cancelled` | `CancelledNotification` | yes | yes |
| `notifications/elicitation/complete` | `ElicitationCompleteNotification` | yes | no |
| `notifications/message` | `LoggingMessageNotification` | yes | yes |
| `notifications/progress` | `ProgressNotification` | yes | yes |
| `notifications/prompts/list_changed` | `PromptListChangedNotification` | yes | yes |
| `notifications/resources/list_changed` | `ResourceListChangedNotification` | yes | yes |
| `notifications/resources/updated` | `ResourceUpdatedNotification` | yes | yes |
| `notifications/subscriptions/acknowledged` | `SubscriptionsAcknowledgedNotification` | no | yes |
| `notifications/tasks/status` | `TaskStatusNotification` | yes | no |
| `notifications/tools/list_changed` | `ToolListChangedNotification` | yes | yes |

Update this file when a revision is added or removed. The test suite checks
that every method in the catalogs appears here with its record name.

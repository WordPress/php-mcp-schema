# Common Domain Types

## Contents

- [Content](#content) (3 types)
- [Core](#core) (1 types)
- [JsonRpc](#jsonrpc) (14 types)
- [Lifecycle](#lifecycle) (1 types)
- [Protocol](#protocol) (49 types)

## Content

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| AudioContent | Audio provided to or from an LLM | type: "audio", data: string, mimeType: string, +2 more |
| ImageContent | An image provided to or from an LLM | type: "image", data: string, mimeType: string, +2 more |
| TextContent | Text provided to or from an LLM | type: "text", text: string, annotations?: Annotations, +1 more |

### Relationships

- `TextContent` implements `SamplingMessageContentBlockInterface`
- `TextContent` implements `ContentBlockInterface`
- `ImageContent` implements `SamplingMessageContentBlockInterface`
- `ImageContent` implements `ContentBlockInterface`
- `AudioContent` implements `SamplingMessageContentBlockInterface`

## Core

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| Icon | An optionally-sized icon that can be displayed in a user ... | src: string, mimeType?: string, sizes?: string[], +1 more |

## JsonRpc

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| Error | Error data structure | code: number, message: string, data?: unknown |
| JSONRPCErrorResponse | A response to a request that indicates an error occurred | jsonrpc: typeof JSONR..., id?: RequestId, error: Error |
| JSONRPCMessageInterface | Refers to any valid JSON-RPC object that can be decoded o... | - |
| JSONRPCNotification | A notification which does not expect a response | jsonrpc: typeof JSONR... |
| JSONRPCRequest | A request that expects a response | jsonrpc: typeof JSONR..., id: RequestId |
| JSONRPCResponseInterface | A response to a request, containing either the result or ... | - |
| JSONRPCResultResponse | A successful (non-error) response to a request | jsonrpc: typeof JSONR..., id: RequestId, result: Result |
| Notification | Notification for  events | method: string, params?: { [key: stri... |
| NotificationMetaObject | Extends {@link MetaObject} with additional notification-s... | io.modelcontextprotocol/subscriptionId?: RequestId |
| NotificationParams | Common params for any notification | _meta?: Notification... |
| Request | Request for  operation | method: string, params?: { [key: stri... |
| RequestIdInterface | A uniquely identifying ID for a request in JSON-RPC | - |
| RequestMetaObject | Extends {@link MetaObject} with additional request-specif... | progressToken?: ProgressToken, io.modelcontextprotocol/protocolVersion: string, io.modelcontextprotocol/clientInfo?: Implementation, +2 more |
| RequestParams | Common params for any request | _meta: RequestMetaO... |

### Relationships

- `RequestMetaObject` extends `MetaObject`
- `NotificationMetaObject` extends `MetaObject`
- `JSONRPCRequest` extends `Request`
- `JSONRPCRequest` implements `JSONRPCMessageInterface`
- `JSONRPCNotification` extends `Notification`

## Lifecycle

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| Implementation | Describes the MCP implementation | version: string, description?: string, websiteUrl?: string |

### Relationships

- `Implementation` extends `BaseMetadata`

## Protocol

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| Annotations | Optional annotations for the client | audience?: Role[], priority?: number, lastModified?: string |
| BaseMetadata | Base interface for metadata with name (identifier) and ti... | name: string, title?: string |
| BlobResourceContents | Blob Resource Contents data structure | blob: string |
| CacheableResult | A result that supports a time-to-live (TTL) hint for clie... | ttlMs: number, cacheScope: "public" \| "... |
| CancelledNotification | This notification is sent by the client to indicate that ... | method: "notificatio..., params: CancelledNot... |
| CancelledNotificationParams | Parameters for a `notifications/cancelled` notification | requestId: RequestId, reason?: string |
| ClientNotification | Type alias for CancelledNotification | - |
| ClientRequestInterface | Union type: DiscoverRequest \| CompleteRequest \| GetPrompt... | - |
| ContentBlockInterface | Union type: TextContent \| ImageContent \| AudioContent \| R... | - |
| DiscoverRequest | A request from the client asking the server to advertise ... | method: "server/disc..., params: RequestParams |
| DiscoverResult | The result returned by the server for a {@link DiscoverRe... | supportedVersions: string[], capabilities: ServerCapabi..., instructions?: string |
| DiscoverResultResponse | A successful response from the server for a {@link Discov... | result: DiscoverResult |
| EmbeddedResource | The contents of a resource, embedded into a prompt or too... | type: "resource", resource: TextResource..., annotations?: Annotations, +1 more |
| EmptyResult | A result that indicates success but carries no data | - |
| HeaderMismatchError | Returned when a server rejects a request because the valu... | error: Error & {
  ... |
| Icons | Base interface to add `icons` property | icons?: Icon[] |
| InputRequestInterface | Union type: CreateMessageRequest \| ListRootsRequest \| Eli... | - |
| InputRequests | A map of server-initiated requests that the client must f... | - |
| InputRequiredResult | An InputRequiredResult sent by the server to indicate tha... | inputRequests?: InputRequests, requestState?: string |
| InputResponseInterface | Union type: CreateMessageResult \| ListRootsResult \| Elici... | - |
| InputResponseRequestParams | Parameters for InputResponseRequest | inputResponses?: InputResponses, requestState?: string |
| InputResponses | A map of client responses to server-initiated requests | - |
| InternalError | A JSON-RPC error indicating that an internal error occurr... | code: typeof INTER... |
| InvalidParamsError | A JSON-RPC error indicating that the method parameters ar... | code: typeof INVAL... |
| InvalidRequestError | A JSON-RPC error indicating that the request is not a val... | code: typeof INVAL... |
| JSONValueInterface | Union type: JSONObject \| JSONArray | - |
| MethodNotFoundError | A JSON-RPC error indicating that the requested method doe... | code: typeof METHO... |
| MissingRequiredClientCapabilityError | Returned when processing a request requires a capability ... | error: Error & {
  ... |
| PaginatedRequest | Request for Paginated operation | params: PaginatedReq... |
| PaginatedRequestParams | Common params for paginated requests | cursor?: Cursor |
| PaginatedResult | Result from Paginated operation | nextCursor?: Cursor |
| ParseError | A JSON-RPC error indicating that invalid JSON was receive... | code: typeof PARSE... |
| ProgressNotification | An out-of-band notification used to inform the receiver o... | method: "notificatio..., params: ProgressNoti... |
| ProgressNotificationParams | Parameters for a {@link ProgressNotification \| notificati... | progressToken: ProgressToken, progress: number, total?: number, +1 more |
| ProgressTokenInterface | A progress token, used to associate progress notification... | - |
| Result | Common result fields | _meta?: ResultMetaOb..., resultType: ResultType |
| ResultMetaObject | Extends {@link MetaObject} with additional result-specifi... | io.modelcontextprotocol/serverInfo?: Implementation |
| ResultTypeInterface | Indicates the type of a {@link Result} object, allowing t... | - |
| Role | The sender or recipient of messages and data in a convers... | USER: user, ASSISTANT: assistant |
| SubscriptionFilter | The set of notification types a client may opt in to on a | toolsListChanged?: boolean, promptsListChanged?: boolean, resourcesListChanged?: boolean, +1 more |
| SubscriptionsAcknowledgedNotification | Sent by the server to acknowledge that a | method: "notificatio..., params: Subscription... |
| SubscriptionsAcknowledgedNotificationParams | Parameters for a {@link SubscriptionsAcknowledgedNotifica... | notifications: Subscription... |
| SubscriptionsListenRequest | Sent from the client to open a long-lived channel for rec... | method: "subscriptio..., params: Subscription... |
| SubscriptionsListenRequestParams | Parameters for a {@link SubscriptionsListenRequest \| subs... | notifications: Subscription... |
| SubscriptionsListenResult | The response to a {@link SubscriptionsListenRequest \| sub... | _meta: Subscription... |
| SubscriptionsListenResultMetaObject | Extends {@link ResultMetaObject} with the subscription-st... | io.modelcontextprotocol/subscriptionId: RequestId |
| SubscriptionsListenResultResponse | A successful response from the server for a {@link Subscr... | result: Subscription... |
| TextResourceContents | Text Resource Contents data structure | text: string |
| UnsupportedProtocolVersionError | Returned when the request's protocol version is unknown t... | error: Error & {
  ... |

### Relationships

- `ResultMetaObject` extends `MetaObject`
- `ParseError` extends `Error`
- `InvalidRequestError` extends `Error`
- `MethodNotFoundError` extends `Error`
- `InvalidParamsError` extends `Error`

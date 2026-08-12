# Server Domain Types

## Contents

- [Core](#core) (9 types)
- [Lifecycle](#lifecycle) (6 types)
- [Logging](#logging) (3 types)
- [Prompts](#prompts) (11 types)
- [Resources](#resources) (18 types)
- [Tools](#tools) (12 types)

## Core

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| CompleteRequest | A request from the client to the server, to ask for compl... | method: "completion/..., params: CompleteRequ... |
| CompleteRequestParams | Parameters for a `completion/complete` request | ref: PromptRefere..., argument: CompleteRequ..., context?: CompleteRequ... |
| CompleteRequestParamsArgument | The argument's information | name: string, value: string |
| CompleteRequestParamsContext | Additional, optional context for completions | arguments?: { [key: stri... |
| CompleteResult | The result returned by the server for a {@link CompleteRe... | completion: CompleteResu... |
| CompleteResultCompletion | Complete Result Completion data structure | values: string[], total?: number, hasMore?: boolean |
| CompleteResultResponse | A successful response from the server for a {@link Comple... | result: CompleteResult |
| PromptReference | Identifies a prompt | type: "ref/prompt" |
| ResourceTemplateReference | A reference to a resource or resource template definition | type: "ref/resource", uri: string |

### Relationships

- `CompleteRequestParams` extends `RequestParams`
- `CompleteRequest` extends `JSONRPCRequest`
- `CompleteRequest` implements `ClientRequestInterface`
- `CompleteResult` extends `Result`
- `CompleteResult` implements `ServerResultInterface`

## Lifecycle

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| ServerCapabilities | Capabilities that a server may support | experimental?: { [key: stri..., logging?: JSONObject, completions?: JSONObject, +4 more |
| ServerCapabilitiesPrompts | Present if the server offers any prompt templates | listChanged?: boolean |
| ServerCapabilitiesResources | Present if the server offers any resources to read | subscribe?: boolean, listChanged?: boolean |
| ServerCapabilitiesTools | Present if the server offers any tools to call | listChanged?: boolean |
| ServerNotificationInterface | Union type: CancelledNotification \| ProgressNotification ... | - |
| ServerResultInterface | Union type: EmptyResult \| DiscoverResult \| CompleteResult... | - |

## Logging

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| LoggingLevelInterface | The severity of a log message | - |
| LoggingMessageNotification | JSONRPCNotification of a log message passed from server t... | method: "notificatio..., params: LoggingMessa... |
| LoggingMessageNotificationParams | Parameters for a `notifications/message` notification | level: LoggingLevel, logger?: string, data: unknown |

### Relationships

- `LoggingMessageNotificationParams` extends `NotificationParams`
- `LoggingMessageNotification` extends `JSONRPCNotification`
- `LoggingMessageNotification` implements `ServerNotificationInterface`

## Prompts

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| GetPromptRequest | Used by the client to get a prompt provided by the server | method: "prompts/get", params: GetPromptReq... |
| GetPromptRequestParams | Parameters for a `prompts/get` request | name: string, arguments?: { [key: stri... |
| GetPromptResult | The result returned by the server for a {@link GetPromptR... | description?: string, messages: PromptMessage[] |
| GetPromptResultResponse | A successful response from the server for a {@link GetPro... | result: GetPromptRes... |
| ListPromptsRequest | Sent from the client to request a list of prompts and pro... | method: "prompts/list" |
| ListPromptsResult | The result returned by the server for a {@link ListPrompt... | prompts: Prompt[] |
| ListPromptsResultResponse | A successful response from the server for a {@link ListPr... | result: ListPromptsR... |
| Prompt | A prompt or prompt template that the server offers | description?: string, arguments?: PromptArgume..., _meta?: MetaObject |
| PromptArgument | Describes an argument that a prompt can accept | description?: string, required?: boolean |
| PromptListChangedNotification | An optional notification from the server to the client, i... | method: "notificatio..., params?: Notification... |
| PromptMessage | Describes a message returned as part of a prompt | role: Role, content: ContentBlock |

### Relationships

- `ListPromptsRequest` extends `PaginatedRequest`
- `ListPromptsRequest` implements `ClientRequestInterface`
- `ListPromptsResult` extends `PaginatedResult`
- `ListPromptsResult` implements `ServerResultInterface`
- `ListPromptsResultResponse` extends `JSONRPCResultResponse`

## Resources

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| ListResourcesRequest | Sent from the client to request a list of resources the s... | method: "resources/l... |
| ListResourcesResult | The result returned by the server for a {@link ListResour... | resources: Resource[] |
| ListResourcesResultResponse | A successful response from the server for a {@link ListRe... | result: ListResource... |
| ListResourceTemplatesRequest | Sent from the client to request a list of resource templa... | method: "resources/t... |
| ListResourceTemplatesResult | The result returned by the server for a {@link ListResour... | resourceTemplates: ResourceTemp... |
| ListResourceTemplatesResultResponse | A successful response from the server for a {@link ListRe... | result: ListResource... |
| ReadResourceRequest | Sent from the client to the server, to read a specific re... | method: "resources/r..., params: ReadResource... |
| ReadResourceRequestParams | Parameters for a `resources/read` request | - |
| ReadResourceResult | The result returned by the server for a {@link ReadResour... | contents: (TextResourc... |
| ReadResourceResultResponse | A successful response from the server for a {@link ReadRe... | result: ReadResource... |
| Resource | A known resource that the server is capable of reading | uri: string, description?: string, mimeType?: string, +3 more |
| ResourceContents | The contents of a specific resource or sub-resource | uri: string, mimeType?: string, _meta?: MetaObject |
| ResourceLink | A resource that the server is capable of reading, include... | type: "resource_link" |
| ResourceListChangedNotification | An optional notification from the server to the client, i... | method: "notificatio..., params?: Notification... |
| ResourceRequestParams | Common params for resource-related requests | uri: string |
| ResourceTemplate | A template description for resources available on the server | uriTemplate: string, description?: string, mimeType?: string, +2 more |
| ResourceUpdatedNotification | A notification from the server to the client, informing i... | method: "notificatio..., params: ResourceUpda... |
| ResourceUpdatedNotificationParams | Parameters for a `notifications/resources/updated` notifi... | uri: string |

### Relationships

- `ListResourcesRequest` extends `PaginatedRequest`
- `ListResourcesRequest` implements `ClientRequestInterface`
- `ListResourcesResult` extends `PaginatedResult`
- `ListResourcesResult` implements `ServerResultInterface`
- `ListResourcesResultResponse` extends `JSONRPCResultResponse`

## Tools

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| CallToolRequest | Used by the client to invoke a tool provided by the server | method: "tools/call", params: CallToolRequ... |
| CallToolRequestParams | Parameters for a `tools/call` request | name: string, arguments?: { [key: stri... |
| CallToolResult | The result returned by the server for a {@link CallToolRe... | content: ContentBlock[], structuredContent?: unknown, isError?: boolean |
| CallToolResultResponse | A successful response from the server for a {@link CallTo... | result: CallToolResu... |
| ListToolsRequest | Sent from the client to request a list of tools the serve... | method: "tools/list" |
| ListToolsResult | The result returned by the server for a {@link ListToolsR... | tools: Tool[] |
| ListToolsResultResponse | A successful response from the server for a {@link ListTo... | result: ListToolsResult |
| Tool | Definition for a tool the client can call | description?: string, inputSchema: ToolInputSchema, outputSchema?: ToolOutputSc..., +2 more |
| ToolAnnotations | Additional properties describing a {@link Tool} to clients | title?: string, readOnlyHint?: boolean, destructiveHint?: boolean, +2 more |
| ToolInputSchema | A JSON Schema object defining the expected parameters for... | $schema?: string, type: "object" |
| ToolListChangedNotification | An optional notification from the server to the client, i... | method: "notificatio..., params?: Notification... |
| ToolOutputSchema | An optional JSON Schema object defining the structure of ... | $schema?: string |

### Relationships

- `ListToolsRequest` extends `PaginatedRequest`
- `ListToolsRequest` implements `ClientRequestInterface`
- `ListToolsResult` extends `PaginatedResult`
- `ListToolsResult` implements `ServerResultInterface`
- `ListToolsResultResponse` extends `JSONRPCResultResponse`

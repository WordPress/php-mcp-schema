# Client Domain Types

## Contents

- [Elicitation](#elicitation) (20 types)
- [Lifecycle](#lifecycle) (5 types)
- [Roots](#roots) (4 types)
- [Sampling](#sampling) (10 types)

## Elicitation

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| BooleanSchema | Boolean Schema data structure | type: "boolean", title?: string, description?: string, +1 more |
| ElicitRequest | A request from the server to elicit additional informatio... | method: "elicitation..., params: ElicitReques... |
| ElicitRequestFormParams | The parameters for a request to elicit non-sensitive info... | mode?: "form", message: string, requestedSchema: ElicitReques... |
| ElicitRequestFormParamsRequestedSchema | A restricted subset of JSON Schema | $schema?: string, type: "object", required?: string[] |
| ElicitRequestParamsInterface | The parameters for a request to elicit additional informa... | - |
| ElicitRequestURLParams | The parameters for a request to elicit information from t... | mode: "url", message: string, url: string |
| ElicitResult | The result returned by the client for an {@link ElicitReq... | action: "accept" \| "..., content?: { [key: stri... |
| EnumSchemaInterface | Union type: SingleSelectEnumSchema \| MultiSelectEnumSchem... | - |
| LegacyTitledEnumSchema | Use {@link TitledSingleSelectEnumSchema} instead | type: "string", title?: string, description?: string, +3 more |
| MultiSelectEnumSchemaInterface | Union type: UntitledMultiSelectEnumSchema \| TitledMultiSe... | - |
| NumberSchema | Number Schema data structure | type: "number" \| "..., title?: string, description?: string, +3 more |
| PrimitiveSchemaDefinitionInterface | Restricted schema definitions that only allow primitive t... | - |
| SingleSelectEnumSchemaInterface | Union type: UntitledSingleSelectEnumSchema \| TitledSingle... | - |
| StringSchema | String Schema data structure | type: "string", title?: string, description?: string, +4 more |
| TitledMultiSelectEnumSchema | Schema for multiple-selection enumeration with display ti... | type: "array", title?: string, description?: string, +4 more |
| TitledMultiSelectEnumSchemaItems | Schema for array items with enum options and display labels | - |
| TitledSingleSelectEnumSchema | Schema for single-selection enumeration with display titl... | type: "string", title?: string, description?: string, +2 more |
| UntitledMultiSelectEnumSchema | Schema for multiple-selection enumeration without display... | type: "array", title?: string, description?: string, +4 more |
| UntitledMultiSelectEnumSchemaItems | Schema for the array items | type: "string", enum: string[] |
| UntitledSingleSelectEnumSchema | Schema for single-selection enumeration without display t... | type: "string", title?: string, description?: string, +2 more |

### Relationships

- `ElicitRequestFormParams` implements `ElicitRequestParamsInterface`
- `ElicitRequestURLParams` implements `ElicitRequestParamsInterface`
- `ElicitRequest` implements `InputRequestInterface`
- `StringSchema` implements `PrimitiveSchemaDefinitionInterface`
- `NumberSchema` implements `PrimitiveSchemaDefinitionInterface`

## Lifecycle

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| ClientCapabilities | Capabilities a client may support | experimental?: { [key: stri..., roots?: ClientCapabi..., sampling?: ClientCapabi..., +2 more |
| ClientCapabilitiesElicitation | Present if the client supports elicitation from the server | form?: JSONObject, url?: JSONObject |
| ClientCapabilitiesRoots | Present if the client supports listing roots | - |
| ClientCapabilitiesSampling | Present if the client supports sampling from an LLM | context?: JSONObject, tools?: JSONObject |
| ClientResult | Type alias for EmptyResult | - |

### Relationships

- `ClientResult` extends `EmptyResult`

## Roots

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| ListRootsRequest | Sent from the server to request a list of root URIs from ... | method: "roots/list", params?: ListRootsReq... |
| ListRootsRequestParams | Parameters for ListRootsRequest | _meta?: MetaObject |
| ListRootsResult | The result returned by the client for a {@link ListRootsR... | roots: Root[] |
| Root | Represents a root directory or file that the server can o... | uri: string, name?: string, _meta?: MetaObject |

### Relationships

- `ListRootsRequest` implements `InputRequestInterface`
- `ListRootsResult` implements `InputResponseInterface`

## Sampling

### Types

| Type | Purpose | Key Properties |
| --- | --- | --- |
| CreateMessageRequest | A request from the server to sample an LLM via the client | method: "sampling/cr..., params: CreateMessag... |
| CreateMessageRequestParams | Parameters for a `sampling/createMessage` request | messages: SamplingMess..., modelPreferences?: ModelPrefere..., systemPrompt?: string, +7 more |
| CreateMessageResult | The result returned by the client for a {@link CreateMess... | model: string, stopReason?: "endTurn" \| ... |
| ModelHint | Hints to use for model selection | name?: string |
| ModelPreferences | The server's preferences for model selection, requested o... | hints?: ModelHint[], costPriority?: number, speedPriority?: number, +1 more |
| SamplingMessage | Describes a message issued to or received from an LLM API | role: Role, content: SamplingMess..., _meta?: MetaObject |
| SamplingMessageContentBlockInterface | Union type: TextContent \| ImageContent \| AudioContent \| T... | - |
| ToolChoice | Controls tool selection behavior for sampling requests | mode?: "auto" \| "re... |
| ToolResultContent | The result of a tool use, provided by the user back to th... | type: "tool_result", toolUseId: string, content: ContentBlock[], +3 more |
| ToolUseContent | A request from the assistant to call a tool | type: "tool_use", id: string, name: string, +2 more |

### Relationships

- `CreateMessageRequest` implements `InputRequestInterface`
- `CreateMessageResult` extends `SamplingMessage`
- `CreateMessageResult` implements `InputResponseInterface`
- `ToolUseContent` implements `SamplingMessageContentBlockInterface`
- `ToolResultContent` implements `SamplingMessageContentBlockInterface`

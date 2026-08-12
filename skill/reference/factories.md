# Factory Classes Reference

Factories instantiate the correct DTO based on discriminator values.

## Common Factories

### InputRequestFactory

- **Interface:** `InputRequestInterface`
- **Discriminator:** `method`

**Mappings:**

| Value | Type |
| --- | --- |
| `sampling/createMessage` | CreateMessageRequest |
| `roots/list` | ListRootsRequest |
| `elicitation/create` | ElicitRequest |

### ContentBlockFactory

- **Interface:** `ContentBlockInterface`
- **Discriminator:** `type`

**Mappings:**

| Value | Type |
| --- | --- |
| `text` | TextContent |
| `image` | ImageContent |
| `audio` | AudioContent |
| `resource_link` | ResourceLink |
| `resource` | EmbeddedResource |

### ClientRequestFactory

- **Interface:** `ClientRequestInterface`
- **Discriminator:** `method`

**Mappings:**

| Value | Type |
| --- | --- |
| `server/discover` | DiscoverRequest |
| `completion/complete` | CompleteRequest |
| `prompts/get` | GetPromptRequest |
| `prompts/list` | ListPromptsRequest |
| `resources/list` | ListResourcesRequest |
| `resources/templates/list` | ListResourceTemplatesRequest |
| `resources/read` | ReadResourceRequest |
| `subscriptions/listen` | SubscriptionsListenRequest |
| `tools/call` | CallToolRequest |
| `tools/list` | ListToolsRequest |


## Client Factories

### SamplingMessageContentBlockFactory

- **Interface:** `SamplingMessageContentBlockInterface`
- **Discriminator:** `type`

**Mappings:**

| Value | Type |
| --- | --- |
| `text` | TextContent |
| `image` | ImageContent |
| `audio` | AudioContent |
| `tool_use` | ToolUseContent |
| `tool_result` | ToolResultContent |

### ElicitRequestParamsFactory

- **Interface:** `ElicitRequestParamsInterface`
- **Discriminator:** `mode`

**Mappings:**

| Value | Type |
| --- | --- |
| `form` | ElicitRequestFormParams |
| `url` | ElicitRequestURLParams |

### PrimitiveSchemaDefinitionFactory

- **Interface:** `PrimitiveSchemaDefinitionInterface`
- **Discriminator:** `type`

**Mappings:**

| Value | Type |
| --- | --- |
| `string` | StringSchema |
| `number" \| "integer` | NumberSchema |
| `boolean` | BooleanSchema |

### SingleSelectEnumSchemaFactory

- **Interface:** `SingleSelectEnumSchemaInterface`
- **Discriminator:** `type`

**Mappings:**

| Value | Type |
| --- | --- |
| `string` | TitledSingleSelectEnumSchema |

### MultiSelectEnumSchemaFactory

- **Interface:** `MultiSelectEnumSchemaInterface`
- **Discriminator:** `type`

**Mappings:**

| Value | Type |
| --- | --- |
| `array` | TitledMultiSelectEnumSchema |

### EnumSchemaFactory

- **Interface:** `EnumSchemaInterface`
- **Discriminator:** `type`

**Mappings:**

| Value | Type |
| --- | --- |
| `string` | LegacyTitledEnumSchema |


## Server Factories

### ServerNotificationFactory

- **Interface:** `ServerNotificationInterface`
- **Discriminator:** `method`

**Mappings:**

| Value | Type |
| --- | --- |
| `notifications/cancelled` | CancelledNotification |
| `notifications/progress` | ProgressNotification |
| `notifications/message` | LoggingMessageNotification |
| `notifications/resources/updated` | ResourceUpdatedNotification |
| `notifications/resources/list_changed` | ResourceListChangedNotification |
| `notifications/tools/list_changed` | ToolListChangedNotification |
| `notifications/prompts/list_changed` | PromptListChangedNotification |
| `notifications/subscriptions/acknowledged` | SubscriptionsAcknowledgedNotification |

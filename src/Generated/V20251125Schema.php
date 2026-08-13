<?php

declare(strict_types=1);

namespace WP\McpSchema\Generated;

use WP\McpSchema\Contract\Type;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Runtime\GenericRevisionSchema;

/**
 * Generated discoverable catalog for MCP 2025-11-25.
 *
 * @phpstan-type AnnotationsWire array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}
 * @phpstan-type AnnotationsFields array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}
 * @phpstan-type AudioContentWire array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}
 * @phpstan-type AudioContentFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'audio'}
 * @phpstan-type BaseMetadataWire array{name: string, title?: string}
 * @phpstan-type BaseMetadataFields array{name: string, title?: string}
 * @phpstan-type BlobResourceContentsWire array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string, blob: string}
 * @phpstan-type BlobResourceContentsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string, blob: string}
 * @phpstan-type BooleanSchemaWire array{default?: bool, description?: string, title?: string, type: 'boolean'}
 * @phpstan-type BooleanSchemaFields array{default?: bool, description?: string, title?: string, type: 'boolean'}
 * @phpstan-type CallToolRequestWire array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CallToolRequestFields array{method: 'tools/call', params: Record<CallToolRequestParamsWire, CallToolRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CallToolRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, arguments?: array{...<string, mixed>}, name: string}
 * @phpstan-type CallToolRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}
 * @phpstan-type CallToolResultWire array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, ...<string, mixed>}
 * @phpstan-type CallToolResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<ContentBlockWire, ContentBlockFields>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type CancelTaskRequestWire array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CancelTaskRequestFields array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CancelTaskResultWire array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}
 * @phpstan-type CancelTaskResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}
 * @phpstan-type CancelledNotificationWire array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type CancelledNotificationFields array{method: 'notifications/cancelled', params: Record<CancelledNotificationParamsWire, CancelledNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type CancelledNotificationParamsWire array{_meta?: array{...<string, mixed>}, reason?: string, requestId?: string|int|float}
 * @phpstan-type CancelledNotificationParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, reason?: string, requestId?: string|int|float}
 * @phpstan-type ClientCapabilitiesWire array{elicitation?: array{form?: array<string, mixed>, url?: array<string, mixed>}, experimental?: array{...<string, array<string, mixed>>}, roots?: array{listChanged?: bool}, sampling?: array{context?: array<string, mixed>, tools?: array<string, mixed>}, tasks?: array{cancel?: array<string, mixed>, list?: array<string, mixed>, requests?: array{elicitation?: array{create?: array<string, mixed>}, sampling?: array{createMessage?: array<string, mixed>}}}}
 * @phpstan-type ClientCapabilitiesFields array{elicitation?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, roots?: Record<array<string, mixed>, array<string, mixed>>, sampling?: Record<array<string, mixed>, array<string, mixed>>, tasks?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ClientNotificationWire array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/initialized', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/roots/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ClientNotificationFields array{method: 'notifications/cancelled', params: Record<CancelledNotificationParamsWire, CancelledNotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<ProgressNotificationParamsWire, ProgressNotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/initialized', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/roots/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: Record<TaskStatusNotificationParamsWire, TaskStatusNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ClientRequestWire array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'initialize', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'logging/setLevel', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/subscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/unsubscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ClientRequestFields array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'initialize', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'logging/setLevel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/subscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/unsubscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ClientResultWire array{_meta?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, roots: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ClientResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>|list<Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, roots: list<Record<RootWire, RootFields>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<TaskWire, TaskFields>>, ...<string, mixed>}
 * @phpstan-type CompleteRequestWire array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CompleteRequestFields array{method: 'completion/complete', params: Record<CompleteRequestParamsWire, CompleteRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CompleteRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, argument: array{name: string, value: string}, context?: array{arguments?: array{...<string, string>}}, ref: array<string, mixed>}
 * @phpstan-type CompleteRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, argument: Record<array<string, mixed>, array<string, mixed>>, context?: Record<array<string, mixed>, array<string, mixed>>, ref: Record<PromptReferenceWire, PromptReferenceFields>|Record<ResourceTemplateReferenceWire, ResourceTemplateReferenceFields>}
 * @phpstan-type CompleteResultWire array{_meta?: array{...<string, mixed>}, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}
 * @phpstan-type CompleteResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ContentBlockWire array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}
 * @phpstan-type ContentBlockFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, text: string, type: 'text'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, resource: Record<TextResourceContentsWire, TextResourceContentsFields>|Record<BlobResourceContentsWire, BlobResourceContentsFields>, type: 'resource'}
 * @phpstan-type CreateMessageRequestWire array{method: 'sampling/createMessage', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CreateMessageRequestFields array{method: 'sampling/createMessage', params: Record<CreateMessageRequestParamsWire, CreateMessageRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CreateMessageRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<array<string, mixed>>, metadata?: array<string, mixed>, modelPreferences?: array<string, mixed>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: array<string, mixed>, tools?: list<array<string, mixed>>}
 * @phpstan-type CreateMessageRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>, includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<Record<SamplingMessageWire, SamplingMessageFields>>, metadata?: Record<array<string, mixed>, array<string, mixed>>, modelPreferences?: Record<ModelPreferencesWire, ModelPreferencesFields>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: Record<ToolChoiceWire, ToolChoiceFields>, tools?: list<Record<ToolWire, ToolFields>>}
 * @phpstan-type CreateMessageResultWire array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}
 * @phpstan-type CreateMessageResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>|list<Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}
 * @phpstan-type CreateTaskResultWire array{_meta?: array{...<string, mixed>}, task: array<string, mixed>, ...<string, mixed>}
 * @phpstan-type CreateTaskResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task: Record<TaskWire, TaskFields>, ...<string, mixed>}
 * @phpstan-type ElicitRequestWire array{method: 'elicitation/create', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ElicitRequestFields array{method: 'elicitation/create', params: Record<ElicitRequestParamsWire, ElicitRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ElicitRequestFormParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}
 * @phpstan-type ElicitRequestFormParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>, message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ElicitRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}|array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, elicitationId: string, message: string, mode: 'url', url: string}
 * @phpstan-type ElicitRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>, message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>, elicitationId: string, message: string, mode: 'url', url: string}
 * @phpstan-type ElicitRequestURLParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, elicitationId: string, message: string, mode: 'url', url: string}
 * @phpstan-type ElicitRequestURLParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>, elicitationId: string, message: string, mode: 'url', url: string}
 * @phpstan-type ElicitResultWire array{_meta?: array{...<string, mixed>}, action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}, ...<string, mixed>}
 * @phpstan-type ElicitResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ElicitationCompleteNotificationWire array{method: 'notifications/elicitation/complete', params: array{elicitationId: string}, jsonrpc: '2.0'}
 * @phpstan-type ElicitationCompleteNotificationFields array{method: 'notifications/elicitation/complete', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}
 * @phpstan-type EmbeddedResourceWire array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}
 * @phpstan-type EmbeddedResourceFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, resource: Record<TextResourceContentsWire, TextResourceContentsFields>|Record<BlobResourceContentsWire, BlobResourceContentsFields>, type: 'resource'}
 * @phpstan-type EmptyResultWire array{_meta?: array{...<string, mixed>}, ...<string, mixed>}
 * @phpstan-type EmptyResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type EnumSchemaWire array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type EnumSchemaFields array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type ErrorWire array{code: int|float, data?: mixed, message: string}
 * @phpstan-type ErrorFields array{code: int|float, data?: mixed, message: string}
 * @phpstan-type GetPromptRequestWire array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetPromptRequestFields array{method: 'prompts/get', params: Record<GetPromptRequestParamsWire, GetPromptRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetPromptRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, arguments?: array{...<string, string>}, name: string}
 * @phpstan-type GetPromptRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}
 * @phpstan-type GetPromptResultWire array{_meta?: array{...<string, mixed>}, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type GetPromptResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, description?: string, messages: list<Record<PromptMessageWire, PromptMessageFields>>, ...<string, mixed>}
 * @phpstan-type GetTaskPayloadRequestWire array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetTaskPayloadRequestFields array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetTaskPayloadResultWire array{_meta?: array{...<string, mixed>}, ...<string, mixed>}
 * @phpstan-type GetTaskPayloadResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type GetTaskRequestWire array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetTaskRequestFields array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetTaskResultWire array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}
 * @phpstan-type GetTaskResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}
 * @phpstan-type IconWire array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}
 * @phpstan-type IconFields array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}
 * @phpstan-type IconsWire array{icons?: list<array<string, mixed>>}
 * @phpstan-type IconsFields array{icons?: list<Record<IconWire, IconFields>>}
 * @phpstan-type ImageContentWire array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}
 * @phpstan-type ImageContentFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'image'}
 * @phpstan-type ImplementationWire array{name: string, title?: string, icons?: list<array<string, mixed>>, description?: string, version: string, websiteUrl?: string}
 * @phpstan-type ImplementationFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, description?: string, version: string, websiteUrl?: string}
 * @phpstan-type InitializeRequestWire array{method: 'initialize', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type InitializeRequestFields array{method: 'initialize', params: Record<InitializeRequestParamsWire, InitializeRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type InitializeRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, capabilities: array<string, mixed>, clientInfo: array<string, mixed>, protocolVersion: string}
 * @phpstan-type InitializeRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<ClientCapabilitiesWire, ClientCapabilitiesFields>, clientInfo: Record<ImplementationWire, ImplementationFields>, protocolVersion: string}
 * @phpstan-type InitializeResultWire array{_meta?: array{...<string, mixed>}, capabilities: array<string, mixed>, instructions?: string, protocolVersion: string, serverInfo: array<string, mixed>, ...<string, mixed>}
 * @phpstan-type InitializeResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<ServerCapabilitiesWire, ServerCapabilitiesFields>, instructions?: string, protocolVersion: string, serverInfo: Record<ImplementationWire, ImplementationFields>, ...<string, mixed>}
 * @phpstan-type InitializedNotificationWire array{method: 'notifications/initialized', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type InitializedNotificationFields array{method: 'notifications/initialized', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCErrorResponseWire array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCErrorResponseFields array{error: Record<ErrorWire, ErrorFields>, id?: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCMessageWire array{method: string, params?: array{...<string, mixed>}, id: string|int|float, jsonrpc: '2.0'}|array{method: string, params?: array{...<string, mixed>}, jsonrpc: '2.0'}|array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}|array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCMessageFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{id: string|int|float, jsonrpc: '2.0', result: Record<ResultWire, ResultFields>}|array{error: Record<ErrorWire, ErrorFields>, id?: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCNotificationWire array{method: string, params?: array{...<string, mixed>}, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCNotificationFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCRequestWire array{method: string, params?: array{...<string, mixed>}, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCRequestFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}|array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ResultWire, ResultFields>}|array{error: Record<ErrorWire, ErrorFields>, id?: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type JSONRPCResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type JSONRPCResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ResultWire, ResultFields>}
 * @phpstan-type LegacyTitledEnumSchemaWire array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type LegacyTitledEnumSchemaFields array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type ListPromptsRequestWire array{method: 'prompts/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListPromptsRequestFields array{method: 'prompts/list', params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListPromptsResultWire array{_meta?: array{...<string, mixed>}, nextCursor?: string, prompts: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListPromptsResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, prompts: list<Record<PromptWire, PromptFields>>, ...<string, mixed>}
 * @phpstan-type ListResourceTemplatesRequestWire array{method: 'resources/templates/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourceTemplatesRequestFields array{method: 'resources/templates/list', params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourceTemplatesResultWire array{_meta?: array{...<string, mixed>}, nextCursor?: string, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListResourceTemplatesResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resourceTemplates: list<Record<ResourceTemplateWire, ResourceTemplateFields>>, ...<string, mixed>}
 * @phpstan-type ListResourcesRequestWire array{method: 'resources/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourcesRequestFields array{method: 'resources/list', params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourcesResultWire array{_meta?: array{...<string, mixed>}, nextCursor?: string, resources: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListResourcesResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resources: list<Record<ResourceWire, ResourceFields>>, ...<string, mixed>}
 * @phpstan-type ListRootsRequestWire array{method: 'roots/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListRootsRequestFields array{method: 'roots/list', params?: Record<RequestParamsWire, RequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListRootsResultWire array{_meta?: array{...<string, mixed>}, roots: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListRootsResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, roots: list<Record<RootWire, RootFields>>, ...<string, mixed>}
 * @phpstan-type ListTasksRequestWire array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListTasksRequestFields array{method: 'tasks/list', params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListTasksResultWire array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListTasksResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<TaskWire, TaskFields>>, ...<string, mixed>}
 * @phpstan-type ListToolsRequestWire array{method: 'tools/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListToolsRequestFields array{method: 'tools/list', params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListToolsResultWire array{_meta?: array{...<string, mixed>}, nextCursor?: string, tools: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListToolsResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tools: list<Record<ToolWire, ToolFields>>, ...<string, mixed>}
 * @phpstan-type LoggingMessageNotificationWire array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type LoggingMessageNotificationFields array{method: 'notifications/message', params: Record<LoggingMessageNotificationParamsWire, LoggingMessageNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type LoggingMessageNotificationParamsWire array{_meta?: array{...<string, mixed>}, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}
 * @phpstan-type LoggingMessageNotificationParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}
 * @phpstan-type ModelHintWire array{name?: string}
 * @phpstan-type ModelHintFields array{name?: string}
 * @phpstan-type ModelPreferencesWire array{costPriority?: int|float, hints?: list<array<string, mixed>>, intelligencePriority?: int|float, speedPriority?: int|float}
 * @phpstan-type ModelPreferencesFields array{costPriority?: int|float, hints?: list<Record<ModelHintWire, ModelHintFields>>, intelligencePriority?: int|float, speedPriority?: int|float}
 * @phpstan-type MultiSelectEnumSchemaWire array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type MultiSelectEnumSchemaFields array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type NotificationWire array{method: string, params?: array{...<string, mixed>}}
 * @phpstan-type NotificationFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type NotificationParamsWire array{_meta?: array{...<string, mixed>}}
 * @phpstan-type NotificationParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type NumberSchemaWire array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}
 * @phpstan-type NumberSchemaFields array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}
 * @phpstan-type PaginatedRequestWire array{method: string, params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type PaginatedRequestFields array{method: string, params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type PaginatedRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, cursor?: string}
 * @phpstan-type PaginatedRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, cursor?: string}
 * @phpstan-type PaginatedResultWire array{_meta?: array{...<string, mixed>}, nextCursor?: string, ...<string, mixed>}
 * @phpstan-type PaginatedResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, ...<string, mixed>}
 * @phpstan-type PingRequestWire array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type PingRequestFields array{method: 'ping', params?: Record<RequestParamsWire, RequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type PrimitiveSchemaDefinitionWire array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type PrimitiveSchemaDefinitionFields array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type ProgressNotificationWire array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ProgressNotificationFields array{method: 'notifications/progress', params: Record<ProgressNotificationParamsWire, ProgressNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ProgressNotificationParamsWire array{_meta?: array{...<string, mixed>}, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}
 * @phpstan-type ProgressNotificationParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}
 * @phpstan-type PromptWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, arguments?: list<array<string, mixed>>, description?: string}
 * @phpstan-type PromptFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, arguments?: list<Record<PromptArgumentWire, PromptArgumentFields>>, description?: string}
 * @phpstan-type PromptArgumentWire array{name: string, title?: string, description?: string, required?: bool}
 * @phpstan-type PromptArgumentFields array{name: string, title?: string, description?: string, required?: bool}
 * @phpstan-type PromptListChangedNotificationWire array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type PromptListChangedNotificationFields array{method: 'notifications/prompts/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type PromptMessageWire array{content: array<string, mixed>, role: 'user'|'assistant'}
 * @phpstan-type PromptMessageFields array{content: Record<ContentBlockWire, ContentBlockFields>, role: 'user'|'assistant'}
 * @phpstan-type PromptReferenceWire array{name: string, title?: string, type: 'ref/prompt'}
 * @phpstan-type PromptReferenceFields array{name: string, title?: string, type: 'ref/prompt'}
 * @phpstan-type ReadResourceRequestWire array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ReadResourceRequestFields array{method: 'resources/read', params: Record<ReadResourceRequestParamsWire, ReadResourceRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ReadResourceRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}
 * @phpstan-type ReadResourceRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}
 * @phpstan-type ReadResourceResultWire array{_meta?: array{...<string, mixed>}, contents: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ReadResourceResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, contents: list<Record<TextResourceContentsWire, TextResourceContentsFields>|Record<BlobResourceContentsWire, BlobResourceContentsFields>>, ...<string, mixed>}
 * @phpstan-type RelatedTaskMetadataWire array{taskId: string}
 * @phpstan-type RelatedTaskMetadataFields array{taskId: string}
 * @phpstan-type RequestWire array{method: string, params?: array{...<string, mixed>}}
 * @phpstan-type RequestFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type RequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}}
 * @phpstan-type RequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ResourceWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string}
 * @phpstan-type ResourceFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, size?: int|float, uri: string}
 * @phpstan-type ResourceContentsWire array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string}
 * @phpstan-type ResourceContentsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string}
 * @phpstan-type ResourceLinkWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}
 * @phpstan-type ResourceLinkFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}
 * @phpstan-type ResourceListChangedNotificationWire array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ResourceListChangedNotificationFields array{method: 'notifications/resources/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ResourceRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}
 * @phpstan-type ResourceRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}
 * @phpstan-type ResourceTemplateWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, uriTemplate: string}
 * @phpstan-type ResourceTemplateFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, uriTemplate: string}
 * @phpstan-type ResourceTemplateReferenceWire array{type: 'ref/resource', uri: string}
 * @phpstan-type ResourceTemplateReferenceFields array{type: 'ref/resource', uri: string}
 * @phpstan-type ResourceUpdatedNotificationWire array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ResourceUpdatedNotificationFields array{method: 'notifications/resources/updated', params: Record<ResourceUpdatedNotificationParamsWire, ResourceUpdatedNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ResourceUpdatedNotificationParamsWire array{_meta?: array{...<string, mixed>}, uri: string}
 * @phpstan-type ResourceUpdatedNotificationParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}
 * @phpstan-type ResultWire array{_meta?: array{...<string, mixed>}, ...<string, mixed>}
 * @phpstan-type ResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type RootWire array{_meta?: array{...<string, mixed>}, name?: string, uri: string}
 * @phpstan-type RootFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, name?: string, uri: string}
 * @phpstan-type RootsListChangedNotificationWire array{method: 'notifications/roots/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type RootsListChangedNotificationFields array{method: 'notifications/roots/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type SamplingMessageWire array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant'}
 * @phpstan-type SamplingMessageFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>|list<Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>>, role: 'user'|'assistant'}
 * @phpstan-type SamplingMessageContentBlockWire array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{_meta?: array{...<string, mixed>}, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}|array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, toolUseId: string, type: 'tool_result'}
 * @phpstan-type SamplingMessageContentBlockFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, text: string, type: 'text'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'audio'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<ContentBlockWire, ContentBlockFields>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, toolUseId: string, type: 'tool_result'}
 * @phpstan-type ServerCapabilitiesWire array{completions?: array<string, mixed>, experimental?: array{...<string, array<string, mixed>>}, logging?: array<string, mixed>, prompts?: array{listChanged?: bool}, resources?: array{listChanged?: bool, subscribe?: bool}, tasks?: array{cancel?: array<string, mixed>, list?: array<string, mixed>, requests?: array{tools?: array{call?: array<string, mixed>}}}, tools?: array{listChanged?: bool}}
 * @phpstan-type ServerCapabilitiesFields array{completions?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, logging?: Record<array<string, mixed>, array<string, mixed>>, prompts?: Record<array<string, mixed>, array<string, mixed>>, resources?: Record<array<string, mixed>, array<string, mixed>>, tasks?: Record<array<string, mixed>, array<string, mixed>>, tools?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ServerNotificationWire array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/elicitation/complete', params: array{elicitationId: string}, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ServerNotificationFields array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/elicitation/complete', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}
 * @phpstan-type ServerRequestWire array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'sampling/createMessage', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'roots/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'elicitation/create', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ServerRequestFields array{method: 'ping', params?: Record<RequestParamsWire, RequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'sampling/createMessage', params: Record<CreateMessageRequestParamsWire, CreateMessageRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'roots/list', params?: Record<RequestParamsWire, RequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'elicitation/create', params: Record<ElicitRequestParamsWire, ElicitRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ServerResultWire array{_meta?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, capabilities: array<string, mixed>, instructions?: string, protocolVersion: string, serverInfo: array<string, mixed>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, prompts: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, resources: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, contents: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tools: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ServerResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, instructions?: string, protocolVersion: string, serverInfo: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, description?: string, messages: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, prompts: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resourceTemplates: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resources: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, contents: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tools: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}
 * @phpstan-type SetLevelRequestWire array{method: 'logging/setLevel', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type SetLevelRequestFields array{method: 'logging/setLevel', params: Record<SetLevelRequestParamsWire, SetLevelRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type SetLevelRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'}
 * @phpstan-type SetLevelRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'}
 * @phpstan-type SingleSelectEnumSchemaWire array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}
 * @phpstan-type SingleSelectEnumSchemaFields array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}
 * @phpstan-type StringSchemaWire array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}
 * @phpstan-type StringSchemaFields array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}
 * @phpstan-type SubscribeRequestWire array{method: 'resources/subscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type SubscribeRequestFields array{method: 'resources/subscribe', params: Record<SubscribeRequestParamsWire, SubscribeRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type SubscribeRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}
 * @phpstan-type SubscribeRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}
 * @phpstan-type TaskWire array{createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}
 * @phpstan-type TaskFields array{createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}
 * @phpstan-type TaskAugmentedRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>}
 * @phpstan-type TaskAugmentedRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<TaskMetadataWire, TaskMetadataFields>}
 * @phpstan-type TaskMetadataWire array{ttl?: int|float}
 * @phpstan-type TaskMetadataFields array{ttl?: int|float}
 * @phpstan-type TaskStatusNotificationWire array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type TaskStatusNotificationFields array{method: 'notifications/tasks/status', params: Record<TaskStatusNotificationParamsWire, TaskStatusNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type TaskStatusNotificationParamsWire array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}
 * @phpstan-type TaskStatusNotificationParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}
 * @phpstan-type TextContentWire array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}
 * @phpstan-type TextContentFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<AnnotationsWire, AnnotationsFields>, text: string, type: 'text'}
 * @phpstan-type TextResourceContentsWire array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string, text: string}
 * @phpstan-type TextResourceContentsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string, text: string}
 * @phpstan-type TitledMultiSelectEnumSchemaWire array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type TitledMultiSelectEnumSchemaFields array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type TitledSingleSelectEnumSchemaWire array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}
 * @phpstan-type TitledSingleSelectEnumSchemaFields array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}
 * @phpstan-type ToolWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, execution?: array<string, mixed>, inputSchema: array{'$schema'?: string, properties?: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}, outputSchema?: array{'$schema'?: string, properties?: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}
 * @phpstan-type ToolFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<ToolAnnotationsWire, ToolAnnotationsFields>, description?: string, execution?: Record<ToolExecutionWire, ToolExecutionFields>, inputSchema: Record<array<string, mixed>, array<string, mixed>>, outputSchema?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ToolAnnotationsWire array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}
 * @phpstan-type ToolAnnotationsFields array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}
 * @phpstan-type ToolChoiceWire array{mode?: 'auto'|'required'|'none'}
 * @phpstan-type ToolChoiceFields array{mode?: 'auto'|'required'|'none'}
 * @phpstan-type ToolExecutionWire array{taskSupport?: 'forbidden'|'optional'|'required'}
 * @phpstan-type ToolExecutionFields array{taskSupport?: 'forbidden'|'optional'|'required'}
 * @phpstan-type ToolListChangedNotificationWire array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ToolListChangedNotificationFields array{method: 'notifications/tools/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ToolResultContentWire array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, toolUseId: string, type: 'tool_result'}
 * @phpstan-type ToolResultContentFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<ContentBlockWire, ContentBlockFields>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, toolUseId: string, type: 'tool_result'}
 * @phpstan-type ToolUseContentWire array{_meta?: array{...<string, mixed>}, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}
 * @phpstan-type ToolUseContentFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}
 * @phpstan-type URLElicitationRequiredErrorWire array{id?: string|int|float, jsonrpc: '2.0', error: array{code: -32042, data: array{elicitations: list<array<string, mixed>>, ...<string, mixed>}, message: string}}
 * @phpstan-type URLElicitationRequiredErrorFields array{id?: string|int|float, jsonrpc: '2.0', error: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type UnsubscribeRequestWire array{method: 'resources/unsubscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type UnsubscribeRequestFields array{method: 'resources/unsubscribe', params: Record<UnsubscribeRequestParamsWire, UnsubscribeRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type UnsubscribeRequestParamsWire array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}
 * @phpstan-type UnsubscribeRequestParamsFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}
 * @phpstan-type UntitledMultiSelectEnumSchemaWire array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type UntitledMultiSelectEnumSchemaFields array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type UntitledSingleSelectEnumSchemaWire array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}
 * @phpstan-type UntitledSingleSelectEnumSchemaFields array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}
 */
final class V20251125Schema extends GenericRevisionSchema
{
    public const REVISION = '2025-11-25';

    public function __construct()
    {
        $manifest = DescriptorPool::manifests()[self::REVISION];
        parent::__construct(
            self::REVISION,
            DescriptorPool::descriptors(),
            $manifest['types'],
            $manifest['roots'],
            $manifest['fingerprint']
        );
    }

    /** @return Type<AnnotationsWire, AnnotationsFields> */
    public function annotations(): Type
    {
        /** @var Type<AnnotationsWire, AnnotationsFields> $type */
        $type = $this->type('Annotations');
        return $type;
    }


    /** @return Type<AudioContentWire, AudioContentFields> */
    public function audioContent(): Type
    {
        /** @var Type<AudioContentWire, AudioContentFields> $type */
        $type = $this->type('AudioContent');
        return $type;
    }


    /** @return Type<BaseMetadataWire, BaseMetadataFields> */
    public function baseMetadata(): Type
    {
        /** @var Type<BaseMetadataWire, BaseMetadataFields> $type */
        $type = $this->type('BaseMetadata');
        return $type;
    }


    /** @return Type<BlobResourceContentsWire, BlobResourceContentsFields> */
    public function blobResourceContents(): Type
    {
        /** @var Type<BlobResourceContentsWire, BlobResourceContentsFields> $type */
        $type = $this->type('BlobResourceContents');
        return $type;
    }


    /** @return Type<BooleanSchemaWire, BooleanSchemaFields> */
    public function booleanSchema(): Type
    {
        /** @var Type<BooleanSchemaWire, BooleanSchemaFields> $type */
        $type = $this->type('BooleanSchema');
        return $type;
    }


    /** @return Type<CallToolRequestWire, CallToolRequestFields> */
    public function callToolRequest(): Type
    {
        /** @var Type<CallToolRequestWire, CallToolRequestFields> $type */
        $type = $this->type('CallToolRequest');
        return $type;
    }


    /** @return Type<CallToolRequestParamsWire, CallToolRequestParamsFields> */
    public function callToolRequestParams(): Type
    {
        /** @var Type<CallToolRequestParamsWire, CallToolRequestParamsFields> $type */
        $type = $this->type('CallToolRequestParams');
        return $type;
    }


    /** @return Type<CallToolResultWire, CallToolResultFields> */
    public function callToolResult(): Type
    {
        /** @var Type<CallToolResultWire, CallToolResultFields> $type */
        $type = $this->type('CallToolResult');
        return $type;
    }


    /** @return Type<CancelTaskRequestWire, CancelTaskRequestFields> */
    public function cancelTaskRequest(): Type
    {
        /** @var Type<CancelTaskRequestWire, CancelTaskRequestFields> $type */
        $type = $this->type('CancelTaskRequest');
        return $type;
    }


    /** @return Type<CancelTaskResultWire, CancelTaskResultFields> */
    public function cancelTaskResult(): Type
    {
        /** @var Type<CancelTaskResultWire, CancelTaskResultFields> $type */
        $type = $this->type('CancelTaskResult');
        return $type;
    }


    /** @return Type<CancelledNotificationWire, CancelledNotificationFields> */
    public function cancelledNotification(): Type
    {
        /** @var Type<CancelledNotificationWire, CancelledNotificationFields> $type */
        $type = $this->type('CancelledNotification');
        return $type;
    }


    /** @return Type<CancelledNotificationParamsWire, CancelledNotificationParamsFields> */
    public function cancelledNotificationParams(): Type
    {
        /** @var Type<CancelledNotificationParamsWire, CancelledNotificationParamsFields> $type */
        $type = $this->type('CancelledNotificationParams');
        return $type;
    }


    /** @return Type<ClientCapabilitiesWire, ClientCapabilitiesFields> */
    public function clientCapabilities(): Type
    {
        /** @var Type<ClientCapabilitiesWire, ClientCapabilitiesFields> $type */
        $type = $this->type('ClientCapabilities');
        return $type;
    }


    /** @return Type<ClientNotificationWire, ClientNotificationFields> */
    public function clientNotification(): Type
    {
        /** @var Type<ClientNotificationWire, ClientNotificationFields> $type */
        $type = $this->type('ClientNotification');
        return $type;
    }


    /** @return Type<ClientRequestWire, ClientRequestFields> */
    public function clientRequest(): Type
    {
        /** @var Type<ClientRequestWire, ClientRequestFields> $type */
        $type = $this->type('ClientRequest');
        return $type;
    }


    /** @return Type<ClientResultWire, ClientResultFields> */
    public function clientResult(): Type
    {
        /** @var Type<ClientResultWire, ClientResultFields> $type */
        $type = $this->type('ClientResult');
        return $type;
    }


    /** @return Type<CompleteRequestWire, CompleteRequestFields> */
    public function completeRequest(): Type
    {
        /** @var Type<CompleteRequestWire, CompleteRequestFields> $type */
        $type = $this->type('CompleteRequest');
        return $type;
    }


    /** @return Type<CompleteRequestParamsWire, CompleteRequestParamsFields> */
    public function completeRequestParams(): Type
    {
        /** @var Type<CompleteRequestParamsWire, CompleteRequestParamsFields> $type */
        $type = $this->type('CompleteRequestParams');
        return $type;
    }


    /** @return Type<CompleteResultWire, CompleteResultFields> */
    public function completeResult(): Type
    {
        /** @var Type<CompleteResultWire, CompleteResultFields> $type */
        $type = $this->type('CompleteResult');
        return $type;
    }


    /** @return Type<ContentBlockWire, ContentBlockFields> */
    public function contentBlock(): Type
    {
        /** @var Type<ContentBlockWire, ContentBlockFields> $type */
        $type = $this->type('ContentBlock');
        return $type;
    }


    /** @return Type<CreateMessageRequestWire, CreateMessageRequestFields> */
    public function createMessageRequest(): Type
    {
        /** @var Type<CreateMessageRequestWire, CreateMessageRequestFields> $type */
        $type = $this->type('CreateMessageRequest');
        return $type;
    }


    /** @return Type<CreateMessageRequestParamsWire, CreateMessageRequestParamsFields> */
    public function createMessageRequestParams(): Type
    {
        /** @var Type<CreateMessageRequestParamsWire, CreateMessageRequestParamsFields> $type */
        $type = $this->type('CreateMessageRequestParams');
        return $type;
    }


    /** @return Type<CreateMessageResultWire, CreateMessageResultFields> */
    public function createMessageResult(): Type
    {
        /** @var Type<CreateMessageResultWire, CreateMessageResultFields> $type */
        $type = $this->type('CreateMessageResult');
        return $type;
    }


    /** @return Type<CreateTaskResultWire, CreateTaskResultFields> */
    public function createTaskResult(): Type
    {
        /** @var Type<CreateTaskResultWire, CreateTaskResultFields> $type */
        $type = $this->type('CreateTaskResult');
        return $type;
    }


    /** @return Type<ElicitRequestWire, ElicitRequestFields> */
    public function elicitRequest(): Type
    {
        /** @var Type<ElicitRequestWire, ElicitRequestFields> $type */
        $type = $this->type('ElicitRequest');
        return $type;
    }


    /** @return Type<ElicitRequestFormParamsWire, ElicitRequestFormParamsFields> */
    public function elicitRequestFormParams(): Type
    {
        /** @var Type<ElicitRequestFormParamsWire, ElicitRequestFormParamsFields> $type */
        $type = $this->type('ElicitRequestFormParams');
        return $type;
    }


    /** @return Type<ElicitRequestParamsWire, ElicitRequestParamsFields> */
    public function elicitRequestParams(): Type
    {
        /** @var Type<ElicitRequestParamsWire, ElicitRequestParamsFields> $type */
        $type = $this->type('ElicitRequestParams');
        return $type;
    }


    /** @return Type<ElicitRequestURLParamsWire, ElicitRequestURLParamsFields> */
    public function elicitRequestURLParams(): Type
    {
        /** @var Type<ElicitRequestURLParamsWire, ElicitRequestURLParamsFields> $type */
        $type = $this->type('ElicitRequestURLParams');
        return $type;
    }


    /** @return Type<ElicitResultWire, ElicitResultFields> */
    public function elicitResult(): Type
    {
        /** @var Type<ElicitResultWire, ElicitResultFields> $type */
        $type = $this->type('ElicitResult');
        return $type;
    }


    /** @return Type<ElicitationCompleteNotificationWire, ElicitationCompleteNotificationFields> */
    public function elicitationCompleteNotification(): Type
    {
        /** @var Type<ElicitationCompleteNotificationWire, ElicitationCompleteNotificationFields> $type */
        $type = $this->type('ElicitationCompleteNotification');
        return $type;
    }


    /** @return Type<EmbeddedResourceWire, EmbeddedResourceFields> */
    public function embeddedResource(): Type
    {
        /** @var Type<EmbeddedResourceWire, EmbeddedResourceFields> $type */
        $type = $this->type('EmbeddedResource');
        return $type;
    }


    /** @return Type<EmptyResultWire, EmptyResultFields> */
    public function emptyResult(): Type
    {
        /** @var Type<EmptyResultWire, EmptyResultFields> $type */
        $type = $this->type('EmptyResult');
        return $type;
    }


    /** @return Type<EnumSchemaWire, EnumSchemaFields> */
    public function enumSchema(): Type
    {
        /** @var Type<EnumSchemaWire, EnumSchemaFields> $type */
        $type = $this->type('EnumSchema');
        return $type;
    }


    /** @return Type<ErrorWire, ErrorFields> */
    public function error(): Type
    {
        /** @var Type<ErrorWire, ErrorFields> $type */
        $type = $this->type('Error');
        return $type;
    }


    /** @return Type<GetPromptRequestWire, GetPromptRequestFields> */
    public function getPromptRequest(): Type
    {
        /** @var Type<GetPromptRequestWire, GetPromptRequestFields> $type */
        $type = $this->type('GetPromptRequest');
        return $type;
    }


    /** @return Type<GetPromptRequestParamsWire, GetPromptRequestParamsFields> */
    public function getPromptRequestParams(): Type
    {
        /** @var Type<GetPromptRequestParamsWire, GetPromptRequestParamsFields> $type */
        $type = $this->type('GetPromptRequestParams');
        return $type;
    }


    /** @return Type<GetPromptResultWire, GetPromptResultFields> */
    public function getPromptResult(): Type
    {
        /** @var Type<GetPromptResultWire, GetPromptResultFields> $type */
        $type = $this->type('GetPromptResult');
        return $type;
    }


    /** @return Type<GetTaskPayloadRequestWire, GetTaskPayloadRequestFields> */
    public function getTaskPayloadRequest(): Type
    {
        /** @var Type<GetTaskPayloadRequestWire, GetTaskPayloadRequestFields> $type */
        $type = $this->type('GetTaskPayloadRequest');
        return $type;
    }


    /** @return Type<GetTaskPayloadResultWire, GetTaskPayloadResultFields> */
    public function getTaskPayloadResult(): Type
    {
        /** @var Type<GetTaskPayloadResultWire, GetTaskPayloadResultFields> $type */
        $type = $this->type('GetTaskPayloadResult');
        return $type;
    }


    /** @return Type<GetTaskRequestWire, GetTaskRequestFields> */
    public function getTaskRequest(): Type
    {
        /** @var Type<GetTaskRequestWire, GetTaskRequestFields> $type */
        $type = $this->type('GetTaskRequest');
        return $type;
    }


    /** @return Type<GetTaskResultWire, GetTaskResultFields> */
    public function getTaskResult(): Type
    {
        /** @var Type<GetTaskResultWire, GetTaskResultFields> $type */
        $type = $this->type('GetTaskResult');
        return $type;
    }


    /** @return Type<IconWire, IconFields> */
    public function icon(): Type
    {
        /** @var Type<IconWire, IconFields> $type */
        $type = $this->type('Icon');
        return $type;
    }


    /** @return Type<IconsWire, IconsFields> */
    public function icons(): Type
    {
        /** @var Type<IconsWire, IconsFields> $type */
        $type = $this->type('Icons');
        return $type;
    }


    /** @return Type<ImageContentWire, ImageContentFields> */
    public function imageContent(): Type
    {
        /** @var Type<ImageContentWire, ImageContentFields> $type */
        $type = $this->type('ImageContent');
        return $type;
    }


    /** @return Type<ImplementationWire, ImplementationFields> */
    public function implementation(): Type
    {
        /** @var Type<ImplementationWire, ImplementationFields> $type */
        $type = $this->type('Implementation');
        return $type;
    }


    /** @return Type<InitializeRequestWire, InitializeRequestFields> */
    public function initializeRequest(): Type
    {
        /** @var Type<InitializeRequestWire, InitializeRequestFields> $type */
        $type = $this->type('InitializeRequest');
        return $type;
    }


    /** @return Type<InitializeRequestParamsWire, InitializeRequestParamsFields> */
    public function initializeRequestParams(): Type
    {
        /** @var Type<InitializeRequestParamsWire, InitializeRequestParamsFields> $type */
        $type = $this->type('InitializeRequestParams');
        return $type;
    }


    /** @return Type<InitializeResultWire, InitializeResultFields> */
    public function initializeResult(): Type
    {
        /** @var Type<InitializeResultWire, InitializeResultFields> $type */
        $type = $this->type('InitializeResult');
        return $type;
    }


    /** @return Type<InitializedNotificationWire, InitializedNotificationFields> */
    public function initializedNotification(): Type
    {
        /** @var Type<InitializedNotificationWire, InitializedNotificationFields> $type */
        $type = $this->type('InitializedNotification');
        return $type;
    }


    /** @return Type<JSONRPCErrorResponseWire, JSONRPCErrorResponseFields> */
    public function jsonrpcErrorResponse(): Type
    {
        /** @var Type<JSONRPCErrorResponseWire, JSONRPCErrorResponseFields> $type */
        $type = $this->type('JSONRPCErrorResponse');
        return $type;
    }


    /** @return Type<JSONRPCMessageWire, JSONRPCMessageFields> */
    public function jsonrpcMessage(): Type
    {
        /** @var Type<JSONRPCMessageWire, JSONRPCMessageFields> $type */
        $type = $this->type('JSONRPCMessage');
        return $type;
    }


    /** @return Type<JSONRPCNotificationWire, JSONRPCNotificationFields> */
    public function jsonrpcNotification(): Type
    {
        /** @var Type<JSONRPCNotificationWire, JSONRPCNotificationFields> $type */
        $type = $this->type('JSONRPCNotification');
        return $type;
    }


    /** @return Type<JSONRPCRequestWire, JSONRPCRequestFields> */
    public function jsonrpcRequest(): Type
    {
        /** @var Type<JSONRPCRequestWire, JSONRPCRequestFields> $type */
        $type = $this->type('JSONRPCRequest');
        return $type;
    }


    /** @return Type<JSONRPCResponseWire, JSONRPCResponseFields> */
    public function jsonrpcResponse(): Type
    {
        /** @var Type<JSONRPCResponseWire, JSONRPCResponseFields> $type */
        $type = $this->type('JSONRPCResponse');
        return $type;
    }


    /** @return Type<JSONRPCResultResponseWire, JSONRPCResultResponseFields> */
    public function jsonrpcResultResponse(): Type
    {
        /** @var Type<JSONRPCResultResponseWire, JSONRPCResultResponseFields> $type */
        $type = $this->type('JSONRPCResultResponse');
        return $type;
    }


    /** @return Type<LegacyTitledEnumSchemaWire, LegacyTitledEnumSchemaFields> */
    public function legacyTitledEnumSchema(): Type
    {
        /** @var Type<LegacyTitledEnumSchemaWire, LegacyTitledEnumSchemaFields> $type */
        $type = $this->type('LegacyTitledEnumSchema');
        return $type;
    }


    /** @return Type<ListPromptsRequestWire, ListPromptsRequestFields> */
    public function listPromptsRequest(): Type
    {
        /** @var Type<ListPromptsRequestWire, ListPromptsRequestFields> $type */
        $type = $this->type('ListPromptsRequest');
        return $type;
    }


    /** @return Type<ListPromptsResultWire, ListPromptsResultFields> */
    public function listPromptsResult(): Type
    {
        /** @var Type<ListPromptsResultWire, ListPromptsResultFields> $type */
        $type = $this->type('ListPromptsResult');
        return $type;
    }


    /** @return Type<ListResourceTemplatesRequestWire, ListResourceTemplatesRequestFields> */
    public function listResourceTemplatesRequest(): Type
    {
        /** @var Type<ListResourceTemplatesRequestWire, ListResourceTemplatesRequestFields> $type */
        $type = $this->type('ListResourceTemplatesRequest');
        return $type;
    }


    /** @return Type<ListResourceTemplatesResultWire, ListResourceTemplatesResultFields> */
    public function listResourceTemplatesResult(): Type
    {
        /** @var Type<ListResourceTemplatesResultWire, ListResourceTemplatesResultFields> $type */
        $type = $this->type('ListResourceTemplatesResult');
        return $type;
    }


    /** @return Type<ListResourcesRequestWire, ListResourcesRequestFields> */
    public function listResourcesRequest(): Type
    {
        /** @var Type<ListResourcesRequestWire, ListResourcesRequestFields> $type */
        $type = $this->type('ListResourcesRequest');
        return $type;
    }


    /** @return Type<ListResourcesResultWire, ListResourcesResultFields> */
    public function listResourcesResult(): Type
    {
        /** @var Type<ListResourcesResultWire, ListResourcesResultFields> $type */
        $type = $this->type('ListResourcesResult');
        return $type;
    }


    /** @return Type<ListRootsRequestWire, ListRootsRequestFields> */
    public function listRootsRequest(): Type
    {
        /** @var Type<ListRootsRequestWire, ListRootsRequestFields> $type */
        $type = $this->type('ListRootsRequest');
        return $type;
    }


    /** @return Type<ListRootsResultWire, ListRootsResultFields> */
    public function listRootsResult(): Type
    {
        /** @var Type<ListRootsResultWire, ListRootsResultFields> $type */
        $type = $this->type('ListRootsResult');
        return $type;
    }


    /** @return Type<ListTasksRequestWire, ListTasksRequestFields> */
    public function listTasksRequest(): Type
    {
        /** @var Type<ListTasksRequestWire, ListTasksRequestFields> $type */
        $type = $this->type('ListTasksRequest');
        return $type;
    }


    /** @return Type<ListTasksResultWire, ListTasksResultFields> */
    public function listTasksResult(): Type
    {
        /** @var Type<ListTasksResultWire, ListTasksResultFields> $type */
        $type = $this->type('ListTasksResult');
        return $type;
    }


    /** @return Type<ListToolsRequestWire, ListToolsRequestFields> */
    public function listToolsRequest(): Type
    {
        /** @var Type<ListToolsRequestWire, ListToolsRequestFields> $type */
        $type = $this->type('ListToolsRequest');
        return $type;
    }


    /** @return Type<ListToolsResultWire, ListToolsResultFields> */
    public function listToolsResult(): Type
    {
        /** @var Type<ListToolsResultWire, ListToolsResultFields> $type */
        $type = $this->type('ListToolsResult');
        return $type;
    }


    /** @return Type<LoggingMessageNotificationWire, LoggingMessageNotificationFields> */
    public function loggingMessageNotification(): Type
    {
        /** @var Type<LoggingMessageNotificationWire, LoggingMessageNotificationFields> $type */
        $type = $this->type('LoggingMessageNotification');
        return $type;
    }


    /** @return Type<LoggingMessageNotificationParamsWire, LoggingMessageNotificationParamsFields> */
    public function loggingMessageNotificationParams(): Type
    {
        /** @var Type<LoggingMessageNotificationParamsWire, LoggingMessageNotificationParamsFields> $type */
        $type = $this->type('LoggingMessageNotificationParams');
        return $type;
    }


    /** @return Type<ModelHintWire, ModelHintFields> */
    public function modelHint(): Type
    {
        /** @var Type<ModelHintWire, ModelHintFields> $type */
        $type = $this->type('ModelHint');
        return $type;
    }


    /** @return Type<ModelPreferencesWire, ModelPreferencesFields> */
    public function modelPreferences(): Type
    {
        /** @var Type<ModelPreferencesWire, ModelPreferencesFields> $type */
        $type = $this->type('ModelPreferences');
        return $type;
    }


    /** @return Type<MultiSelectEnumSchemaWire, MultiSelectEnumSchemaFields> */
    public function multiSelectEnumSchema(): Type
    {
        /** @var Type<MultiSelectEnumSchemaWire, MultiSelectEnumSchemaFields> $type */
        $type = $this->type('MultiSelectEnumSchema');
        return $type;
    }


    /** @return Type<NotificationWire, NotificationFields> */
    public function notification(): Type
    {
        /** @var Type<NotificationWire, NotificationFields> $type */
        $type = $this->type('Notification');
        return $type;
    }


    /** @return Type<NotificationParamsWire, NotificationParamsFields> */
    public function notificationParams(): Type
    {
        /** @var Type<NotificationParamsWire, NotificationParamsFields> $type */
        $type = $this->type('NotificationParams');
        return $type;
    }


    /** @return Type<NumberSchemaWire, NumberSchemaFields> */
    public function numberSchema(): Type
    {
        /** @var Type<NumberSchemaWire, NumberSchemaFields> $type */
        $type = $this->type('NumberSchema');
        return $type;
    }


    /** @return Type<PaginatedRequestWire, PaginatedRequestFields> */
    public function paginatedRequest(): Type
    {
        /** @var Type<PaginatedRequestWire, PaginatedRequestFields> $type */
        $type = $this->type('PaginatedRequest');
        return $type;
    }


    /** @return Type<PaginatedRequestParamsWire, PaginatedRequestParamsFields> */
    public function paginatedRequestParams(): Type
    {
        /** @var Type<PaginatedRequestParamsWire, PaginatedRequestParamsFields> $type */
        $type = $this->type('PaginatedRequestParams');
        return $type;
    }


    /** @return Type<PaginatedResultWire, PaginatedResultFields> */
    public function paginatedResult(): Type
    {
        /** @var Type<PaginatedResultWire, PaginatedResultFields> $type */
        $type = $this->type('PaginatedResult');
        return $type;
    }


    /** @return Type<PingRequestWire, PingRequestFields> */
    public function pingRequest(): Type
    {
        /** @var Type<PingRequestWire, PingRequestFields> $type */
        $type = $this->type('PingRequest');
        return $type;
    }


    /** @return Type<PrimitiveSchemaDefinitionWire, PrimitiveSchemaDefinitionFields> */
    public function primitiveSchemaDefinition(): Type
    {
        /** @var Type<PrimitiveSchemaDefinitionWire, PrimitiveSchemaDefinitionFields> $type */
        $type = $this->type('PrimitiveSchemaDefinition');
        return $type;
    }


    /** @return Type<ProgressNotificationWire, ProgressNotificationFields> */
    public function progressNotification(): Type
    {
        /** @var Type<ProgressNotificationWire, ProgressNotificationFields> $type */
        $type = $this->type('ProgressNotification');
        return $type;
    }


    /** @return Type<ProgressNotificationParamsWire, ProgressNotificationParamsFields> */
    public function progressNotificationParams(): Type
    {
        /** @var Type<ProgressNotificationParamsWire, ProgressNotificationParamsFields> $type */
        $type = $this->type('ProgressNotificationParams');
        return $type;
    }


    /** @return Type<PromptWire, PromptFields> */
    public function prompt(): Type
    {
        /** @var Type<PromptWire, PromptFields> $type */
        $type = $this->type('Prompt');
        return $type;
    }


    /** @return Type<PromptArgumentWire, PromptArgumentFields> */
    public function promptArgument(): Type
    {
        /** @var Type<PromptArgumentWire, PromptArgumentFields> $type */
        $type = $this->type('PromptArgument');
        return $type;
    }


    /** @return Type<PromptListChangedNotificationWire, PromptListChangedNotificationFields> */
    public function promptListChangedNotification(): Type
    {
        /** @var Type<PromptListChangedNotificationWire, PromptListChangedNotificationFields> $type */
        $type = $this->type('PromptListChangedNotification');
        return $type;
    }


    /** @return Type<PromptMessageWire, PromptMessageFields> */
    public function promptMessage(): Type
    {
        /** @var Type<PromptMessageWire, PromptMessageFields> $type */
        $type = $this->type('PromptMessage');
        return $type;
    }


    /** @return Type<PromptReferenceWire, PromptReferenceFields> */
    public function promptReference(): Type
    {
        /** @var Type<PromptReferenceWire, PromptReferenceFields> $type */
        $type = $this->type('PromptReference');
        return $type;
    }


    /** @return Type<ReadResourceRequestWire, ReadResourceRequestFields> */
    public function readResourceRequest(): Type
    {
        /** @var Type<ReadResourceRequestWire, ReadResourceRequestFields> $type */
        $type = $this->type('ReadResourceRequest');
        return $type;
    }


    /** @return Type<ReadResourceRequestParamsWire, ReadResourceRequestParamsFields> */
    public function readResourceRequestParams(): Type
    {
        /** @var Type<ReadResourceRequestParamsWire, ReadResourceRequestParamsFields> $type */
        $type = $this->type('ReadResourceRequestParams');
        return $type;
    }


    /** @return Type<ReadResourceResultWire, ReadResourceResultFields> */
    public function readResourceResult(): Type
    {
        /** @var Type<ReadResourceResultWire, ReadResourceResultFields> $type */
        $type = $this->type('ReadResourceResult');
        return $type;
    }


    /** @return Type<RelatedTaskMetadataWire, RelatedTaskMetadataFields> */
    public function relatedTaskMetadata(): Type
    {
        /** @var Type<RelatedTaskMetadataWire, RelatedTaskMetadataFields> $type */
        $type = $this->type('RelatedTaskMetadata');
        return $type;
    }


    /** @return Type<RequestWire, RequestFields> */
    public function request(): Type
    {
        /** @var Type<RequestWire, RequestFields> $type */
        $type = $this->type('Request');
        return $type;
    }


    /** @return Type<RequestParamsWire, RequestParamsFields> */
    public function requestParams(): Type
    {
        /** @var Type<RequestParamsWire, RequestParamsFields> $type */
        $type = $this->type('RequestParams');
        return $type;
    }


    /** @return Type<ResourceWire, ResourceFields> */
    public function resource(): Type
    {
        /** @var Type<ResourceWire, ResourceFields> $type */
        $type = $this->type('Resource');
        return $type;
    }


    /** @return Type<ResourceContentsWire, ResourceContentsFields> */
    public function resourceContents(): Type
    {
        /** @var Type<ResourceContentsWire, ResourceContentsFields> $type */
        $type = $this->type('ResourceContents');
        return $type;
    }


    /** @return Type<ResourceLinkWire, ResourceLinkFields> */
    public function resourceLink(): Type
    {
        /** @var Type<ResourceLinkWire, ResourceLinkFields> $type */
        $type = $this->type('ResourceLink');
        return $type;
    }


    /** @return Type<ResourceListChangedNotificationWire, ResourceListChangedNotificationFields> */
    public function resourceListChangedNotification(): Type
    {
        /** @var Type<ResourceListChangedNotificationWire, ResourceListChangedNotificationFields> $type */
        $type = $this->type('ResourceListChangedNotification');
        return $type;
    }


    /** @return Type<ResourceRequestParamsWire, ResourceRequestParamsFields> */
    public function resourceRequestParams(): Type
    {
        /** @var Type<ResourceRequestParamsWire, ResourceRequestParamsFields> $type */
        $type = $this->type('ResourceRequestParams');
        return $type;
    }


    /** @return Type<ResourceTemplateWire, ResourceTemplateFields> */
    public function resourceTemplate(): Type
    {
        /** @var Type<ResourceTemplateWire, ResourceTemplateFields> $type */
        $type = $this->type('ResourceTemplate');
        return $type;
    }


    /** @return Type<ResourceTemplateReferenceWire, ResourceTemplateReferenceFields> */
    public function resourceTemplateReference(): Type
    {
        /** @var Type<ResourceTemplateReferenceWire, ResourceTemplateReferenceFields> $type */
        $type = $this->type('ResourceTemplateReference');
        return $type;
    }


    /** @return Type<ResourceUpdatedNotificationWire, ResourceUpdatedNotificationFields> */
    public function resourceUpdatedNotification(): Type
    {
        /** @var Type<ResourceUpdatedNotificationWire, ResourceUpdatedNotificationFields> $type */
        $type = $this->type('ResourceUpdatedNotification');
        return $type;
    }


    /** @return Type<ResourceUpdatedNotificationParamsWire, ResourceUpdatedNotificationParamsFields> */
    public function resourceUpdatedNotificationParams(): Type
    {
        /** @var Type<ResourceUpdatedNotificationParamsWire, ResourceUpdatedNotificationParamsFields> $type */
        $type = $this->type('ResourceUpdatedNotificationParams');
        return $type;
    }


    /** @return Type<ResultWire, ResultFields> */
    public function result(): Type
    {
        /** @var Type<ResultWire, ResultFields> $type */
        $type = $this->type('Result');
        return $type;
    }


    /** @return Type<RootWire, RootFields> */
    public function root(): Type
    {
        /** @var Type<RootWire, RootFields> $type */
        $type = $this->type('Root');
        return $type;
    }


    /** @return Type<RootsListChangedNotificationWire, RootsListChangedNotificationFields> */
    public function rootsListChangedNotification(): Type
    {
        /** @var Type<RootsListChangedNotificationWire, RootsListChangedNotificationFields> $type */
        $type = $this->type('RootsListChangedNotification');
        return $type;
    }


    /** @return Type<SamplingMessageWire, SamplingMessageFields> */
    public function samplingMessage(): Type
    {
        /** @var Type<SamplingMessageWire, SamplingMessageFields> $type */
        $type = $this->type('SamplingMessage');
        return $type;
    }


    /** @return Type<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields> */
    public function samplingMessageContentBlock(): Type
    {
        /** @var Type<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields> $type */
        $type = $this->type('SamplingMessageContentBlock');
        return $type;
    }


    /** @return Type<ServerCapabilitiesWire, ServerCapabilitiesFields> */
    public function serverCapabilities(): Type
    {
        /** @var Type<ServerCapabilitiesWire, ServerCapabilitiesFields> $type */
        $type = $this->type('ServerCapabilities');
        return $type;
    }


    /** @return Type<ServerNotificationWire, ServerNotificationFields> */
    public function serverNotification(): Type
    {
        /** @var Type<ServerNotificationWire, ServerNotificationFields> $type */
        $type = $this->type('ServerNotification');
        return $type;
    }


    /** @return Type<ServerRequestWire, ServerRequestFields> */
    public function serverRequest(): Type
    {
        /** @var Type<ServerRequestWire, ServerRequestFields> $type */
        $type = $this->type('ServerRequest');
        return $type;
    }


    /** @return Type<ServerResultWire, ServerResultFields> */
    public function serverResult(): Type
    {
        /** @var Type<ServerResultWire, ServerResultFields> $type */
        $type = $this->type('ServerResult');
        return $type;
    }


    /** @return Type<SetLevelRequestWire, SetLevelRequestFields> */
    public function setLevelRequest(): Type
    {
        /** @var Type<SetLevelRequestWire, SetLevelRequestFields> $type */
        $type = $this->type('SetLevelRequest');
        return $type;
    }


    /** @return Type<SetLevelRequestParamsWire, SetLevelRequestParamsFields> */
    public function setLevelRequestParams(): Type
    {
        /** @var Type<SetLevelRequestParamsWire, SetLevelRequestParamsFields> $type */
        $type = $this->type('SetLevelRequestParams');
        return $type;
    }


    /** @return Type<SingleSelectEnumSchemaWire, SingleSelectEnumSchemaFields> */
    public function singleSelectEnumSchema(): Type
    {
        /** @var Type<SingleSelectEnumSchemaWire, SingleSelectEnumSchemaFields> $type */
        $type = $this->type('SingleSelectEnumSchema');
        return $type;
    }


    /** @return Type<StringSchemaWire, StringSchemaFields> */
    public function stringSchema(): Type
    {
        /** @var Type<StringSchemaWire, StringSchemaFields> $type */
        $type = $this->type('StringSchema');
        return $type;
    }


    /** @return Type<SubscribeRequestWire, SubscribeRequestFields> */
    public function subscribeRequest(): Type
    {
        /** @var Type<SubscribeRequestWire, SubscribeRequestFields> $type */
        $type = $this->type('SubscribeRequest');
        return $type;
    }


    /** @return Type<SubscribeRequestParamsWire, SubscribeRequestParamsFields> */
    public function subscribeRequestParams(): Type
    {
        /** @var Type<SubscribeRequestParamsWire, SubscribeRequestParamsFields> $type */
        $type = $this->type('SubscribeRequestParams');
        return $type;
    }


    /** @return Type<TaskWire, TaskFields> */
    public function task(): Type
    {
        /** @var Type<TaskWire, TaskFields> $type */
        $type = $this->type('Task');
        return $type;
    }


    /** @return Type<TaskAugmentedRequestParamsWire, TaskAugmentedRequestParamsFields> */
    public function taskAugmentedRequestParams(): Type
    {
        /** @var Type<TaskAugmentedRequestParamsWire, TaskAugmentedRequestParamsFields> $type */
        $type = $this->type('TaskAugmentedRequestParams');
        return $type;
    }


    /** @return Type<TaskMetadataWire, TaskMetadataFields> */
    public function taskMetadata(): Type
    {
        /** @var Type<TaskMetadataWire, TaskMetadataFields> $type */
        $type = $this->type('TaskMetadata');
        return $type;
    }


    /** @return Type<TaskStatusNotificationWire, TaskStatusNotificationFields> */
    public function taskStatusNotification(): Type
    {
        /** @var Type<TaskStatusNotificationWire, TaskStatusNotificationFields> $type */
        $type = $this->type('TaskStatusNotification');
        return $type;
    }


    /** @return Type<TaskStatusNotificationParamsWire, TaskStatusNotificationParamsFields> */
    public function taskStatusNotificationParams(): Type
    {
        /** @var Type<TaskStatusNotificationParamsWire, TaskStatusNotificationParamsFields> $type */
        $type = $this->type('TaskStatusNotificationParams');
        return $type;
    }


    /** @return Type<TextContentWire, TextContentFields> */
    public function textContent(): Type
    {
        /** @var Type<TextContentWire, TextContentFields> $type */
        $type = $this->type('TextContent');
        return $type;
    }


    /** @return Type<TextResourceContentsWire, TextResourceContentsFields> */
    public function textResourceContents(): Type
    {
        /** @var Type<TextResourceContentsWire, TextResourceContentsFields> $type */
        $type = $this->type('TextResourceContents');
        return $type;
    }


    /** @return Type<TitledMultiSelectEnumSchemaWire, TitledMultiSelectEnumSchemaFields> */
    public function titledMultiSelectEnumSchema(): Type
    {
        /** @var Type<TitledMultiSelectEnumSchemaWire, TitledMultiSelectEnumSchemaFields> $type */
        $type = $this->type('TitledMultiSelectEnumSchema');
        return $type;
    }


    /** @return Type<TitledSingleSelectEnumSchemaWire, TitledSingleSelectEnumSchemaFields> */
    public function titledSingleSelectEnumSchema(): Type
    {
        /** @var Type<TitledSingleSelectEnumSchemaWire, TitledSingleSelectEnumSchemaFields> $type */
        $type = $this->type('TitledSingleSelectEnumSchema');
        return $type;
    }


    /** @return Type<ToolWire, ToolFields> */
    public function tool(): Type
    {
        /** @var Type<ToolWire, ToolFields> $type */
        $type = $this->type('Tool');
        return $type;
    }


    /** @return Type<ToolAnnotationsWire, ToolAnnotationsFields> */
    public function toolAnnotations(): Type
    {
        /** @var Type<ToolAnnotationsWire, ToolAnnotationsFields> $type */
        $type = $this->type('ToolAnnotations');
        return $type;
    }


    /** @return Type<ToolChoiceWire, ToolChoiceFields> */
    public function toolChoice(): Type
    {
        /** @var Type<ToolChoiceWire, ToolChoiceFields> $type */
        $type = $this->type('ToolChoice');
        return $type;
    }


    /** @return Type<ToolExecutionWire, ToolExecutionFields> */
    public function toolExecution(): Type
    {
        /** @var Type<ToolExecutionWire, ToolExecutionFields> $type */
        $type = $this->type('ToolExecution');
        return $type;
    }


    /** @return Type<ToolListChangedNotificationWire, ToolListChangedNotificationFields> */
    public function toolListChangedNotification(): Type
    {
        /** @var Type<ToolListChangedNotificationWire, ToolListChangedNotificationFields> $type */
        $type = $this->type('ToolListChangedNotification');
        return $type;
    }


    /** @return Type<ToolResultContentWire, ToolResultContentFields> */
    public function toolResultContent(): Type
    {
        /** @var Type<ToolResultContentWire, ToolResultContentFields> $type */
        $type = $this->type('ToolResultContent');
        return $type;
    }


    /** @return Type<ToolUseContentWire, ToolUseContentFields> */
    public function toolUseContent(): Type
    {
        /** @var Type<ToolUseContentWire, ToolUseContentFields> $type */
        $type = $this->type('ToolUseContent');
        return $type;
    }


    /** @return Type<URLElicitationRequiredErrorWire, URLElicitationRequiredErrorFields> */
    public function urlElicitationRequiredError(): Type
    {
        /** @var Type<URLElicitationRequiredErrorWire, URLElicitationRequiredErrorFields> $type */
        $type = $this->type('URLElicitationRequiredError');
        return $type;
    }


    /** @return Type<UnsubscribeRequestWire, UnsubscribeRequestFields> */
    public function unsubscribeRequest(): Type
    {
        /** @var Type<UnsubscribeRequestWire, UnsubscribeRequestFields> $type */
        $type = $this->type('UnsubscribeRequest');
        return $type;
    }


    /** @return Type<UnsubscribeRequestParamsWire, UnsubscribeRequestParamsFields> */
    public function unsubscribeRequestParams(): Type
    {
        /** @var Type<UnsubscribeRequestParamsWire, UnsubscribeRequestParamsFields> $type */
        $type = $this->type('UnsubscribeRequestParams');
        return $type;
    }


    /** @return Type<UntitledMultiSelectEnumSchemaWire, UntitledMultiSelectEnumSchemaFields> */
    public function untitledMultiSelectEnumSchema(): Type
    {
        /** @var Type<UntitledMultiSelectEnumSchemaWire, UntitledMultiSelectEnumSchemaFields> $type */
        $type = $this->type('UntitledMultiSelectEnumSchema');
        return $type;
    }


    /** @return Type<UntitledSingleSelectEnumSchemaWire, UntitledSingleSelectEnumSchemaFields> */
    public function untitledSingleSelectEnumSchema(): Type
    {
        /** @var Type<UntitledSingleSelectEnumSchemaWire, UntitledSingleSelectEnumSchemaFields> $type */
        $type = $this->type('UntitledSingleSelectEnumSchema');
        return $type;
    }

}

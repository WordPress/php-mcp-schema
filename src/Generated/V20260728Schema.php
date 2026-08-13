<?php

declare(strict_types=1);

namespace WP\McpSchema\Generated;

use WP\McpSchema\Contract\Type;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Runtime\GenericRevisionSchema;

/**
 * Generated discoverable catalog for MCP 2026-07-28.
 *
 * @phpstan-type AnnotationsWire array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}
 * @phpstan-type AnnotationsFields array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}
 * @phpstan-type AudioContentWire array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}
 * @phpstan-type AudioContentFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'audio'}
 * @phpstan-type BaseMetadataWire array{name: string, title?: string}
 * @phpstan-type BaseMetadataFields array{name: string, title?: string}
 * @phpstan-type BlobResourceContentsWire array{_meta?: array<string, mixed>, mimeType?: string, uri: string, blob: string}
 * @phpstan-type BlobResourceContentsFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, mimeType?: string, uri: string, blob: string}
 * @phpstan-type BooleanSchemaWire array{default?: bool, description?: string, title?: string, type: 'boolean'}
 * @phpstan-type BooleanSchemaFields array{default?: bool, description?: string, title?: string, type: 'boolean'}
 * @phpstan-type CacheableResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, ...<string, mixed>}
 * @phpstan-type CacheableResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, ...<string, mixed>}
 * @phpstan-type CallToolRequestWire array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CallToolRequestFields array{method: 'tools/call', params: Record<CallToolRequestParamsWire, CallToolRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CallToolRequestParamsWire array{_meta: array<string, mixed>, inputResponses?: array<string, mixed>, requestState?: string, arguments?: array{...<string, mixed>}, name: string}
 * @phpstan-type CallToolRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, inputResponses?: Record<InputResponsesWire, InputResponsesFields>, requestState?: string, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}
 * @phpstan-type CallToolResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, content: list<array<string, mixed>>, isError?: bool, structuredContent?: mixed, ...<string, mixed>}
 * @phpstan-type CallToolResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, content: list<Record<ContentBlockWire, ContentBlockFields>>, isError?: bool, structuredContent?: mixed, ...<string, mixed>}
 * @phpstan-type CallToolResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type CallToolResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<CallToolResultWire, CallToolResultFields>|Record<InputRequiredResultWire, InputRequiredResultFields>}
 * @phpstan-type CancelledNotificationWire array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type CancelledNotificationFields array{method: 'notifications/cancelled', params: Record<CancelledNotificationParamsWire, CancelledNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type CancelledNotificationParamsWire array{_meta?: array<string, mixed>, reason?: string, requestId: string|int|float}
 * @phpstan-type CancelledNotificationParamsFields array{_meta?: Record<NotificationMetaObjectWire, NotificationMetaObjectFields>, reason?: string, requestId: string|int|float}
 * @phpstan-type ClientCapabilitiesWire array{elicitation?: array{form?: array<string, mixed>, url?: array<string, mixed>}, experimental?: array{...<string, array<string, mixed>>}, extensions?: array{...<string, array<string, mixed>>}, roots?: array{}, sampling?: array{context?: array<string, mixed>, tools?: array<string, mixed>}}
 * @phpstan-type ClientCapabilitiesFields array{elicitation?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, extensions?: Record<array<string, mixed>, array<string, mixed>>, roots?: Record<array<string, mixed>, array<string, mixed>>, sampling?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ClientNotificationWire array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ClientNotificationFields array{method: 'notifications/cancelled', params: Record<CancelledNotificationParamsWire, CancelledNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ClientRequestWire array{method: 'server/discover', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'subscriptions/listen', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ClientRequestFields array{method: 'server/discover', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'subscriptions/listen', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ClientResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type ClientResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type CompleteRequestWire array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CompleteRequestFields array{method: 'completion/complete', params: Record<CompleteRequestParamsWire, CompleteRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type CompleteRequestParamsWire array{_meta: array<string, mixed>, argument: array{name: string, value: string}, context?: array{arguments?: array{...<string, string>}}, ref: array<string, mixed>}
 * @phpstan-type CompleteRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, argument: Record<array<string, mixed>, array<string, mixed>>, context?: Record<array<string, mixed>, array<string, mixed>>, ref: Record<PromptReferenceWire, PromptReferenceFields>|Record<ResourceTemplateReferenceWire, ResourceTemplateReferenceFields>}
 * @phpstan-type CompleteResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}
 * @phpstan-type CompleteResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type CompleteResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type CompleteResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<CompleteResultWire, CompleteResultFields>}
 * @phpstan-type ContentBlockWire array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array<string, mixed>, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}
 * @phpstan-type ContentBlockFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, text: string, type: 'text'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, resource: Record<TextResourceContentsWire, TextResourceContentsFields>|Record<BlobResourceContentsWire, BlobResourceContentsFields>, type: 'resource'}
 * @phpstan-type CreateMessageRequestWire array{method: 'sampling/createMessage', params: array<string, mixed>}
 * @phpstan-type CreateMessageRequestFields array{method: 'sampling/createMessage', params: Record<CreateMessageRequestParamsWire, CreateMessageRequestParamsFields>}
 * @phpstan-type CreateMessageRequestParamsWire array{includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<array<string, mixed>>, metadata?: array<string, mixed>, modelPreferences?: array<string, mixed>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: array<string, mixed>, tools?: list<array<string, mixed>>}
 * @phpstan-type CreateMessageRequestParamsFields array{includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<Record<SamplingMessageWire, SamplingMessageFields>>, metadata?: Record<JSONObjectWire, JSONObjectFields>, modelPreferences?: Record<ModelPreferencesWire, ModelPreferencesFields>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: Record<ToolChoiceWire, ToolChoiceFields>, tools?: list<Record<ToolWire, ToolFields>>}
 * @phpstan-type CreateMessageResultWire array{_meta?: array<string, mixed>, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string}
 * @phpstan-type CreateMessageResultFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, content: Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>|list<Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string}
 * @phpstan-type DiscoverRequestWire array{method: 'server/discover', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type DiscoverRequestFields array{method: 'server/discover', params: Record<RequestParamsWire, RequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type DiscoverResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, capabilities: array<string, mixed>, instructions?: string, supportedVersions: list<string>, ...<string, mixed>}
 * @phpstan-type DiscoverResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, capabilities: Record<ServerCapabilitiesWire, ServerCapabilitiesFields>, instructions?: string, supportedVersions: list<string>, ...<string, mixed>}
 * @phpstan-type DiscoverResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type DiscoverResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<DiscoverResultWire, DiscoverResultFields>}
 * @phpstan-type ElicitRequestWire array{method: 'elicitation/create', params: array<string, mixed>}
 * @phpstan-type ElicitRequestFields array{method: 'elicitation/create', params: Record<ElicitRequestParamsWire, ElicitRequestParamsFields>}
 * @phpstan-type ElicitRequestFormParamsWire array{message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}
 * @phpstan-type ElicitRequestFormParamsFields array{message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ElicitRequestParamsWire array{message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}|array{message: string, mode: 'url', url: string}
 * @phpstan-type ElicitRequestParamsFields array{message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}|array{message: string, mode: 'url', url: string}
 * @phpstan-type ElicitRequestURLParamsWire array{message: string, mode: 'url', url: string}
 * @phpstan-type ElicitRequestURLParamsFields array{message: string, mode: 'url', url: string}
 * @phpstan-type ElicitResultWire array{action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}}
 * @phpstan-type ElicitResultFields array{action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type EmbeddedResourceWire array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}
 * @phpstan-type EmbeddedResourceFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, resource: Record<TextResourceContentsWire, TextResourceContentsFields>|Record<BlobResourceContentsWire, BlobResourceContentsFields>, type: 'resource'}
 * @phpstan-type EmptyResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type EmptyResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type EnumSchemaWire array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type EnumSchemaFields array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type ErrorWire array{code: int|float, data?: mixed, message: string}
 * @phpstan-type ErrorFields array{code: int|float, data?: mixed, message: string}
 * @phpstan-type GetPromptRequestWire array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetPromptRequestFields array{method: 'prompts/get', params: Record<GetPromptRequestParamsWire, GetPromptRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type GetPromptRequestParamsWire array{_meta: array<string, mixed>, inputResponses?: array<string, mixed>, requestState?: string, arguments?: array{...<string, string>}, name: string}
 * @phpstan-type GetPromptRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, inputResponses?: Record<InputResponsesWire, InputResponsesFields>, requestState?: string, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}
 * @phpstan-type GetPromptResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type GetPromptResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, description?: string, messages: list<Record<PromptMessageWire, PromptMessageFields>>, ...<string, mixed>}
 * @phpstan-type GetPromptResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type GetPromptResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<GetPromptResultWire, GetPromptResultFields>|Record<InputRequiredResultWire, InputRequiredResultFields>}
 * @phpstan-type HeaderMismatchErrorWire array{id?: string|int|float, jsonrpc: '2.0', error: array{code: -32020, data?: mixed, message: string}}
 * @phpstan-type HeaderMismatchErrorFields array{id?: string|int|float, jsonrpc: '2.0', error: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type IconWire array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}
 * @phpstan-type IconFields array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}
 * @phpstan-type IconsWire array{icons?: list<array<string, mixed>>}
 * @phpstan-type IconsFields array{icons?: list<Record<IconWire, IconFields>>}
 * @phpstan-type ImageContentWire array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}
 * @phpstan-type ImageContentFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'image'}
 * @phpstan-type ImplementationWire array{name: string, title?: string, icons?: list<array<string, mixed>>, description?: string, version: string, websiteUrl?: string}
 * @phpstan-type ImplementationFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, description?: string, version: string, websiteUrl?: string}
 * @phpstan-type InputRequestWire array{method: 'sampling/createMessage', params: array<string, mixed>}|array{method: 'roots/list', params?: array{_meta?: array<string, mixed>}}|array{method: 'elicitation/create', params: array<string, mixed>}
 * @phpstan-type InputRequestFields array{method: 'sampling/createMessage', params: Record<CreateMessageRequestParamsWire, CreateMessageRequestParamsFields>}|array{method: 'roots/list', params?: Record<array<string, mixed>, array<string, mixed>>}|array{method: 'elicitation/create', params: Record<ElicitRequestParamsWire, ElicitRequestParamsFields>}
 * @phpstan-type InputRequestsWire array{...<string, array<string, mixed>>}
 * @phpstan-type InputRequestsFields array{...<string, Record<InputRequestWire, InputRequestFields>>}
 * @phpstan-type InputRequiredResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, inputRequests?: array<string, mixed>, requestState?: string, ...<string, mixed>}
 * @phpstan-type InputRequiredResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, inputRequests?: Record<InputRequestsWire, InputRequestsFields>, requestState?: string, ...<string, mixed>}
 * @phpstan-type InputResponseWire array{_meta?: array<string, mixed>, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string}|array{roots: list<array<string, mixed>>}|array{action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}}
 * @phpstan-type InputResponseFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, content: Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>|list<Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string}|array{roots: list<Record<RootWire, RootFields>>}|array{action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type InputResponseRequestParamsWire array{_meta: array<string, mixed>, inputResponses?: array<string, mixed>, requestState?: string}
 * @phpstan-type InputResponseRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, inputResponses?: Record<InputResponsesWire, InputResponsesFields>, requestState?: string}
 * @phpstan-type InputResponsesWire array{...<string, array<string, mixed>>}
 * @phpstan-type InputResponsesFields array{...<string, Record<InputResponseWire, InputResponseFields>>}
 * @phpstan-type InternalErrorWire array{code: -32603, data?: mixed, message: string}
 * @phpstan-type InternalErrorFields array{code: -32603, data?: mixed, message: string}
 * @phpstan-type InvalidParamsErrorWire array{code: -32602, data?: mixed, message: string}
 * @phpstan-type InvalidParamsErrorFields array{code: -32602, data?: mixed, message: string}
 * @phpstan-type InvalidRequestErrorWire array{code: -32600, data?: mixed, message: string}
 * @phpstan-type InvalidRequestErrorFields array{code: -32600, data?: mixed, message: string}
 * @phpstan-type JSONObjectWire array{...<string, string|int|float|bool|null|mixed|list<mixed>>}
 * @phpstan-type JSONObjectFields array{...<string, string|int|float|bool|null|mixed|list<mixed>>}
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
 * @phpstan-type ListPromptsRequestWire array{method: 'prompts/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListPromptsRequestFields array{method: 'prompts/list', params: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListPromptsResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, prompts: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListPromptsResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, prompts: list<Record<PromptWire, PromptFields>>, ...<string, mixed>}
 * @phpstan-type ListPromptsResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type ListPromptsResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ListPromptsResultWire, ListPromptsResultFields>}
 * @phpstan-type ListResourceTemplatesRequestWire array{method: 'resources/templates/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourceTemplatesRequestFields array{method: 'resources/templates/list', params: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourceTemplatesResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListResourceTemplatesResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resourceTemplates: list<Record<ResourceTemplateWire, ResourceTemplateFields>>, ...<string, mixed>}
 * @phpstan-type ListResourceTemplatesResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type ListResourceTemplatesResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ListResourceTemplatesResultWire, ListResourceTemplatesResultFields>}
 * @phpstan-type ListResourcesRequestWire array{method: 'resources/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourcesRequestFields array{method: 'resources/list', params: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListResourcesResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resources: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListResourcesResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resources: list<Record<ResourceWire, ResourceFields>>, ...<string, mixed>}
 * @phpstan-type ListResourcesResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type ListResourcesResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ListResourcesResultWire, ListResourcesResultFields>}
 * @phpstan-type ListRootsRequestWire array{method: 'roots/list', params?: array{_meta?: array<string, mixed>}}
 * @phpstan-type ListRootsRequestFields array{method: 'roots/list', params?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ListRootsResultWire array{roots: list<array<string, mixed>>}
 * @phpstan-type ListRootsResultFields array{roots: list<Record<RootWire, RootFields>>}
 * @phpstan-type ListToolsRequestWire array{method: 'tools/list', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListToolsRequestFields array{method: 'tools/list', params: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type ListToolsResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, tools: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ListToolsResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, tools: list<Record<ToolWire, ToolFields>>, ...<string, mixed>}
 * @phpstan-type ListToolsResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type ListToolsResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ListToolsResultWire, ListToolsResultFields>}
 * @phpstan-type LoggingMessageNotificationWire array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type LoggingMessageNotificationFields array{method: 'notifications/message', params: Record<LoggingMessageNotificationParamsWire, LoggingMessageNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type LoggingMessageNotificationParamsWire array{_meta?: array<string, mixed>, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}
 * @phpstan-type LoggingMessageNotificationParamsFields array{_meta?: Record<NotificationMetaObjectWire, NotificationMetaObjectFields>, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}
 * @phpstan-type MetaObjectWire array<string, mixed>
 * @phpstan-type MetaObjectFields array<string, mixed>
 * @phpstan-type MethodNotFoundErrorWire array{code: -32601, data?: mixed, message: string}
 * @phpstan-type MethodNotFoundErrorFields array{code: -32601, data?: mixed, message: string}
 * @phpstan-type MissingRequiredClientCapabilityErrorWire array{id?: string|int|float, jsonrpc: '2.0', error: array{code: -32021, data: array{requiredCapabilities: array<string, mixed>}, message: string}}
 * @phpstan-type MissingRequiredClientCapabilityErrorFields array{id?: string|int|float, jsonrpc: '2.0', error: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ModelHintWire array{name?: string}
 * @phpstan-type ModelHintFields array{name?: string}
 * @phpstan-type ModelPreferencesWire array{costPriority?: int|float, hints?: list<array<string, mixed>>, intelligencePriority?: int|float, speedPriority?: int|float}
 * @phpstan-type ModelPreferencesFields array{costPriority?: int|float, hints?: list<Record<ModelHintWire, ModelHintFields>>, intelligencePriority?: int|float, speedPriority?: int|float}
 * @phpstan-type MultiSelectEnumSchemaWire array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type MultiSelectEnumSchemaFields array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type NotificationWire array{method: string, params?: array{...<string, mixed>}}
 * @phpstan-type NotificationFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type NotificationMetaObjectWire array{'io.modelcontextprotocol/subscriptionId'?: string|int|float, ...<string, mixed>}
 * @phpstan-type NotificationMetaObjectFields array{'io.modelcontextprotocol/subscriptionId'?: string|int|float, ...<string, mixed>}
 * @phpstan-type NotificationParamsWire array{_meta?: array<string, mixed>}
 * @phpstan-type NotificationParamsFields array{_meta?: Record<NotificationMetaObjectWire, NotificationMetaObjectFields>}
 * @phpstan-type NumberSchemaWire array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}
 * @phpstan-type NumberSchemaFields array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}
 * @phpstan-type PaginatedRequestWire array{method: string, params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type PaginatedRequestFields array{method: string, params: Record<PaginatedRequestParamsWire, PaginatedRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type PaginatedRequestParamsWire array{_meta: array<string, mixed>, cursor?: string}
 * @phpstan-type PaginatedRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, cursor?: string}
 * @phpstan-type PaginatedResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, ...<string, mixed>}
 * @phpstan-type PaginatedResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, nextCursor?: string, ...<string, mixed>}
 * @phpstan-type ParseErrorWire array{code: -32700, data?: mixed, message: string}
 * @phpstan-type ParseErrorFields array{code: -32700, data?: mixed, message: string}
 * @phpstan-type PrimitiveSchemaDefinitionWire array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type PrimitiveSchemaDefinitionFields array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}
 * @phpstan-type ProgressNotificationWire array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ProgressNotificationFields array{method: 'notifications/progress', params: Record<ProgressNotificationParamsWire, ProgressNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ProgressNotificationParamsWire array{_meta?: array<string, mixed>, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}
 * @phpstan-type ProgressNotificationParamsFields array{_meta?: Record<NotificationMetaObjectWire, NotificationMetaObjectFields>, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}
 * @phpstan-type PromptWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array<string, mixed>, arguments?: list<array<string, mixed>>, description?: string}
 * @phpstan-type PromptFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<MetaObjectWire, MetaObjectFields>, arguments?: list<Record<PromptArgumentWire, PromptArgumentFields>>, description?: string}
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
 * @phpstan-type ReadResourceRequestParamsWire array{_meta: array<string, mixed>, uri: string, inputResponses?: array<string, mixed>, requestState?: string}
 * @phpstan-type ReadResourceRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, uri: string, inputResponses?: Record<InputResponsesWire, InputResponsesFields>, requestState?: string}
 * @phpstan-type ReadResourceResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, contents: list<array<string, mixed>>, ...<string, mixed>}
 * @phpstan-type ReadResourceResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, contents: list<Record<TextResourceContentsWire, TextResourceContentsFields>|Record<BlobResourceContentsWire, BlobResourceContentsFields>>, ...<string, mixed>}
 * @phpstan-type ReadResourceResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type ReadResourceResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<ReadResourceResultWire, ReadResourceResultFields>|Record<InputRequiredResultWire, InputRequiredResultFields>}
 * @phpstan-type RequestWire array{method: string, params?: array{...<string, mixed>}}
 * @phpstan-type RequestFields array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type RequestMetaObjectWire array{'io.modelcontextprotocol/clientCapabilities': array<string, mixed>, 'io.modelcontextprotocol/clientInfo'?: array<string, mixed>, 'io.modelcontextprotocol/logLevel'?: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', 'io.modelcontextprotocol/protocolVersion': string, progressToken?: string|int|float, ...<string, mixed>}
 * @phpstan-type RequestMetaObjectFields array{'io.modelcontextprotocol/clientCapabilities': Record<ClientCapabilitiesWire, ClientCapabilitiesFields>, 'io.modelcontextprotocol/clientInfo'?: Record<ImplementationWire, ImplementationFields>, 'io.modelcontextprotocol/logLevel'?: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', 'io.modelcontextprotocol/protocolVersion': string, progressToken?: string|int|float, ...<string, mixed>}
 * @phpstan-type RequestParamsWire array{_meta: array<string, mixed>}
 * @phpstan-type RequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>}
 * @phpstan-type ResourceWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array<string, mixed>, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string}
 * @phpstan-type ResourceFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, size?: int|float, uri: string}
 * @phpstan-type ResourceContentsWire array{_meta?: array<string, mixed>, mimeType?: string, uri: string}
 * @phpstan-type ResourceContentsFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, mimeType?: string, uri: string}
 * @phpstan-type ResourceLinkWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array<string, mixed>, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}
 * @phpstan-type ResourceLinkFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}
 * @phpstan-type ResourceListChangedNotificationWire array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ResourceListChangedNotificationFields array{method: 'notifications/resources/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ResourceRequestParamsWire array{_meta: array<string, mixed>, uri: string}
 * @phpstan-type ResourceRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, uri: string}
 * @phpstan-type ResourceTemplateWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array<string, mixed>, annotations?: array<string, mixed>, description?: string, mimeType?: string, uriTemplate: string}
 * @phpstan-type ResourceTemplateFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, description?: string, mimeType?: string, uriTemplate: string}
 * @phpstan-type ResourceTemplateReferenceWire array{type: 'ref/resource', uri: string}
 * @phpstan-type ResourceTemplateReferenceFields array{type: 'ref/resource', uri: string}
 * @phpstan-type ResourceUpdatedNotificationWire array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ResourceUpdatedNotificationFields array{method: 'notifications/resources/updated', params: Record<ResourceUpdatedNotificationParamsWire, ResourceUpdatedNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ResourceUpdatedNotificationParamsWire array{_meta?: array<string, mixed>, uri: string}
 * @phpstan-type ResourceUpdatedNotificationParamsFields array{_meta?: Record<NotificationMetaObjectWire, NotificationMetaObjectFields>, uri: string}
 * @phpstan-type ResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type ResultFields array{_meta?: Record<ResultMetaObjectWire, ResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type ResultMetaObjectWire array{'io.modelcontextprotocol/serverInfo'?: array<string, mixed>, ...<string, mixed>}
 * @phpstan-type ResultMetaObjectFields array{'io.modelcontextprotocol/serverInfo'?: Record<ImplementationWire, ImplementationFields>, ...<string, mixed>}
 * @phpstan-type RootWire array{_meta?: array<string, mixed>, name?: string, uri: string}
 * @phpstan-type RootFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, name?: string, uri: string}
 * @phpstan-type SamplingMessageWire array{_meta?: array<string, mixed>, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant'}
 * @phpstan-type SamplingMessageFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, content: Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>|list<Record<SamplingMessageContentBlockWire, SamplingMessageContentBlockFields>>, role: 'user'|'assistant'}
 * @phpstan-type SamplingMessageContentBlockWire array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{_meta?: array<string, mixed>, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}|array{_meta?: array<string, mixed>, content: list<array<string, mixed>>, isError?: bool, structuredContent?: mixed, toolUseId: string, type: 'tool_result'}
 * @phpstan-type SamplingMessageContentBlockFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, text: string, type: 'text'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, data: string, mimeType: string, type: 'audio'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}|array{_meta?: Record<MetaObjectWire, MetaObjectFields>, content: list<Record<ContentBlockWire, ContentBlockFields>>, isError?: bool, structuredContent?: mixed, toolUseId: string, type: 'tool_result'}
 * @phpstan-type ServerCapabilitiesWire array{completions?: array<string, mixed>, experimental?: array{...<string, array<string, mixed>>}, extensions?: array{...<string, array<string, mixed>>}, logging?: array<string, mixed>, prompts?: array{listChanged?: bool}, resources?: array{listChanged?: bool, subscribe?: bool}, tools?: array{listChanged?: bool}}
 * @phpstan-type ServerCapabilitiesFields array{completions?: Record<JSONObjectWire, JSONObjectFields>, experimental?: Record<array<string, mixed>, array<string, mixed>>, extensions?: Record<array<string, mixed>, array<string, mixed>>, logging?: Record<JSONObjectWire, JSONObjectFields>, prompts?: Record<array<string, mixed>, array<string, mixed>>, resources?: Record<array<string, mixed>, array<string, mixed>>, tools?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ServerNotificationWire array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/subscriptions/acknowledged', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ServerNotificationFields array{method: 'notifications/cancelled', params: Record<CancelledNotificationParamsWire, CancelledNotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<ProgressNotificationParamsWire, ProgressNotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: Record<LoggingMessageNotificationParamsWire, LoggingMessageNotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: Record<ResourceUpdatedNotificationParamsWire, ResourceUpdatedNotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}|array{method: 'notifications/subscriptions/acknowledged', params: Record<SubscriptionsAcknowledgedNotificationParamsWire, SubscriptionsAcknowledgedNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ServerResultWire array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, capabilities: array<string, mixed>, instructions?: string, supportedVersions: list<string>, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, prompts: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resources: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, contents: list<array<string, mixed>>, ...<string, mixed>}|array{_meta: array<string, mixed>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, content: list<array<string, mixed>>, isError?: bool, structuredContent?: mixed, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, tools: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array<string, mixed>, resultType: 'complete'|'input_required'|string, inputRequests?: array<string, mixed>, requestState?: string, ...<string, mixed>}
 * @phpstan-type ServerResultFields array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, capabilities: Record<array<string, mixed>, array<string, mixed>>, instructions?: string, supportedVersions: list<string>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, description?: string, messages: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, prompts: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resourceTemplates: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, resources: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, cacheScope: 'public'|'private', ttlMs: int|float, contents: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: mixed, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, nextCursor?: string, cacheScope: 'public'|'private', ttlMs: int|float, tools: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, resultType: 'complete'|'input_required'|string, inputRequests?: Record<array<string, mixed>, array<string, mixed>>, requestState?: string, ...<string, mixed>}
 * @phpstan-type SingleSelectEnumSchemaWire array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}
 * @phpstan-type SingleSelectEnumSchemaFields array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}
 * @phpstan-type StringSchemaWire array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}
 * @phpstan-type StringSchemaFields array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}
 * @phpstan-type SubscriptionFilterWire array{promptsListChanged?: bool, resourcesListChanged?: bool, resourceSubscriptions?: list<string>, toolsListChanged?: bool}
 * @phpstan-type SubscriptionFilterFields array{promptsListChanged?: bool, resourcesListChanged?: bool, resourceSubscriptions?: list<string>, toolsListChanged?: bool}
 * @phpstan-type SubscriptionsAcknowledgedNotificationWire array{method: 'notifications/subscriptions/acknowledged', params: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type SubscriptionsAcknowledgedNotificationFields array{method: 'notifications/subscriptions/acknowledged', params: Record<SubscriptionsAcknowledgedNotificationParamsWire, SubscriptionsAcknowledgedNotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type SubscriptionsAcknowledgedNotificationParamsWire array{_meta?: array<string, mixed>, notifications: array<string, mixed>}
 * @phpstan-type SubscriptionsAcknowledgedNotificationParamsFields array{_meta?: Record<NotificationMetaObjectWire, NotificationMetaObjectFields>, notifications: Record<SubscriptionFilterWire, SubscriptionFilterFields>}
 * @phpstan-type SubscriptionsListenRequestWire array{method: 'subscriptions/listen', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type SubscriptionsListenRequestFields array{method: 'subscriptions/listen', params: Record<SubscriptionsListenRequestParamsWire, SubscriptionsListenRequestParamsFields>, id: string|int|float, jsonrpc: '2.0'}
 * @phpstan-type SubscriptionsListenRequestParamsWire array{_meta: array<string, mixed>, notifications: array<string, mixed>}
 * @phpstan-type SubscriptionsListenRequestParamsFields array{_meta: Record<RequestMetaObjectWire, RequestMetaObjectFields>, notifications: Record<SubscriptionFilterWire, SubscriptionFilterFields>}
 * @phpstan-type SubscriptionsListenResultWire array{_meta: array<string, mixed>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type SubscriptionsListenResultFields array{_meta: Record<SubscriptionsListenResultMetaObjectWire, SubscriptionsListenResultMetaObjectFields>, resultType: 'complete'|'input_required'|string, ...<string, mixed>}
 * @phpstan-type SubscriptionsListenResultMetaObjectWire array{'io.modelcontextprotocol/serverInfo'?: array<string, mixed>, 'io.modelcontextprotocol/subscriptionId': string|int|float, ...<string, mixed>}
 * @phpstan-type SubscriptionsListenResultMetaObjectFields array{'io.modelcontextprotocol/serverInfo'?: Record<ImplementationWire, ImplementationFields>, 'io.modelcontextprotocol/subscriptionId': string|int|float, ...<string, mixed>}
 * @phpstan-type SubscriptionsListenResultResponseWire array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}
 * @phpstan-type SubscriptionsListenResultResponseFields array{id: string|int|float, jsonrpc: '2.0', result: Record<SubscriptionsListenResultWire, SubscriptionsListenResultFields>}
 * @phpstan-type TextContentWire array{_meta?: array<string, mixed>, annotations?: array<string, mixed>, text: string, type: 'text'}
 * @phpstan-type TextContentFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<AnnotationsWire, AnnotationsFields>, text: string, type: 'text'}
 * @phpstan-type TextResourceContentsWire array{_meta?: array<string, mixed>, mimeType?: string, uri: string, text: string}
 * @phpstan-type TextResourceContentsFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, mimeType?: string, uri: string, text: string}
 * @phpstan-type TitledMultiSelectEnumSchemaWire array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type TitledMultiSelectEnumSchemaFields array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type TitledSingleSelectEnumSchemaWire array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}
 * @phpstan-type TitledSingleSelectEnumSchemaFields array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}
 * @phpstan-type ToolWire array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array<string, mixed>, annotations?: array<string, mixed>, description?: string, inputSchema: array{'$schema'?: string, type: 'object', ...<string, mixed>}, outputSchema?: array{'$schema'?: string, ...<string, mixed>}}
 * @phpstan-type ToolFields array{name: string, title?: string, icons?: list<Record<IconWire, IconFields>>, _meta?: Record<MetaObjectWire, MetaObjectFields>, annotations?: Record<ToolAnnotationsWire, ToolAnnotationsFields>, description?: string, inputSchema: Record<array<string, mixed>, array<string, mixed>>, outputSchema?: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type ToolAnnotationsWire array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}
 * @phpstan-type ToolAnnotationsFields array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}
 * @phpstan-type ToolChoiceWire array{mode?: 'auto'|'required'|'none'}
 * @phpstan-type ToolChoiceFields array{mode?: 'auto'|'required'|'none'}
 * @phpstan-type ToolListChangedNotificationWire array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}
 * @phpstan-type ToolListChangedNotificationFields array{method: 'notifications/tools/list_changed', params?: Record<NotificationParamsWire, NotificationParamsFields>, jsonrpc: '2.0'}
 * @phpstan-type ToolResultContentWire array{_meta?: array<string, mixed>, content: list<array<string, mixed>>, isError?: bool, structuredContent?: mixed, toolUseId: string, type: 'tool_result'}
 * @phpstan-type ToolResultContentFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, content: list<Record<ContentBlockWire, ContentBlockFields>>, isError?: bool, structuredContent?: mixed, toolUseId: string, type: 'tool_result'}
 * @phpstan-type ToolUseContentWire array{_meta?: array<string, mixed>, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}
 * @phpstan-type ToolUseContentFields array{_meta?: Record<MetaObjectWire, MetaObjectFields>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}
 * @phpstan-type UnsupportedProtocolVersionErrorWire array{id?: string|int|float, jsonrpc: '2.0', error: array{code: -32022, data: array{requested: string, supported: list<string>}, message: string}}
 * @phpstan-type UnsupportedProtocolVersionErrorFields array{id?: string|int|float, jsonrpc: '2.0', error: Record<array<string, mixed>, array<string, mixed>>}
 * @phpstan-type UntitledMultiSelectEnumSchemaWire array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type UntitledMultiSelectEnumSchemaFields array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}
 * @phpstan-type UntitledSingleSelectEnumSchemaWire array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}
 * @phpstan-type UntitledSingleSelectEnumSchemaFields array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}
 */
final class V20260728Schema extends GenericRevisionSchema
{
    public const REVISION = '2026-07-28';

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


    /** @return Type<CacheableResultWire, CacheableResultFields> */
    public function cacheableResult(): Type
    {
        /** @var Type<CacheableResultWire, CacheableResultFields> $type */
        $type = $this->type('CacheableResult');
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


    /** @return Type<CallToolResultResponseWire, CallToolResultResponseFields> */
    public function callToolResultResponse(): Type
    {
        /** @var Type<CallToolResultResponseWire, CallToolResultResponseFields> $type */
        $type = $this->type('CallToolResultResponse');
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


    /** @return Type<CompleteResultResponseWire, CompleteResultResponseFields> */
    public function completeResultResponse(): Type
    {
        /** @var Type<CompleteResultResponseWire, CompleteResultResponseFields> $type */
        $type = $this->type('CompleteResultResponse');
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


    /** @return Type<DiscoverRequestWire, DiscoverRequestFields> */
    public function discoverRequest(): Type
    {
        /** @var Type<DiscoverRequestWire, DiscoverRequestFields> $type */
        $type = $this->type('DiscoverRequest');
        return $type;
    }


    /** @return Type<DiscoverResultWire, DiscoverResultFields> */
    public function discoverResult(): Type
    {
        /** @var Type<DiscoverResultWire, DiscoverResultFields> $type */
        $type = $this->type('DiscoverResult');
        return $type;
    }


    /** @return Type<DiscoverResultResponseWire, DiscoverResultResponseFields> */
    public function discoverResultResponse(): Type
    {
        /** @var Type<DiscoverResultResponseWire, DiscoverResultResponseFields> $type */
        $type = $this->type('DiscoverResultResponse');
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


    /** @return Type<GetPromptResultResponseWire, GetPromptResultResponseFields> */
    public function getPromptResultResponse(): Type
    {
        /** @var Type<GetPromptResultResponseWire, GetPromptResultResponseFields> $type */
        $type = $this->type('GetPromptResultResponse');
        return $type;
    }


    /** @return Type<HeaderMismatchErrorWire, HeaderMismatchErrorFields> */
    public function headerMismatchError(): Type
    {
        /** @var Type<HeaderMismatchErrorWire, HeaderMismatchErrorFields> $type */
        $type = $this->type('HeaderMismatchError');
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


    /** @return Type<InputRequestWire, InputRequestFields> */
    public function inputRequest(): Type
    {
        /** @var Type<InputRequestWire, InputRequestFields> $type */
        $type = $this->type('InputRequest');
        return $type;
    }


    /** @return Type<InputRequestsWire, InputRequestsFields> */
    public function inputRequests(): Type
    {
        /** @var Type<InputRequestsWire, InputRequestsFields> $type */
        $type = $this->type('InputRequests');
        return $type;
    }


    /** @return Type<InputRequiredResultWire, InputRequiredResultFields> */
    public function inputRequiredResult(): Type
    {
        /** @var Type<InputRequiredResultWire, InputRequiredResultFields> $type */
        $type = $this->type('InputRequiredResult');
        return $type;
    }


    /** @return Type<InputResponseWire, InputResponseFields> */
    public function inputResponse(): Type
    {
        /** @var Type<InputResponseWire, InputResponseFields> $type */
        $type = $this->type('InputResponse');
        return $type;
    }


    /** @return Type<InputResponseRequestParamsWire, InputResponseRequestParamsFields> */
    public function inputResponseRequestParams(): Type
    {
        /** @var Type<InputResponseRequestParamsWire, InputResponseRequestParamsFields> $type */
        $type = $this->type('InputResponseRequestParams');
        return $type;
    }


    /** @return Type<InputResponsesWire, InputResponsesFields> */
    public function inputResponses(): Type
    {
        /** @var Type<InputResponsesWire, InputResponsesFields> $type */
        $type = $this->type('InputResponses');
        return $type;
    }


    /** @return Type<InternalErrorWire, InternalErrorFields> */
    public function internalError(): Type
    {
        /** @var Type<InternalErrorWire, InternalErrorFields> $type */
        $type = $this->type('InternalError');
        return $type;
    }


    /** @return Type<InvalidParamsErrorWire, InvalidParamsErrorFields> */
    public function invalidParamsError(): Type
    {
        /** @var Type<InvalidParamsErrorWire, InvalidParamsErrorFields> $type */
        $type = $this->type('InvalidParamsError');
        return $type;
    }


    /** @return Type<InvalidRequestErrorWire, InvalidRequestErrorFields> */
    public function invalidRequestError(): Type
    {
        /** @var Type<InvalidRequestErrorWire, InvalidRequestErrorFields> $type */
        $type = $this->type('InvalidRequestError');
        return $type;
    }


    /** @return Type<JSONObjectWire, JSONObjectFields> */
    public function jsonObject(): Type
    {
        /** @var Type<JSONObjectWire, JSONObjectFields> $type */
        $type = $this->type('JSONObject');
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


    /** @return Type<ListPromptsResultResponseWire, ListPromptsResultResponseFields> */
    public function listPromptsResultResponse(): Type
    {
        /** @var Type<ListPromptsResultResponseWire, ListPromptsResultResponseFields> $type */
        $type = $this->type('ListPromptsResultResponse');
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


    /** @return Type<ListResourceTemplatesResultResponseWire, ListResourceTemplatesResultResponseFields> */
    public function listResourceTemplatesResultResponse(): Type
    {
        /** @var Type<ListResourceTemplatesResultResponseWire, ListResourceTemplatesResultResponseFields> $type */
        $type = $this->type('ListResourceTemplatesResultResponse');
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


    /** @return Type<ListResourcesResultResponseWire, ListResourcesResultResponseFields> */
    public function listResourcesResultResponse(): Type
    {
        /** @var Type<ListResourcesResultResponseWire, ListResourcesResultResponseFields> $type */
        $type = $this->type('ListResourcesResultResponse');
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


    /** @return Type<ListToolsResultResponseWire, ListToolsResultResponseFields> */
    public function listToolsResultResponse(): Type
    {
        /** @var Type<ListToolsResultResponseWire, ListToolsResultResponseFields> $type */
        $type = $this->type('ListToolsResultResponse');
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


    /** @return Type<MetaObjectWire, MetaObjectFields> */
    public function metaObject(): Type
    {
        /** @var Type<MetaObjectWire, MetaObjectFields> $type */
        $type = $this->type('MetaObject');
        return $type;
    }


    /** @return Type<MethodNotFoundErrorWire, MethodNotFoundErrorFields> */
    public function methodNotFoundError(): Type
    {
        /** @var Type<MethodNotFoundErrorWire, MethodNotFoundErrorFields> $type */
        $type = $this->type('MethodNotFoundError');
        return $type;
    }


    /** @return Type<MissingRequiredClientCapabilityErrorWire, MissingRequiredClientCapabilityErrorFields> */
    public function missingRequiredClientCapabilityError(): Type
    {
        /** @var Type<MissingRequiredClientCapabilityErrorWire, MissingRequiredClientCapabilityErrorFields> $type */
        $type = $this->type('MissingRequiredClientCapabilityError');
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


    /** @return Type<NotificationMetaObjectWire, NotificationMetaObjectFields> */
    public function notificationMetaObject(): Type
    {
        /** @var Type<NotificationMetaObjectWire, NotificationMetaObjectFields> $type */
        $type = $this->type('NotificationMetaObject');
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


    /** @return Type<ParseErrorWire, ParseErrorFields> */
    public function parseError(): Type
    {
        /** @var Type<ParseErrorWire, ParseErrorFields> $type */
        $type = $this->type('ParseError');
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


    /** @return Type<ReadResourceResultResponseWire, ReadResourceResultResponseFields> */
    public function readResourceResultResponse(): Type
    {
        /** @var Type<ReadResourceResultResponseWire, ReadResourceResultResponseFields> $type */
        $type = $this->type('ReadResourceResultResponse');
        return $type;
    }


    /** @return Type<RequestWire, RequestFields> */
    public function request(): Type
    {
        /** @var Type<RequestWire, RequestFields> $type */
        $type = $this->type('Request');
        return $type;
    }


    /** @return Type<RequestMetaObjectWire, RequestMetaObjectFields> */
    public function requestMetaObject(): Type
    {
        /** @var Type<RequestMetaObjectWire, RequestMetaObjectFields> $type */
        $type = $this->type('RequestMetaObject');
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


    /** @return Type<ResultMetaObjectWire, ResultMetaObjectFields> */
    public function resultMetaObject(): Type
    {
        /** @var Type<ResultMetaObjectWire, ResultMetaObjectFields> $type */
        $type = $this->type('ResultMetaObject');
        return $type;
    }


    /** @return Type<RootWire, RootFields> */
    public function root(): Type
    {
        /** @var Type<RootWire, RootFields> $type */
        $type = $this->type('Root');
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


    /** @return Type<ServerResultWire, ServerResultFields> */
    public function serverResult(): Type
    {
        /** @var Type<ServerResultWire, ServerResultFields> $type */
        $type = $this->type('ServerResult');
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


    /** @return Type<SubscriptionFilterWire, SubscriptionFilterFields> */
    public function subscriptionFilter(): Type
    {
        /** @var Type<SubscriptionFilterWire, SubscriptionFilterFields> $type */
        $type = $this->type('SubscriptionFilter');
        return $type;
    }


    /** @return Type<SubscriptionsAcknowledgedNotificationWire, SubscriptionsAcknowledgedNotificationFields> */
    public function subscriptionsAcknowledgedNotification(): Type
    {
        /** @var Type<SubscriptionsAcknowledgedNotificationWire, SubscriptionsAcknowledgedNotificationFields> $type */
        $type = $this->type('SubscriptionsAcknowledgedNotification');
        return $type;
    }


    /** @return Type<SubscriptionsAcknowledgedNotificationParamsWire, SubscriptionsAcknowledgedNotificationParamsFields> */
    public function subscriptionsAcknowledgedNotificationParams(): Type
    {
        /** @var Type<SubscriptionsAcknowledgedNotificationParamsWire, SubscriptionsAcknowledgedNotificationParamsFields> $type */
        $type = $this->type('SubscriptionsAcknowledgedNotificationParams');
        return $type;
    }


    /** @return Type<SubscriptionsListenRequestWire, SubscriptionsListenRequestFields> */
    public function subscriptionsListenRequest(): Type
    {
        /** @var Type<SubscriptionsListenRequestWire, SubscriptionsListenRequestFields> $type */
        $type = $this->type('SubscriptionsListenRequest');
        return $type;
    }


    /** @return Type<SubscriptionsListenRequestParamsWire, SubscriptionsListenRequestParamsFields> */
    public function subscriptionsListenRequestParams(): Type
    {
        /** @var Type<SubscriptionsListenRequestParamsWire, SubscriptionsListenRequestParamsFields> $type */
        $type = $this->type('SubscriptionsListenRequestParams');
        return $type;
    }


    /** @return Type<SubscriptionsListenResultWire, SubscriptionsListenResultFields> */
    public function subscriptionsListenResult(): Type
    {
        /** @var Type<SubscriptionsListenResultWire, SubscriptionsListenResultFields> $type */
        $type = $this->type('SubscriptionsListenResult');
        return $type;
    }


    /** @return Type<SubscriptionsListenResultMetaObjectWire, SubscriptionsListenResultMetaObjectFields> */
    public function subscriptionsListenResultMetaObject(): Type
    {
        /** @var Type<SubscriptionsListenResultMetaObjectWire, SubscriptionsListenResultMetaObjectFields> $type */
        $type = $this->type('SubscriptionsListenResultMetaObject');
        return $type;
    }


    /** @return Type<SubscriptionsListenResultResponseWire, SubscriptionsListenResultResponseFields> */
    public function subscriptionsListenResultResponse(): Type
    {
        /** @var Type<SubscriptionsListenResultResponseWire, SubscriptionsListenResultResponseFields> $type */
        $type = $this->type('SubscriptionsListenResultResponse');
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


    /** @return Type<UnsupportedProtocolVersionErrorWire, UnsupportedProtocolVersionErrorFields> */
    public function unsupportedProtocolVersionError(): Type
    {
        /** @var Type<UnsupportedProtocolVersionErrorWire, UnsupportedProtocolVersionErrorFields> $type */
        $type = $this->type('UnsupportedProtocolVersionError');
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

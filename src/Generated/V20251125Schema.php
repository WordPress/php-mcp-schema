<?php

declare(strict_types=1);

namespace WP\McpSchema\Generated;

use WP\McpSchema\Contract\Type;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Runtime\GenericRevisionSchema;

/** Generated discoverable catalog for MCP 2025-11-25. */
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

    /** @return Type<array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}, array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}> */
    public function annotations(): Type
    {
        /** @var Type<array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}, array{audience?: list<'user'|'assistant'>, lastModified?: string, priority?: int|float}> $type */
        $type = $this->type('Annotations');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'audio'}> */
    public function audioContent(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'audio'}> $type */
        $type = $this->type('AudioContent');
        return $type;
    }


    /** @return Type<array{name: string, title?: string}, array{name: string, title?: string}> */
    public function baseMetadata(): Type
    {
        /** @var Type<array{name: string, title?: string}, array{name: string, title?: string}> $type */
        $type = $this->type('BaseMetadata');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string, blob: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string, blob: string}> */
    public function blobResourceContents(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string, blob: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string, blob: string}> $type */
        $type = $this->type('BlobResourceContents');
        return $type;
    }


    /** @return Type<array{default?: bool, description?: string, title?: string, type: 'boolean'}, array{default?: bool, description?: string, title?: string, type: 'boolean'}> */
    public function booleanSchema(): Type
    {
        /** @var Type<array{default?: bool, description?: string, title?: string, type: 'boolean'}, array{default?: bool, description?: string, title?: string, type: 'boolean'}> $type */
        $type = $this->type('BooleanSchema');
        return $type;
    }


    /** @return Type<array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tools/call', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function callToolRequest(): Type
    {
        /** @var Type<array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tools/call', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('CallToolRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, arguments?: array{...<string, mixed>}, name: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}> */
    public function callToolRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, arguments?: array{...<string, mixed>}, name: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}> $type */
        $type = $this->type('CallToolRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function callToolResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('CallToolResult');
        return $type;
    }


    /** @return Type<array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function cancelTaskRequest(): Type
    {
        /** @var Type<array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('CancelTaskRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}> */
    public function cancelTaskResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}> $type */
        $type = $this->type('CancelTaskResult');
        return $type;
    }


    /** @return Type<array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function cancelledNotification(): Type
    {
        /** @var Type<array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('CancelledNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, reason?: string, requestId?: string|int|float}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, reason?: string, requestId?: string|int|float}> */
    public function cancelledNotificationParams(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, reason?: string, requestId?: string|int|float}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, reason?: string, requestId?: string|int|float}> $type */
        $type = $this->type('CancelledNotificationParams');
        return $type;
    }


    /** @return Type<array{elicitation?: array{form?: array<string, mixed>, url?: array<string, mixed>}, experimental?: array{...<string, array<string, mixed>>}, roots?: array{listChanged?: bool}, sampling?: array{context?: array<string, mixed>, tools?: array<string, mixed>}, tasks?: array{cancel?: array<string, mixed>, list?: array<string, mixed>, requests?: array{elicitation?: array{create?: array<string, mixed>}, sampling?: array{createMessage?: array<string, mixed>}}}}, array{elicitation?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, roots?: Record<array<string, mixed>, array<string, mixed>>, sampling?: Record<array<string, mixed>, array<string, mixed>>, tasks?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function clientCapabilities(): Type
    {
        /** @var Type<array{elicitation?: array{form?: array<string, mixed>, url?: array<string, mixed>}, experimental?: array{...<string, array<string, mixed>>}, roots?: array{listChanged?: bool}, sampling?: array{context?: array<string, mixed>, tools?: array<string, mixed>}, tasks?: array{cancel?: array<string, mixed>, list?: array<string, mixed>, requests?: array{elicitation?: array{create?: array<string, mixed>}, sampling?: array{createMessage?: array<string, mixed>}}}}, array{elicitation?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, roots?: Record<array<string, mixed>, array<string, mixed>>, sampling?: Record<array<string, mixed>, array<string, mixed>>, tasks?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('ClientCapabilities');
        return $type;
    }


    /** @return Type<array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/initialized', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/roots/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/initialized', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/roots/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function clientNotification(): Type
    {
        /** @var Type<array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/initialized', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/roots/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/initialized', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/roots/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ClientNotification');
        return $type;
    }


    /** @return Type<array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'initialize', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'logging/setLevel', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/subscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/unsubscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'initialize', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'logging/setLevel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/subscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/unsubscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function clientRequest(): Type
    {
        /** @var Type<array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'initialize', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'logging/setLevel', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/subscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/unsubscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'initialize', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'completion/complete', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'logging/setLevel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'prompts/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/templates/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/read', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/subscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'resources/unsubscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/call', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tools/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ClientRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, roots: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<array<string, mixed>, array<string, mixed>>|list<Record<array<string, mixed>, array<string, mixed>>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, roots: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function clientResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, roots: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<array<string, mixed>, array<string, mixed>>|list<Record<array<string, mixed>, array<string, mixed>>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, roots: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ClientResult');
        return $type;
    }


    /** @return Type<array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'completion/complete', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function completeRequest(): Type
    {
        /** @var Type<array{method: 'completion/complete', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'completion/complete', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('CompleteRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, argument: array{name: string, value: string}, context?: array{arguments?: array{...<string, string>}}, ref: array<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, argument: Record<array<string, mixed>, array<string, mixed>>, context?: Record<array<string, mixed>, array<string, mixed>>, ref: Record<array<string, mixed>, array<string, mixed>>}> */
    public function completeRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, argument: array{name: string, value: string}, context?: array{arguments?: array{...<string, string>}}, ref: array<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, argument: Record<array<string, mixed>, array<string, mixed>>, context?: Record<array<string, mixed>, array<string, mixed>>, ref: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('CompleteRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function completeResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('CompleteResult');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, text: string, type: 'text'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, resource: Record<array<string, mixed>, array<string, mixed>>, type: 'resource'}> */
    public function contentBlock(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, text: string, type: 'text'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'audio'}|array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, resource: Record<array<string, mixed>, array<string, mixed>>, type: 'resource'}> $type */
        $type = $this->type('ContentBlock');
        return $type;
    }


    /** @return Type<array{method: 'sampling/createMessage', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'sampling/createMessage', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function createMessageRequest(): Type
    {
        /** @var Type<array{method: 'sampling/createMessage', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'sampling/createMessage', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('CreateMessageRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<array<string, mixed>>, metadata?: array<string, mixed>, modelPreferences?: array<string, mixed>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: array<string, mixed>, tools?: list<array<string, mixed>>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<Record<array<string, mixed>, array<string, mixed>>>, metadata?: Record<array<string, mixed>, array<string, mixed>>, modelPreferences?: Record<array<string, mixed>, array<string, mixed>>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: Record<array<string, mixed>, array<string, mixed>>, tools?: list<Record<array<string, mixed>, array<string, mixed>>>}> */
    public function createMessageRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<array<string, mixed>>, metadata?: array<string, mixed>, modelPreferences?: array<string, mixed>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: array<string, mixed>, tools?: list<array<string, mixed>>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, includeContext?: 'none'|'thisServer'|'allServers', maxTokens: int|float, messages: list<Record<array<string, mixed>, array<string, mixed>>>, metadata?: Record<array<string, mixed>, array<string, mixed>>, modelPreferences?: Record<array<string, mixed>, array<string, mixed>>, stopSequences?: list<string>, systemPrompt?: string, temperature?: int|float, toolChoice?: Record<array<string, mixed>, array<string, mixed>>, tools?: list<Record<array<string, mixed>, array<string, mixed>>>}> $type */
        $type = $this->type('CreateMessageRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<array<string, mixed>, array<string, mixed>>|list<Record<array<string, mixed>, array<string, mixed>>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}> */
    public function createMessageResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<array<string, mixed>, array<string, mixed>>|list<Record<array<string, mixed>, array<string, mixed>>>, role: 'user'|'assistant', model: string, stopReason?: 'endTurn'|'stopSequence'|'maxTokens'|'toolUse'|string, ...<string, mixed>}> $type */
        $type = $this->type('CreateMessageResult');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, task: array<string, mixed>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function createTaskResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, task: array<string, mixed>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('CreateTaskResult');
        return $type;
    }


    /** @return Type<array{method: 'elicitation/create', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'elicitation/create', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function elicitRequest(): Type
    {
        /** @var Type<array{method: 'elicitation/create', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'elicitation/create', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ElicitRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}> */
    public function elicitRequestFormParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('ElicitRequestFormParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}|array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, elicitationId: string, message: string, mode: 'url', url: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, elicitationId: string, message: string, mode: 'url', url: string}> */
    public function elicitRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, message: string, mode?: 'form', requestedSchema: array{'$schema'?: string, properties: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}|array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, elicitationId: string, message: string, mode: 'url', url: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, message: string, mode?: 'form', requestedSchema: Record<array<string, mixed>, array<string, mixed>>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, elicitationId: string, message: string, mode: 'url', url: string}> $type */
        $type = $this->type('ElicitRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, elicitationId: string, message: string, mode: 'url', url: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, elicitationId: string, message: string, mode: 'url', url: string}> */
    public function elicitRequestURLParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>, elicitationId: string, message: string, mode: 'url', url: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>, elicitationId: string, message: string, mode: 'url', url: string}> $type */
        $type = $this->type('ElicitRequestURLParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function elicitResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, action: 'accept'|'decline'|'cancel', content?: array{...<string, string|int|float|bool|list<string>>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, action: 'accept'|'decline'|'cancel', content?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('ElicitResult');
        return $type;
    }


    /** @return Type<array{method: 'notifications/elicitation/complete', params: array{elicitationId: string}, jsonrpc: '2.0'}, array{method: 'notifications/elicitation/complete', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function elicitationCompleteNotification(): Type
    {
        /** @var Type<array{method: 'notifications/elicitation/complete', params: array{elicitationId: string}, jsonrpc: '2.0'}, array{method: 'notifications/elicitation/complete', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ElicitationCompleteNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, resource: Record<array<string, mixed>, array<string, mixed>>, type: 'resource'}> */
    public function embeddedResource(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, resource: array<string, mixed>, type: 'resource'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, resource: Record<array<string, mixed>, array<string, mixed>>, type: 'resource'}> $type */
        $type = $this->type('EmbeddedResource');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function emptyResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('EmptyResult');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}> */
    public function enumSchema(): Type
    {
        /** @var Type<array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}> $type */
        $type = $this->type('EnumSchema');
        return $type;
    }


    /** @return Type<array{code: int|float, data?: mixed, message: string}, array{code: int|float, data?: mixed, message: string}> */
    public function error(): Type
    {
        /** @var Type<array{code: int|float, data?: mixed, message: string}, array{code: int|float, data?: mixed, message: string}> $type */
        $type = $this->type('Error');
        return $type;
    }


    /** @return Type<array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'prompts/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function getPromptRequest(): Type
    {
        /** @var Type<array{method: 'prompts/get', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'prompts/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('GetPromptRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, arguments?: array{...<string, string>}, name: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}> */
    public function getPromptRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, arguments?: array{...<string, string>}, name: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, arguments?: Record<array<string, mixed>, array<string, mixed>>, name: string}> $type */
        $type = $this->type('GetPromptRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, description?: string, messages: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function getPromptResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, description?: string, messages: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('GetPromptResult');
        return $type;
    }


    /** @return Type<array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function getTaskPayloadRequest(): Type
    {
        /** @var Type<array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('GetTaskPayloadRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function getTaskPayloadResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('GetTaskPayloadResult');
        return $type;
    }


    /** @return Type<array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function getTaskRequest(): Type
    {
        /** @var Type<array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('GetTaskRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}> */
    public function getTaskResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}> $type */
        $type = $this->type('GetTaskResult');
        return $type;
    }


    /** @return Type<array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}, array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}> */
    public function icon(): Type
    {
        /** @var Type<array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}, array{mimeType?: string, sizes?: list<string>, src: string, theme?: 'light'|'dark'}> $type */
        $type = $this->type('Icon');
        return $type;
    }


    /** @return Type<array{icons?: list<array<string, mixed>>}, array{icons?: list<Record<array<string, mixed>, array<string, mixed>>>}> */
    public function icons(): Type
    {
        /** @var Type<array{icons?: list<array<string, mixed>>}, array{icons?: list<Record<array<string, mixed>, array<string, mixed>>>}> $type */
        $type = $this->type('Icons');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'image'}> */
    public function imageContent(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'image'}> $type */
        $type = $this->type('ImageContent');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, description?: string, version: string, websiteUrl?: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, description?: string, version: string, websiteUrl?: string}> */
    public function implementation(): Type
    {
        /** @var Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, description?: string, version: string, websiteUrl?: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, description?: string, version: string, websiteUrl?: string}> $type */
        $type = $this->type('Implementation');
        return $type;
    }


    /** @return Type<array{method: 'initialize', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'initialize', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function initializeRequest(): Type
    {
        /** @var Type<array{method: 'initialize', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'initialize', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('InitializeRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, capabilities: array<string, mixed>, clientInfo: array<string, mixed>, protocolVersion: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, clientInfo: Record<array<string, mixed>, array<string, mixed>>, protocolVersion: string}> */
    public function initializeRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, capabilities: array<string, mixed>, clientInfo: array<string, mixed>, protocolVersion: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, clientInfo: Record<array<string, mixed>, array<string, mixed>>, protocolVersion: string}> $type */
        $type = $this->type('InitializeRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, capabilities: array<string, mixed>, instructions?: string, protocolVersion: string, serverInfo: array<string, mixed>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, instructions?: string, protocolVersion: string, serverInfo: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function initializeResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, capabilities: array<string, mixed>, instructions?: string, protocolVersion: string, serverInfo: array<string, mixed>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, instructions?: string, protocolVersion: string, serverInfo: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('InitializeResult');
        return $type;
    }


    /** @return Type<array{method: 'notifications/initialized', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/initialized', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function initializedNotification(): Type
    {
        /** @var Type<array{method: 'notifications/initialized', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/initialized', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('InitializedNotification');
        return $type;
    }


    /** @return Type<array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}, array{error: Record<array<string, mixed>, array<string, mixed>>, id?: string|int|float, jsonrpc: '2.0'}> */
    public function jSONRPCErrorResponse(): Type
    {
        /** @var Type<array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}, array{error: Record<array<string, mixed>, array<string, mixed>>, id?: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('JSONRPCErrorResponse');
        return $type;
    }


    /** @return Type<array{method: string, params?: array{...<string, mixed>}, id: string|int|float, jsonrpc: '2.0'}|array{method: string, params?: array{...<string, mixed>}, jsonrpc: '2.0'}|array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}|array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{id: string|int|float, jsonrpc: '2.0', result: Record<array<string, mixed>, array<string, mixed>>}|array{error: Record<array<string, mixed>, array<string, mixed>>, id?: string|int|float, jsonrpc: '2.0'}> */
    public function jSONRPCMessage(): Type
    {
        /** @var Type<array{method: string, params?: array{...<string, mixed>}, id: string|int|float, jsonrpc: '2.0'}|array{method: string, params?: array{...<string, mixed>}, jsonrpc: '2.0'}|array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}|array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{id: string|int|float, jsonrpc: '2.0', result: Record<array<string, mixed>, array<string, mixed>>}|array{error: Record<array<string, mixed>, array<string, mixed>>, id?: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('JSONRPCMessage');
        return $type;
    }


    /** @return Type<array{method: string, params?: array{...<string, mixed>}, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function jSONRPCNotification(): Type
    {
        /** @var Type<array{method: string, params?: array{...<string, mixed>}, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('JSONRPCNotification');
        return $type;
    }


    /** @return Type<array{method: string, params?: array{...<string, mixed>}, id: string|int|float, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function jSONRPCRequest(): Type
    {
        /** @var Type<array{method: string, params?: array{...<string, mixed>}, id: string|int|float, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('JSONRPCRequest');
        return $type;
    }


    /** @return Type<array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}|array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}, array{id: string|int|float, jsonrpc: '2.0', result: Record<array<string, mixed>, array<string, mixed>>}|array{error: Record<array<string, mixed>, array<string, mixed>>, id?: string|int|float, jsonrpc: '2.0'}> */
    public function jSONRPCResponse(): Type
    {
        /** @var Type<array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}|array{error: array<string, mixed>, id?: string|int|float, jsonrpc: '2.0'}, array{id: string|int|float, jsonrpc: '2.0', result: Record<array<string, mixed>, array<string, mixed>>}|array{error: Record<array<string, mixed>, array<string, mixed>>, id?: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('JSONRPCResponse');
        return $type;
    }


    /** @return Type<array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}, array{id: string|int|float, jsonrpc: '2.0', result: Record<array<string, mixed>, array<string, mixed>>}> */
    public function jSONRPCResultResponse(): Type
    {
        /** @var Type<array{id: string|int|float, jsonrpc: '2.0', result: array<string, mixed>}, array{id: string|int|float, jsonrpc: '2.0', result: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('JSONRPCResultResponse');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}> */
    public function legacyTitledEnumSchema(): Type
    {
        /** @var Type<array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}> $type */
        $type = $this->type('LegacyTitledEnumSchema');
        return $type;
    }


    /** @return Type<array{method: 'prompts/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'prompts/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function listPromptsRequest(): Type
    {
        /** @var Type<array{method: 'prompts/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'prompts/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ListPromptsRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, prompts: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, prompts: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function listPromptsResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, prompts: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, prompts: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ListPromptsResult');
        return $type;
    }


    /** @return Type<array{method: 'resources/templates/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/templates/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function listResourceTemplatesRequest(): Type
    {
        /** @var Type<array{method: 'resources/templates/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/templates/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ListResourceTemplatesRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resourceTemplates: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function listResourceTemplatesResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resourceTemplates: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ListResourceTemplatesResult');
        return $type;
    }


    /** @return Type<array{method: 'resources/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function listResourcesRequest(): Type
    {
        /** @var Type<array{method: 'resources/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ListResourcesRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, resources: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resources: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function listResourcesResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, resources: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resources: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ListResourcesResult');
        return $type;
    }


    /** @return Type<array{method: 'roots/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'roots/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function listRootsRequest(): Type
    {
        /** @var Type<array{method: 'roots/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'roots/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ListRootsRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, roots: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, roots: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function listRootsResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, roots: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, roots: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ListRootsResult');
        return $type;
    }


    /** @return Type<array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function listTasksRequest(): Type
    {
        /** @var Type<array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ListTasksRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function listTasksResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ListTasksResult');
        return $type;
    }


    /** @return Type<array{method: 'tools/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tools/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function listToolsRequest(): Type
    {
        /** @var Type<array{method: 'tools/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'tools/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ListToolsRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, tools: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tools: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function listToolsResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, tools: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tools: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ListToolsResult');
        return $type;
    }


    /** @return Type<array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/message', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function loggingMessageNotification(): Type
    {
        /** @var Type<array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/message', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('LoggingMessageNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}> */
    public function loggingMessageNotificationParams(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, data: mixed, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency', logger?: string}> $type */
        $type = $this->type('LoggingMessageNotificationParams');
        return $type;
    }


    /** @return Type<array{name?: string}, array{name?: string}> */
    public function modelHint(): Type
    {
        /** @var Type<array{name?: string}, array{name?: string}> $type */
        $type = $this->type('ModelHint');
        return $type;
    }


    /** @return Type<array{costPriority?: int|float, hints?: list<array<string, mixed>>, intelligencePriority?: int|float, speedPriority?: int|float}, array{costPriority?: int|float, hints?: list<Record<array<string, mixed>, array<string, mixed>>>, intelligencePriority?: int|float, speedPriority?: int|float}> */
    public function modelPreferences(): Type
    {
        /** @var Type<array{costPriority?: int|float, hints?: list<array<string, mixed>>, intelligencePriority?: int|float, speedPriority?: int|float}, array{costPriority?: int|float, hints?: list<Record<array<string, mixed>, array<string, mixed>>>, intelligencePriority?: int|float, speedPriority?: int|float}> $type */
        $type = $this->type('ModelPreferences');
        return $type;
    }


    /** @return Type<array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}, array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}> */
    public function multiSelectEnumSchema(): Type
    {
        /** @var Type<array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}, array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}> $type */
        $type = $this->type('MultiSelectEnumSchema');
        return $type;
    }


    /** @return Type<array{method: string, params?: array{...<string, mixed>}}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function notification(): Type
    {
        /** @var Type<array{method: string, params?: array{...<string, mixed>}}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('Notification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function notificationParams(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('NotificationParams');
        return $type;
    }


    /** @return Type<array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}, array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}> */
    public function numberSchema(): Type
    {
        /** @var Type<array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}, array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}> $type */
        $type = $this->type('NumberSchema');
        return $type;
    }


    /** @return Type<array{method: string, params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function paginatedRequest(): Type
    {
        /** @var Type<array{method: string, params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('PaginatedRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, cursor?: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, cursor?: string}> */
    public function paginatedRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, cursor?: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, cursor?: string}> $type */
        $type = $this->type('PaginatedRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, ...<string, mixed>}> */
    public function paginatedResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, nextCursor?: string, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, ...<string, mixed>}> $type */
        $type = $this->type('PaginatedResult');
        return $type;
    }


    /** @return Type<array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function pingRequest(): Type
    {
        /** @var Type<array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('PingRequest');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}> */
    public function primitiveSchemaDefinition(): Type
    {
        /** @var Type<array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}|array{default?: int|float, description?: string, maximum?: int|float, minimum?: int|float, title?: string, type: 'number'|'integer'}|array{default?: bool, description?: string, title?: string, type: 'boolean'}|array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}|array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}|array{default?: string, description?: string, enum: list<string>, enumNames?: list<string>, title?: string, type: 'string'}> $type */
        $type = $this->type('PrimitiveSchemaDefinition');
        return $type;
    }


    /** @return Type<array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function progressNotification(): Type
    {
        /** @var Type<array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ProgressNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}> */
    public function progressNotificationParams(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, message?: string, progress: int|float, progressToken: string|int|float, total?: int|float}> $type */
        $type = $this->type('ProgressNotificationParams');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, arguments?: list<array<string, mixed>>, description?: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, arguments?: list<Record<array<string, mixed>, array<string, mixed>>>, description?: string}> */
    public function prompt(): Type
    {
        /** @var Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, arguments?: list<array<string, mixed>>, description?: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, arguments?: list<Record<array<string, mixed>, array<string, mixed>>>, description?: string}> $type */
        $type = $this->type('Prompt');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, description?: string, required?: bool}, array{name: string, title?: string, description?: string, required?: bool}> */
    public function promptArgument(): Type
    {
        /** @var Type<array{name: string, title?: string, description?: string, required?: bool}, array{name: string, title?: string, description?: string, required?: bool}> $type */
        $type = $this->type('PromptArgument');
        return $type;
    }


    /** @return Type<array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/prompts/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function promptListChangedNotification(): Type
    {
        /** @var Type<array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/prompts/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('PromptListChangedNotification');
        return $type;
    }


    /** @return Type<array{content: array<string, mixed>, role: 'user'|'assistant'}, array{content: Record<array<string, mixed>, array<string, mixed>>, role: 'user'|'assistant'}> */
    public function promptMessage(): Type
    {
        /** @var Type<array{content: array<string, mixed>, role: 'user'|'assistant'}, array{content: Record<array<string, mixed>, array<string, mixed>>, role: 'user'|'assistant'}> $type */
        $type = $this->type('PromptMessage');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, type: 'ref/prompt'}, array{name: string, title?: string, type: 'ref/prompt'}> */
    public function promptReference(): Type
    {
        /** @var Type<array{name: string, title?: string, type: 'ref/prompt'}, array{name: string, title?: string, type: 'ref/prompt'}> $type */
        $type = $this->type('PromptReference');
        return $type;
    }


    /** @return Type<array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/read', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function readResourceRequest(): Type
    {
        /** @var Type<array{method: 'resources/read', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/read', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ReadResourceRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> */
    public function readResourceRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> $type */
        $type = $this->type('ReadResourceRequestParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, contents: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, contents: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function readResourceResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, contents: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, contents: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ReadResourceResult');
        return $type;
    }


    /** @return Type<array{taskId: string}, array{taskId: string}> */
    public function relatedTaskMetadata(): Type
    {
        /** @var Type<array{taskId: string}, array{taskId: string}> $type */
        $type = $this->type('RelatedTaskMetadata');
        return $type;
    }


    /** @return Type<array{method: string, params?: array{...<string, mixed>}}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function request(): Type
    {
        /** @var Type<array{method: string, params?: array{...<string, mixed>}}, array{method: string, params?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('Request');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function requestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('RequestParams');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, size?: int|float, uri: string}> */
    public function resource(): Type
    {
        /** @var Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, size?: int|float, uri: string}> $type */
        $type = $this->type('Resource');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string}> */
    public function resourceContents(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string}> $type */
        $type = $this->type('ResourceContents');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}> */
    public function resourceLink(): Type
    {
        /** @var Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, size?: int|float, uri: string, type: 'resource_link'}> $type */
        $type = $this->type('ResourceLink');
        return $type;
    }


    /** @return Type<array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/resources/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function resourceListChangedNotification(): Type
    {
        /** @var Type<array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/resources/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ResourceListChangedNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> */
    public function resourceRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> $type */
        $type = $this->type('ResourceRequestParams');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, uriTemplate: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, uriTemplate: string}> */
    public function resourceTemplate(): Type
    {
        /** @var Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, mimeType?: string, uriTemplate: string}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, mimeType?: string, uriTemplate: string}> $type */
        $type = $this->type('ResourceTemplate');
        return $type;
    }


    /** @return Type<array{type: 'ref/resource', uri: string}, array{type: 'ref/resource', uri: string}> */
    public function resourceTemplateReference(): Type
    {
        /** @var Type<array{type: 'ref/resource', uri: string}, array{type: 'ref/resource', uri: string}> $type */
        $type = $this->type('ResourceTemplateReference');
        return $type;
    }


    /** @return Type<array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/resources/updated', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function resourceUpdatedNotification(): Type
    {
        /** @var Type<array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/resources/updated', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ResourceUpdatedNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> */
    public function resourceUpdatedNotificationParams(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> $type */
        $type = $this->type('ResourceUpdatedNotificationParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> */
    public function result(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}> $type */
        $type = $this->type('Result');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, name?: string, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, name?: string, uri: string}> */
    public function root(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, name?: string, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, name?: string, uri: string}> $type */
        $type = $this->type('Root');
        return $type;
    }


    /** @return Type<array{method: 'notifications/roots/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/roots/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function rootsListChangedNotification(): Type
    {
        /** @var Type<array{method: 'notifications/roots/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/roots/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('RootsListChangedNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<array<string, mixed>, array<string, mixed>>|list<Record<array<string, mixed>, array<string, mixed>>>, role: 'user'|'assistant'}> */
    public function samplingMessage(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, content: array<string, mixed>|list<array<string, mixed>>, role: 'user'|'assistant'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: Record<array<string, mixed>, array<string, mixed>>|list<Record<array<string, mixed>, array<string, mixed>>>, role: 'user'|'assistant'}> $type */
        $type = $this->type('SamplingMessage');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{_meta?: array{...<string, mixed>}, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}|array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, toolUseId: string, type: 'tool_result'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, text: string, type: 'text'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'audio'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, toolUseId: string, type: 'tool_result'}> */
    public function samplingMessageContentBlock(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'image'}|array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, data: string, mimeType: string, type: 'audio'}|array{_meta?: array{...<string, mixed>}, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}|array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, toolUseId: string, type: 'tool_result'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, text: string, type: 'text'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'image'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, data: string, mimeType: string, type: 'audio'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, toolUseId: string, type: 'tool_result'}> $type */
        $type = $this->type('SamplingMessageContentBlock');
        return $type;
    }


    /** @return Type<array{completions?: array<string, mixed>, experimental?: array{...<string, array<string, mixed>>}, logging?: array<string, mixed>, prompts?: array{listChanged?: bool}, resources?: array{listChanged?: bool, subscribe?: bool}, tasks?: array{cancel?: array<string, mixed>, list?: array<string, mixed>, requests?: array{tools?: array{call?: array<string, mixed>}}}, tools?: array{listChanged?: bool}}, array{completions?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, logging?: Record<array<string, mixed>, array<string, mixed>>, prompts?: Record<array<string, mixed>, array<string, mixed>>, resources?: Record<array<string, mixed>, array<string, mixed>>, tasks?: Record<array<string, mixed>, array<string, mixed>>, tools?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function serverCapabilities(): Type
    {
        /** @var Type<array{completions?: array<string, mixed>, experimental?: array{...<string, array<string, mixed>>}, logging?: array<string, mixed>, prompts?: array{listChanged?: bool}, resources?: array{listChanged?: bool, subscribe?: bool}, tasks?: array{cancel?: array<string, mixed>, list?: array<string, mixed>, requests?: array{tools?: array{call?: array<string, mixed>}}}, tools?: array{listChanged?: bool}}, array{completions?: Record<array<string, mixed>, array<string, mixed>>, experimental?: Record<array<string, mixed>, array<string, mixed>>, logging?: Record<array<string, mixed>, array<string, mixed>>, prompts?: Record<array<string, mixed>, array<string, mixed>>, resources?: Record<array<string, mixed>, array<string, mixed>>, tasks?: Record<array<string, mixed>, array<string, mixed>>, tools?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('ServerCapabilities');
        return $type;
    }


    /** @return Type<array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/elicitation/complete', params: array{elicitationId: string}, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/elicitation/complete', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function serverNotification(): Type
    {
        /** @var Type<array{method: 'notifications/cancelled', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}|array{method: 'notifications/elicitation/complete', params: array{elicitationId: string}, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/cancelled', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/progress', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/message', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/resources/updated', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/resources/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tools/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/prompts/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/elicitation/complete', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}|array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ServerNotification');
        return $type;
    }


    /** @return Type<array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'sampling/createMessage', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'roots/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'elicitation/create', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'sampling/createMessage', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'roots/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'elicitation/create', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function serverRequest(): Type
    {
        /** @var Type<array{method: 'ping', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'sampling/createMessage', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'roots/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'elicitation/create', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: array{taskId: string}, id: string|int|float, jsonrpc: '2.0'}, array{method: 'ping', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'sampling/createMessage', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'roots/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'elicitation/create', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/get', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/result', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/list', params?: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}|array{method: 'tasks/cancel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('ServerRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, capabilities: array<string, mixed>, instructions?: string, protocolVersion: string, serverInfo: array<string, mixed>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, prompts: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, resources: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, contents: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tools: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, instructions?: string, protocolVersion: string, serverInfo: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, description?: string, messages: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, prompts: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resourceTemplates: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resources: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, contents: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tools: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> */
    public function serverResult(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, capabilities: array<string, mixed>, instructions?: string, protocolVersion: string, serverInfo: array<string, mixed>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, completion: array{hasMore?: bool, total?: int|float, values: list<string>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, description?: string, messages: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, prompts: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, resourceTemplates: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, resources: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, contents: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tools: list<array<string, mixed>>, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: array{...<string, mixed>}, nextCursor?: string, tasks: list<array<string, mixed>>, ...<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, capabilities: Record<array<string, mixed>, array<string, mixed>>, instructions?: string, protocolVersion: string, serverInfo: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, completion: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, description?: string, messages: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, prompts: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resourceTemplates: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, resources: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, contents: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tools: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null, ...<string, mixed>}|array{_meta?: Record<array<string, mixed>, array<string, mixed>>, nextCursor?: string, tasks: list<Record<array<string, mixed>, array<string, mixed>>>, ...<string, mixed>}> $type */
        $type = $this->type('ServerResult');
        return $type;
    }


    /** @return Type<array{method: 'logging/setLevel', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'logging/setLevel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function setLevelRequest(): Type
    {
        /** @var Type<array{method: 'logging/setLevel', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'logging/setLevel', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('SetLevelRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'}> */
    public function setLevelRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, level: 'debug'|'info'|'notice'|'warning'|'error'|'critical'|'alert'|'emergency'}> $type */
        $type = $this->type('SetLevelRequestParams');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}> */
    public function singleSelectEnumSchema(): Type
    {
        /** @var Type<array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}|array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}> $type */
        $type = $this->type('SingleSelectEnumSchema');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}, array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}> */
    public function stringSchema(): Type
    {
        /** @var Type<array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}, array{default?: string, description?: string, format?: 'email'|'uri'|'date'|'date-time', maxLength?: int|float, minLength?: int|float, title?: string, type: 'string'}> $type */
        $type = $this->type('StringSchema');
        return $type;
    }


    /** @return Type<array{method: 'resources/subscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/subscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function subscribeRequest(): Type
    {
        /** @var Type<array{method: 'resources/subscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/subscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('SubscribeRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> */
    public function subscribeRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> $type */
        $type = $this->type('SubscribeRequestParams');
        return $type;
    }


    /** @return Type<array{createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}, array{createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}> */
    public function task(): Type
    {
        /** @var Type<array{createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}, array{createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}> $type */
        $type = $this->type('Task');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function taskAugmentedRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, task?: array<string, mixed>}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, task?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('TaskAugmentedRequestParams');
        return $type;
    }


    /** @return Type<array{ttl?: int|float}, array{ttl?: int|float}> */
    public function taskMetadata(): Type
    {
        /** @var Type<array{ttl?: int|float}, array{ttl?: int|float}> $type */
        $type = $this->type('TaskMetadata');
        return $type;
    }


    /** @return Type<array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function taskStatusNotification(): Type
    {
        /** @var Type<array{method: 'notifications/tasks/status', params: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/tasks/status', params: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('TaskStatusNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}> */
    public function taskStatusNotificationParams(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, createdAt: string, lastUpdatedAt: string, pollInterval?: int|float, status: 'working'|'input_required'|'completed'|'failed'|'cancelled', statusMessage?: string, taskId: string, ttl: int|float|null}> $type */
        $type = $this->type('TaskStatusNotificationParams');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, text: string, type: 'text'}> */
    public function textContent(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, text: string, type: 'text'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, text: string, type: 'text'}> $type */
        $type = $this->type('TextContent');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string, text: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string, text: string}> */
    public function textResourceContents(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, mimeType?: string, uri: string, text: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, mimeType?: string, uri: string, text: string}> $type */
        $type = $this->type('TextResourceContents');
        return $type;
    }


    /** @return Type<array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}, array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}> */
    public function titledMultiSelectEnumSchema(): Type
    {
        /** @var Type<array{default?: list<string>, description?: string, items: array{anyOf: list<array{const: string, title: string}>}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}, array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}> $type */
        $type = $this->type('TitledMultiSelectEnumSchema');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}, array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}> */
    public function titledSingleSelectEnumSchema(): Type
    {
        /** @var Type<array{default?: string, description?: string, oneOf: list<array{const: string, title: string}>, title?: string, type: 'string'}, array{default?: string, description?: string, oneOf: list<Record<array<string, mixed>, array<string, mixed>>>, title?: string, type: 'string'}> $type */
        $type = $this->type('TitledSingleSelectEnumSchema');
        return $type;
    }


    /** @return Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, execution?: array<string, mixed>, inputSchema: array{'$schema'?: string, properties?: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}, outputSchema?: array{'$schema'?: string, properties?: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, execution?: Record<array<string, mixed>, array<string, mixed>>, inputSchema: Record<array<string, mixed>, array<string, mixed>>, outputSchema?: Record<array<string, mixed>, array<string, mixed>>}> */
    public function tool(): Type
    {
        /** @var Type<array{name: string, title?: string, icons?: list<array<string, mixed>>, _meta?: array{...<string, mixed>}, annotations?: array<string, mixed>, description?: string, execution?: array<string, mixed>, inputSchema: array{'$schema'?: string, properties?: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}, outputSchema?: array{'$schema'?: string, properties?: array{...<string, array<string, mixed>>}, required?: list<string>, type: 'object'}}, array{name: string, title?: string, icons?: list<Record<array<string, mixed>, array<string, mixed>>>, _meta?: Record<array<string, mixed>, array<string, mixed>>, annotations?: Record<array<string, mixed>, array<string, mixed>>, description?: string, execution?: Record<array<string, mixed>, array<string, mixed>>, inputSchema: Record<array<string, mixed>, array<string, mixed>>, outputSchema?: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('Tool');
        return $type;
    }


    /** @return Type<array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}, array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}> */
    public function toolAnnotations(): Type
    {
        /** @var Type<array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}, array{destructiveHint?: bool, idempotentHint?: bool, openWorldHint?: bool, readOnlyHint?: bool, title?: string}> $type */
        $type = $this->type('ToolAnnotations');
        return $type;
    }


    /** @return Type<array{mode?: 'auto'|'required'|'none'}, array{mode?: 'auto'|'required'|'none'}> */
    public function toolChoice(): Type
    {
        /** @var Type<array{mode?: 'auto'|'required'|'none'}, array{mode?: 'auto'|'required'|'none'}> $type */
        $type = $this->type('ToolChoice');
        return $type;
    }


    /** @return Type<array{taskSupport?: 'forbidden'|'optional'|'required'}, array{taskSupport?: 'forbidden'|'optional'|'required'}> */
    public function toolExecution(): Type
    {
        /** @var Type<array{taskSupport?: 'forbidden'|'optional'|'required'}, array{taskSupport?: 'forbidden'|'optional'|'required'}> $type */
        $type = $this->type('ToolExecution');
        return $type;
    }


    /** @return Type<array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/tools/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> */
    public function toolListChangedNotification(): Type
    {
        /** @var Type<array{method: 'notifications/tools/list_changed', params?: array<string, mixed>, jsonrpc: '2.0'}, array{method: 'notifications/tools/list_changed', params?: Record<array<string, mixed>, array<string, mixed>>, jsonrpc: '2.0'}> $type */
        $type = $this->type('ToolListChangedNotification');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, toolUseId: string, type: 'tool_result'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, toolUseId: string, type: 'tool_result'}> */
    public function toolResultContent(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, content: list<array<string, mixed>>, isError?: bool, structuredContent?: array{...<string, mixed>}, toolUseId: string, type: 'tool_result'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, content: list<Record<array<string, mixed>, array<string, mixed>>>, isError?: bool, structuredContent?: Record<array<string, mixed>, array<string, mixed>>, toolUseId: string, type: 'tool_result'}> $type */
        $type = $this->type('ToolResultContent');
        return $type;
    }


    /** @return Type<array{_meta?: array{...<string, mixed>}, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}> */
    public function toolUseContent(): Type
    {
        /** @var Type<array{_meta?: array{...<string, mixed>}, id: string, input: array{...<string, mixed>}, name: string, type: 'tool_use'}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, id: string, input: Record<array<string, mixed>, array<string, mixed>>, name: string, type: 'tool_use'}> $type */
        $type = $this->type('ToolUseContent');
        return $type;
    }


    /** @return Type<array{id?: string|int|float, jsonrpc: '2.0', error: array{code: -32042, data: array{elicitations: list<array<string, mixed>>, ...<string, mixed>}, message: string}}, array{id?: string|int|float, jsonrpc: '2.0', error: Record<array<string, mixed>, array<string, mixed>>}> */
    public function uRLElicitationRequiredError(): Type
    {
        /** @var Type<array{id?: string|int|float, jsonrpc: '2.0', error: array{code: -32042, data: array{elicitations: list<array<string, mixed>>, ...<string, mixed>}, message: string}}, array{id?: string|int|float, jsonrpc: '2.0', error: Record<array<string, mixed>, array<string, mixed>>}> $type */
        $type = $this->type('URLElicitationRequiredError');
        return $type;
    }


    /** @return Type<array{method: 'resources/unsubscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/unsubscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> */
    public function unsubscribeRequest(): Type
    {
        /** @var Type<array{method: 'resources/unsubscribe', params: array<string, mixed>, id: string|int|float, jsonrpc: '2.0'}, array{method: 'resources/unsubscribe', params: Record<array<string, mixed>, array<string, mixed>>, id: string|int|float, jsonrpc: '2.0'}> $type */
        $type = $this->type('UnsubscribeRequest');
        return $type;
    }


    /** @return Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> */
    public function unsubscribeRequestParams(): Type
    {
        /** @var Type<array{_meta?: array{progressToken?: string|int|float, ...<string, mixed>}, uri: string}, array{_meta?: Record<array<string, mixed>, array<string, mixed>>, uri: string}> $type */
        $type = $this->type('UnsubscribeRequestParams');
        return $type;
    }


    /** @return Type<array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}, array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}> */
    public function untitledMultiSelectEnumSchema(): Type
    {
        /** @var Type<array{default?: list<string>, description?: string, items: array{enum: list<string>, type: 'string'}, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}, array{default?: list<string>, description?: string, items: Record<array<string, mixed>, array<string, mixed>>, maxItems?: int|float, minItems?: int|float, title?: string, type: 'array'}> $type */
        $type = $this->type('UntitledMultiSelectEnumSchema');
        return $type;
    }


    /** @return Type<array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}> */
    public function untitledSingleSelectEnumSchema(): Type
    {
        /** @var Type<array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}, array{default?: string, description?: string, enum: list<string>, title?: string, type: 'string'}> $type */
        $type = $this->type('UntitledSingleSelectEnumSchema');
        return $type;
    }

}

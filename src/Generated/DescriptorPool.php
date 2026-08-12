<?php

declare(strict_types=1);

namespace WP\McpSchema\Generated;

/** Generated content-addressed descriptor data. Do not edit manually. */
final class DescriptorPool
{
    /** @var array<string, array<string, mixed>>|null */
    private static ?array $descriptorCache = null;

    /** @var array<string, array{fingerprint: string, roots: array<int, string>, types: array<string, string>}>|null */
    private static ?array $manifestCache = null;

    /** @return array<string, array<string, mixed>> */
    public static function descriptors(): array
    {
        if (self::$descriptorCache === null) {
            self::$descriptorCache = [
                '00eebe9a38c6d244c4026d8b931025858099a8e49b5300247b4025e41722f03b' => [
                    'kind' => 'record',
                    'fields' => [
                        'error' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'intersection',
                                'allOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'Error',
                                    ],
                                    [
                                        'kind' => 'record',
                                        'fields' => [
                                            'code' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'literal',
                                                    'value' => -32042,
                                                ],
                                            ],
                                            'data' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'record',
                                                    'fields' => [
                                                        'elicitations' => [
                                                            'required' => true,
                                                            'type' => [
                                                                'kind' => 'list',
                                                                'items' => [
                                                                    'kind' => 'ref',
                                                                    'name' => 'ElicitRequestURLParams',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                    'parents' => [],
                                                    'additional' => [
                                                        'kind' => 'any',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'parents' => [],
                                        'additional' => false,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'omit',
                            'from' => [
                                'kind' => 'ref',
                                'name' => 'JSONRPCErrorResponse',
                            ],
                            'keys' => [
                                'error',
                            ],
                        ],
                    ],
                    'additional' => false,
                ],
                '026e1d328f378957cdd6754d14f255205ba41d716f13175af75703edf3e7914c' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ContentBlock',
                                ],
                            ],
                        ],
                        'isError' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'structuredContent' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'any',
                            ],
                        ],
                        'toolUseId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tool_result',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '02a785e52c4ded35f38d7e8022ce631717679e387d93c3bfd380ef993e010719' => [
                    'kind' => 'record',
                    'fields' => [
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '02c2ea676c3cdec865ce281934522c7c3c5b622ac6eb61f2cc1006b0f111f35e' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'resource' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'TextResourceContents',
                                    ],
                                    [
                                        'kind' => 'ref',
                                        'name' => 'BlobResourceContents',
                                    ],
                                ],
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resource',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '0336d95611a4ad09b0162dde45e3c0ae1f26ea2342481c3175ec74d40571d16d' => [
                    'kind' => 'record',
                    'fields' => [
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'PaginatedRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '038a1cabbaa71a2048730dba03065c24b55590d50ba8c54e773f79a5baa267bc' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ContentBlock',
                                ],
                            ],
                        ],
                        'isError' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'structuredContent' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'toolUseId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tool_result',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '038f22010a85461e704b31831d9029915c2a4213972522f9ec370e23e5d1dca9' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscriptionsListenResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '039748ac3152d7e2f4ded61c630ad55b830ee7144f9adb2189fa7bc34a02840c' => [
                    'kind' => 'record',
                    'fields' => [
                        'taskSupport' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'forbidden',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'optional',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'required',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '03fe9291179adc130ada0c76c50c383d64704c155296df3b5fe137f65b3dd2f3' => [
                    'kind' => 'record',
                    'fields' => [
                        'error' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'intersection',
                                'allOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'Error',
                                    ],
                                    [
                                        'kind' => 'record',
                                        'fields' => [
                                            'code' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'literal',
                                                    'value' => -32022,
                                                ],
                                            ],
                                            'data' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'record',
                                                    'fields' => [
                                                        'requested' => [
                                                            'required' => true,
                                                            'type' => [
                                                                'kind' => 'string',
                                                            ],
                                                        ],
                                                        'supported' => [
                                                            'required' => true,
                                                            'type' => [
                                                                'kind' => 'list',
                                                                'items' => [
                                                                    'kind' => 'string',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                    'parents' => [],
                                                    'additional' => false,
                                                ],
                                            ],
                                        ],
                                        'parents' => [],
                                        'additional' => false,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'omit',
                            'from' => [
                                'kind' => 'ref',
                                'name' => 'JSONRPCErrorResponse',
                            ],
                            'keys' => [
                                'error',
                            ],
                        ],
                    ],
                    'additional' => false,
                ],
                '0435f730fdd966d579c3b5bf97eea24bdcef49313893ca34bf895992a33faee5' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'initialize',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'InitializeRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '062495eaed59502f95ae2507d2923cb037203a6721037fdfd6aeeb1a0ac5e273' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CancelledNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ProgressNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'InitializedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'RootsListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'TaskStatusNotification',
                        ],
                    ],
                ],
                '0682e30e407afdccee01ae9154b819dc9b582beaf93d89d2ac33dcd5f09e5a81' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tools/call',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'CallToolRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '06b681c280f6d5d4e15c2af16e038a70ebf8cab7fa9bae19531b3125c7e23600' => [
                    'kind' => 'record',
                    'fields' => [
                        'audience' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Role',
                                ],
                            ],
                        ],
                        'lastModified' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'priority' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '08229e5261a03f670d31cbf25c192592ffbace3ae0f5218d5e9afcecb608e9bf' => [
                    'kind' => 'record',
                    'fields' => [
                        'error' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Error',
                            ],
                        ],
                        'id' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                        'jsonrpc' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => '2.0',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '095c7450c0e1763364b1ddc01fb775fe32d05b2a483ac58cd925125b9c8fcd3a' => [
                    'kind' => 'record',
                    'fields' => [
                        'capabilities' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ServerCapabilities',
                            ],
                        ],
                        'instructions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'supportedVersions' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CacheableResult',
                        ],
                    ],
                    'additional' => false,
                ],
                '09e61292f1fe2be0cd8ae007fc1a21c9b573d71f5ceb5d1c86c206b7ddee4b9e' => [
                    'kind' => 'record',
                    'fields' => [
                        'promptsListChanged' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'resourcesListChanged' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'resourceSubscriptions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'toolsListChanged' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '0a7cf6d688dd548cbbdd452d63523ce7a91ea0765a328563724e1db2531cb385' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'literal',
                            'value' => 'complete',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'input_required',
                        ],
                        [
                            'kind' => 'string',
                        ],
                    ],
                ],
                '0ba77fb802ae20b0d5bf227a0ee2d77ddb82a54b56995a23c4000586feae61e3' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'SamplingMessageContentBlock',
                                    ],
                                    [
                                        'kind' => 'list',
                                        'items' => [
                                            'kind' => 'ref',
                                            'name' => 'SamplingMessageContentBlock',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'role' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Role',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '0d5e123a9ad82310d9a40102f3f02f307eda51cd70669a8b8eacb2a74490148f' => [
                    'kind' => 'record',
                    'fields' => [
                        'roots' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Root',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '0ddf3de2710bc15f516a76eb2674d3d0f9d829ad9d8e7f0cb6825da0e0399522' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'UntitledSingleSelectEnumSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'TitledSingleSelectEnumSchema',
                        ],
                    ],
                ],
                '0e73f320c9cdb2d2807681af31d2909f902d1b613892686932ac0b1c0fc01d7f' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'prompts/list',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '0ecf1f75aeb465760305167b4cb0ec3b88bfceae18fe8b1f715053514b66191c' => [
                    'kind' => 'record',
                    'fields' => [
                        'message' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'progress' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'progressToken' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ProgressToken',
                            ],
                        ],
                        'total' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '0f4af5fa59be04f97e378df8664202b088853d17b2e732592048419c85f452e9' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'ping',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '1198169004537cee13a1c72a64290ece1bbe93f71fe621b9bb02da0d1f46ec79' => [
                    'kind' => 'record',
                    'fields' => [
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '12d9c00d75ac7bc1aedd449ba900507349d62867be6efdfc891e481132e80811' => [
                    'kind' => 'record',
                    'fields' => [
                        'code' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => -32601,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Error',
                        ],
                    ],
                    'additional' => false,
                ],
                '1382f8fac6f98093f262114aa7c940cf1d7fcadd60a121868a85d7a4b46a9fde' => [
                    'kind' => 'record',
                    'fields' => [
                        'roots' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Root',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '144965b224ec7329f8dc380d660c2e23271180e766c4e71d177e5755a332cea3' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CreateMessageRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListRootsRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitRequest',
                        ],
                    ],
                ],
                '1467e2d3876a36a1aee3f53590a928100c614edb7c8c5ecfe12a5c68b414fff7' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'text' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'text',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '157f385956ed26a38437f61731daeeb8044c13df7fedb553f2eeb4f6a5a297f0' => [
                    'kind' => 'record',
                    'fields' => [
                        'code' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => -32602,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Error',
                        ],
                    ],
                    'additional' => false,
                ],
                '173a507389b4062fb5e8bcd8849f556eee20b5ed611fc30b261f39eb6d6163bc' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tasks/cancel',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'taskId' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '184774a364cde4a4fd595b7d60d55047cc50af153525e1f9b8e67a1b600a70ce' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'uriTemplate' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '1bf4ca6ddc402d7a5722b1dff1667472b4ec7e8e99ac1526b4584eb99b157f1c' => [
                    'kind' => 'record',
                    'fields' => [
                        'message' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mode' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'form',
                            ],
                        ],
                        'requestedSchema' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '$schema' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                    'properties' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [],
                                            'parents' => [],
                                            'additional' => [
                                                'kind' => 'ref',
                                                'name' => 'PrimitiveSchemaDefinition',
                                            ],
                                        ],
                                    ],
                                    'required' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                    'type' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'literal',
                                            'value' => 'object',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'TaskAugmentedRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '1d4a1ba22f169f0c315499627a4de83e40812b51053e9cee23d268fd1220cfc4' => [
                    'kind' => 'record',
                    'fields' => [
                        'inputRequests' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'InputRequests',
                            ],
                        ],
                        'requestState' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '1e72e8f425f41d63fcf7dca6e27141b937a59be86cf8c4fc5c9359dac2e9e271' => [
                    'kind' => 'record',
                    'fields' => [
                        'jsonrpc' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => '2.0',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Notification',
                        ],
                    ],
                    'additional' => false,
                ],
                '20b5ed5b30889e268d3919da448eab52f7ee0e29650744326c5892a948ec7055' => [
                    'kind' => 'record',
                    'fields' => [
                        'arguments' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'InputResponseRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '22010e063c633e68bcd6d5295ce1d17abff40ea946cc60d449196dd3efe70cc3' => [
                    'kind' => 'record',
                    'fields' => [
                        'action' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'accept',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'decline',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'cancel',
                                    ],
                                ],
                            ],
                        ],
                        'content' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'union',
                                    'anyOf' => [
                                        [
                                            'kind' => 'string',
                                        ],
                                        [
                                            'kind' => 'number',
                                        ],
                                        [
                                            'kind' => 'boolean',
                                        ],
                                        [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '226dcd5648135361b96230cb96e84345b6ab9f55c446ef76dd714d129f195a11' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ListToolsResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '23040a3ad89efb6f6ed4253cd298aa26c5e7c2642471f6fcd2ee1f4ca06fc1ae' => [
                    'kind' => 'record',
                    'fields' => [
                        'arguments' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'InputResponseRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '23c09b678529cff02e622d5c13cfb881beff2a52629125b73eb099fef082e1de' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'CompleteResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '245e460e4769be5504d54b789c0ad3b13351bb339b3a320e4740b9d94a7ed431' => [
                    'kind' => 'record',
                    'fields' => [],
                    'parents' => [],
                    'additional' => [
                        'kind' => 'ref',
                        'name' => 'InputRequest',
                    ],
                ],
                '24c2b2e05f05235de1f6ac6aab05233cac676bd79b5355e5ecab054e6eb67656' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'arguments' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'PromptArgument',
                                ],
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '24dd5d2401798a0fcc27b3a39729aecb935852a5bd1bf91133a13bdeaf9de4c0' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'size' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '25120ae1290f91b6713038686b7f1495ac0267d1e0dd1ea222814e9acea5d759' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PingRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'InitializeRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CompleteRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'SetLevelRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetPromptRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListPromptsRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourcesRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourceTemplatesRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ReadResourceRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'SubscribeRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'UnsubscribeRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CallToolRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListToolsRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskPayloadRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListTasksRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CancelTaskRequest',
                        ],
                    ],
                ],
                '25241836c35f79aa2215f3e07dfe1f2b8318a09a5c6c9e86b8fe3af31ab7fc5e' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'literal',
                            'value' => 'debug',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'info',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'notice',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'warning',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'error',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'critical',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'alert',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'emergency',
                        ],
                    ],
                ],
                '2785806074e1ca3f943c78507f902cc7415c03530765dfee4ac25a6277a69456' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'SamplingMessageContentBlock',
                                    ],
                                    [
                                        'kind' => 'list',
                                        'items' => [
                                            'kind' => 'ref',
                                            'name' => 'SamplingMessageContentBlock',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'role' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Role',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '295e00f59cbb648ff699658ea2564e7e67ebc078878e558bfa15a0cba075904b' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/elicitation/complete',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'elicitationId' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                '29e785376eaf520767b24793a1a72ae86d8a0bc84955bd0eb97f7f315c5fc1fe' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'EmptyResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CreateMessageResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListRootsResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskPayloadResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListTasksResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CancelTaskResult',
                        ],
                    ],
                ],
                '2c53786b4677151108cecaef442de4ede73143c767ab82ada7e5dc3e3ac5a29d' => [
                    'kind' => 'record',
                    'fields' => [
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '2dae2d6778bc00bab2d0ca6c6474d8d199b9ffea2a78d0f6f8de16d090cb1963' => [
                    'kind' => 'record',
                    'fields' => [
                        'inputResponses' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'InputResponses',
                            ],
                        ],
                        'requestState' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '301c17a7e94ede4d119114aaffa2201a951ad78f9a65a7bb7bd216fef56848d4' => [
                    'kind' => 'record',
                    'fields' => [
                        'action' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'accept',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'decline',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'cancel',
                                    ],
                                ],
                            ],
                        ],
                        'content' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'union',
                                    'anyOf' => [
                                        [
                                            'kind' => 'string',
                                        ],
                                        [
                                            'kind' => 'number',
                                        ],
                                        [
                                            'kind' => 'boolean',
                                        ],
                                        [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '305bd3074a7d818adffcfc57516791fc8b477fea64910bc02578cb2a65df7ead' => [
                    'kind' => 'list',
                    'items' => [
                        'kind' => 'ref',
                        'name' => 'JSONValue',
                    ],
                ],
                '30bc1535145a88fe06e5e3b636f7e756ae269df6208dd8351318733bbb40e97f' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'completion/complete',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'CompleteRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '30ce0f971a9e9ccd492196072b348fe07b7ab83d00e77584b05d7e38a695a0cb' => [
                    'kind' => 'record',
                    'fields' => [
                        'completions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'map',
                                'values' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'experimental' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'map',
                                    'values' => [
                                        'kind' => 'any',
                                    ],
                                ],
                            ],
                        ],
                        'logging' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'map',
                                'values' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'prompts' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'resources' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                    'subscribe' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'tasks' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'cancel' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                    'list' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                    'requests' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [
                                                'tools' => [
                                                    'required' => false,
                                                    'type' => [
                                                        'kind' => 'record',
                                                        'fields' => [
                                                            'call' => [
                                                                'required' => false,
                                                                'type' => [
                                                                    'kind' => 'map',
                                                                    'values' => [
                                                                        'kind' => 'any',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'parents' => [],
                                                        'additional' => false,
                                                    ],
                                                ],
                                            ],
                                            'parents' => [],
                                            'additional' => false,
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'tools' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '3170b223f1084c6e9ed6041e54ec12e011631db497b6ad65640d279435d0bdd3' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ListResourcesResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '32616a0089cb87a3f0a98b951fea12b83b593ab6966d3527e10e32ecf0ef9474' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'EmptyResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'DiscoverResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CompleteResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetPromptResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListPromptsResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourceTemplatesResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourcesResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ReadResourceResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'SubscriptionsListenResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CallToolResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListToolsResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'InputRequiredResult',
                        ],
                    ],
                ],
                '34e00c9f48330725a119d354a3e0eab63b67fa2f642a96bafd89880bd929db29' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'data' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'image',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '35a3d3d66e0154666946d406382b171ed4ac967078d9c10ee3e98a5d39e8efb6' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'GetPromptResult',
                                    ],
                                    [
                                        'kind' => 'ref',
                                        'name' => 'InputRequiredResult',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '360ace9e4a4a109ff81b2b12727502c85cd68b2ba60f1efc3afccf92f6c335ad' => [
                    'kind' => 'record',
                    'fields' => [
                        'name' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '361c7a3ef14691b83d8c228b311eccf6a2c10e1f66627252d9f29023923f7005' => [
                    'kind' => 'record',
                    'fields' => [
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ContentBlock',
                            ],
                        ],
                        'role' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Role',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '37ad14d1634ad77cfb57f096836a3903aef498328ed15fe4fa4f163e61333448' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '3968ccd7d06c471868fc013c31d1090055064ac79ca77e44a13fc3c043ec6dde' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitRequestFormParams',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitRequestURLParams',
                        ],
                    ],
                ],
                '3b0087e87a687cb76aa36a4719dbca92a93843e7e6ef1b7c441747592b4f63fe' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resources/list',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '3b75b6cc8fa1c8abf3ac6e014397a5f4b5136d4d65ff5cf046337b2587a9cbcf' => [
                    'kind' => 'record',
                    'fields' => [
                        'icons' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Icon',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '3ba037cd7c67dbe690ac262101c26acd9e3b40e404536eac92e9e1ea0273856c' => [
                    'kind' => 'record',
                    'fields' => [
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ContentBlock',
                                ],
                            ],
                        ],
                        'isError' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'structuredContent' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'any',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '3f30e7283c0e3fd9063cbf310486b235c3b316d8dd92bfc7e9a1f7d8c7c8260c' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'text' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'text',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '401466ba2f1d38a56e98414806d23cc73de3177b4bfa401f614e018953c4c9cf' => [
                    'kind' => 'record',
                    'fields' => [],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '41a8d446a77f218768f2800682cbc4b9cfdfc2cb9a2ba8cccc126e659b1c2a61' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/prompts/list_changed',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'NotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                '41d31dd5fba7c58df47ec424cad112394670a22a29d28438040c9cba1bb5acbd' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'NotificationMetaObject',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '429254f94eb268c77e18f487bf1cdc17285a03a46fcd1f45cb11e8914c78d969' => [
                    'kind' => 'record',
                    'fields' => [
                        'cursor' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Cursor',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '4318f0dcbd6026298fdd4de241f25d8b6494f7cc03947d4efe63b34dfb8c2c8f' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '434fc25a0d1b570c19298479aa93958d32a3810f8dce3eef0439147884641cab' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ToolAnnotations',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'execution' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ToolExecution',
                            ],
                        ],
                        'inputSchema' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '$schema' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                    'properties' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [],
                                            'parents' => [],
                                            'additional' => [
                                                'kind' => 'map',
                                                'values' => [
                                                    'kind' => 'any',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'required' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                    'type' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'literal',
                                            'value' => 'object',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'outputSchema' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '$schema' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                    'properties' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [],
                                            'parents' => [],
                                            'additional' => [
                                                'kind' => 'map',
                                                'values' => [
                                                    'kind' => 'any',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'required' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                    'type' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'literal',
                                            'value' => 'object',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '4401359e729b31cebede1ac81e593d182794ffef82039c164da5777e0e37f2c3' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'sampling/createMessage',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'CreateMessageRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '45153f59f307c9cf31c05c364f254b6362c433944e73b99e8fc9cbf02fbb5623' => [
                    'kind' => 'record',
                    'fields' => [
                        'includeContext' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'none',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'thisServer',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'allServers',
                                    ],
                                ],
                            ],
                        ],
                        'maxTokens' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'messages' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'SamplingMessage',
                                ],
                            ],
                        ],
                        'metadata' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'JSONObject',
                            ],
                        ],
                        'modelPreferences' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ModelPreferences',
                            ],
                        ],
                        'stopSequences' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'systemPrompt' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'temperature' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'toolChoice' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ToolChoice',
                            ],
                        ],
                        'tools' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Tool',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '45b24d375421a6339075c1718183bc346f08b56aacf239b663b01439e1448254' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'StringSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'NumberSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'BooleanSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'EnumSchema',
                        ],
                    ],
                ],
                '499ac59976c772fa2945117a16e26e2f9eb30bbb2a7e6374e51b683936220f53' => [
                    'kind' => 'record',
                    'fields' => [
                        'destructiveHint' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'idempotentHint' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'openWorldHint' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'readOnlyHint' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '4c512a8998ac6aaa54ba845663f65a47e2b7aae2a2b9e13fbf9c0e9e08304135' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'prompts/get',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'GetPromptRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '50cfdd75a1f62d3d8290f34a5507afd41d9bcc3e6158bab469513d4892611ed5' => [
                    'kind' => 'record',
                    'fields' => [
                        'costPriority' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'hints' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ModelHint',
                                ],
                            ],
                        ],
                        'intelligencePriority' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'speedPriority' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '50f4b3e75734ebac318affc8bdf4a363dd383b19612ddf538fad8b8f96de5a59' => [
                    'kind' => 'record',
                    'fields' => [
                        'elicitationId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'message' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mode' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'url',
                            ],
                        ],
                        'url' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'TaskAugmentedRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '51abcabd577addabdee1910403da49b498cb92e73d9ba43222183f3748d08d9c' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'CallToolResult',
                                    ],
                                    [
                                        'kind' => 'ref',
                                        'name' => 'InputRequiredResult',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '5210c5cc12ec8098ad895319a731f8e0046b10beac53e0359d7505c48bdab114' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/tasks/status',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'TaskStatusNotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                '5213ffc1eab629ab0b86f14ec13d0cfa8cd68eda1de9dcc85a4d0f3f92cdfd86' => [
                    'kind' => 'record',
                    'fields' => [
                        'elicitation' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'form' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                    'url' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'experimental' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'map',
                                    'values' => [
                                        'kind' => 'any',
                                    ],
                                ],
                            ],
                        ],
                        'roots' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'sampling' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'context' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                    'tools' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'tasks' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'cancel' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                    'list' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'map',
                                            'values' => [
                                                'kind' => 'any',
                                            ],
                                        ],
                                    ],
                                    'requests' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [
                                                'elicitation' => [
                                                    'required' => false,
                                                    'type' => [
                                                        'kind' => 'record',
                                                        'fields' => [
                                                            'create' => [
                                                                'required' => false,
                                                                'type' => [
                                                                    'kind' => 'map',
                                                                    'values' => [
                                                                        'kind' => 'any',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'parents' => [],
                                                        'additional' => false,
                                                    ],
                                                ],
                                                'sampling' => [
                                                    'required' => false,
                                                    'type' => [
                                                        'kind' => 'record',
                                                        'fields' => [
                                                            'createMessage' => [
                                                                'required' => false,
                                                                'type' => [
                                                                    'kind' => 'map',
                                                                    'values' => [
                                                                        'kind' => 'any',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'parents' => [],
                                                        'additional' => false,
                                                    ],
                                                ],
                                            ],
                                            'parents' => [],
                                            'additional' => false,
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '523b0717d5565753f9bec8260de70efc6dd26c2d8249fec9a0419e8cc41a95da' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'data' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'audio',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '546a1f096fe349b8a4fbb54a0424a0205be466d8b16bfe18c9047937253c7cd8' => [
                    'kind' => 'record',
                    'fields' => [
                        'model' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'stopReason' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'endTurn',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'stopSequence',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'maxTokens',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'toolUse',
                                    ],
                                    [
                                        'kind' => 'string',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'SamplingMessage',
                        ],
                    ],
                    'additional' => false,
                ],
                '5497b0290d183bec6460a81ec04661a3f6ac3739741f23c97a38be4b52535e8e' => [
                    'kind' => 'record',
                    'fields' => [
                        'message' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mode' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'form',
                            ],
                        ],
                        'requestedSchema' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '$schema' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                    'properties' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [],
                                            'parents' => [],
                                            'additional' => [
                                                'kind' => 'ref',
                                                'name' => 'PrimitiveSchemaDefinition',
                                            ],
                                        ],
                                    ],
                                    'required' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                    'type' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'literal',
                                            'value' => 'object',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '54ca6636b0bd81c3b890263c077f1642a76477aea0e3cf3fa45987c95aeaee6f' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resources/read',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ReadResourceRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '54cb8fe437ac131d47102487c921e8eb100befbdf18a080a7b1012a3785606f0' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'uriTemplate' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '55ef98235c635e09ee04e9dc9ea65bf94f4cd94fb136e474739b4484c17b087b' => [
                    'kind' => 'record',
                    'fields' => [
                        'notifications' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscriptionFilter',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '566bb526abf9b5f8c02178a278a26a10aa0797dbb8af88a05598f612c8dcb82f' => [
                    'kind' => 'record',
                    'fields' => [
                        'cacheScope' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'public',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'private',
                                    ],
                                ],
                            ],
                        ],
                        'ttlMs' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '5830749c2c7b93efbe2ef8e5067271de94493be507d3ec9a12216271615b040b' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'id' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'input' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tool_use',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '58edbce6c09c2dc39fa280613fe09af5e3e505d4da2cf515670a9a8358661a6e' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'TextContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ImageContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'AudioContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceLink',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'EmbeddedResource',
                        ],
                    ],
                ],
                '5a34b2a6b75b91757220871c97853878957bae332bac433a64ef6492bc594830' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ToolAnnotations',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'inputSchema' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '$schema' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                    'type' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'literal',
                                            'value' => 'object',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'outputSchema' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '$schema' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '5a6cb1683f693e8ce7b06ab2c2090bb0948c4213f89b901b07bf1c54e7a73098' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tools/list',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '5af96c5e34bd823a6cf43bbaea2570dab3b6b3eaf958c343cda6de8a630b4981' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tasks/get',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'taskId' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '5ce1bf64c40d638fb26e70b541a9105b04fbf76cef13e0af5eb0103e64ea3e4b' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'data' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'audio',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '5f5a6c0edbd3e3253f8d491614580ede44dc9445fe00fe23cb89ceaf7ae4ffb2' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'maximum' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'minimum' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'number',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'integer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '61410b4e6b3512301f072f92a90ff5de90b0718e78784e8f384d60908fce4dd4' => [
                    'kind' => 'record',
                    'fields' => [
                        'arguments' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '626936d9cbef8e9af527ae81cf3a8b344bb96cb18944ba9b7b83dcf1250ef505' => [
                    'kind' => 'record',
                    'fields' => [
                        'argument' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'name' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                    'value' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'context' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'arguments' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'record',
                                            'fields' => [],
                                            'parents' => [],
                                            'additional' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'ref' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'PromptReference',
                                    ],
                                    [
                                        'kind' => 'ref',
                                        'name' => 'ResourceTemplateReference',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '641cce8bbf27ea0b198ab81e2f472c3efdbac5e83a069d1675816a774e13ce32' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ListPromptsResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '6664f3cb433836877cc82fba57ced43a4ed7b96e24217bed5d1cadea75618256' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscriptionsListenResultMetaObject',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '68f37295e42b0448d455dcba49b1b37f4e3919bfb2cc07738f3f2ba46177c654' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/resources/list_changed',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'NotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                '690c4bb85d7a06c44f30755f12db0c8f52bd42afc15d717e9fbf018b47ab2f9d' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'resource' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'TextResourceContents',
                                    ],
                                    [
                                        'kind' => 'ref',
                                        'name' => 'BlobResourceContents',
                                    ],
                                ],
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resource',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '692c8f9ae10b4a377b66af309abf469a25f4e77f24f9f14cf94e3bd4e2da77f6' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'literal',
                            'value' => 'working',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'input_required',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'completed',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'failed',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'cancelled',
                        ],
                    ],
                ],
                '6ed0530eb5ed8455b39ccf926bc832366d376a740096dae7133b13afbc3c2998' => [
                    'kind' => 'record',
                    'fields' => [
                        'blob' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceContents',
                        ],
                    ],
                    'additional' => false,
                ],
                '6f4bfab7f8882d3cabc00eb3a5d11c25a9747db2e7638612647f0119845da87c' => [
                    'kind' => 'record',
                    'fields' => [
                        'reason' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'requestId' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '6f84ee7da3e0deeb925cb10fe5800182e1beb5f19528acb8e7db199aee34dc18' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tasks/result',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'taskId' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '703a7b4530cfb40973761b2121d16f0213c5c2971a16699001f3e3d986512222' => [
                    'kind' => 'record',
                    'fields' => [
                        'io.modelcontextprotocol/subscriptionId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'ResultMetaObject',
                        ],
                    ],
                    'additional' => false,
                ],
                '72133c4790109a76018ceeba83b83e26ec15ab5386c1901d566ea16a7e102400' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestMetaObject',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '725d88eafeb72df891575802199de52f6629c541ae90851f7fbce23b773e0dc5' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'string',
                        ],
                        [
                            'kind' => 'number',
                        ],
                    ],
                ],
                '752c90fd455e3c6279efcb95f4f7fa8661fa577f81d23275a9dd75b843e918ec' => [
                    'kind' => 'record',
                    'fields' => [],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => [
                        'kind' => 'any',
                    ],
                ],
                '769ff700e91ecf0b0a2884ec8c7f3fdc40c243e461da27bc848a2b3d701598ac' => [
                    'kind' => 'record',
                    'fields' => [
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'messages' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'PromptMessage',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '7718f5ae79c70b7968cffca1dc89fe768646af27c1c7e67f28f755990f197123' => [
                    'kind' => 'map',
                    'values' => [
                        'kind' => 'any',
                    ],
                ],
                '77ff7357ce669b24231945f3e99de044d00749e259c274606548f8e781585f94' => [
                    'kind' => 'record',
                    'fields' => [
                        'prompts' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Prompt',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CacheableResult',
                        ],
                    ],
                    'additional' => false,
                ],
                '785b579babed86ceb5d4c05cc9a7dae9cd824a47c7b1e9a7d1ff9be09d7c9ecf' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'items' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'anyOf' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'record',
                                                'fields' => [
                                                    'const' => [
                                                        'required' => true,
                                                        'type' => [
                                                            'kind' => 'string',
                                                        ],
                                                    ],
                                                    'title' => [
                                                        'required' => true,
                                                        'type' => [
                                                            'kind' => 'string',
                                                        ],
                                                    ],
                                                ],
                                                'parents' => [],
                                                'additional' => false,
                                            ],
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'maxItems' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'minItems' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'array',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '7a58596cfa5e869521d64bf89c24bf747c05685f72c6c272d76ebe0c3f4eea3d' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CancelledNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ProgressNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'LoggingMessageNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceUpdatedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ToolListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'PromptListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitationCompleteNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'TaskStatusNotification',
                        ],
                    ],
                ],
                '7b4d6f829ad188dc30a95abb063d9f71f3d6bb8237e308cb10d52deb858cada1' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'boolean',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '7b557ebe6667b288c0df12f3d48244f58baa45d5e3441a8ffecf0297ed029daf' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '7d628467e39ecf4d9bacc204e1518596ef1bda879bf8f3ebee67d663e8a8f8d3' => [
                    'kind' => 'record',
                    'fields' => [
                        'capabilities' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ServerCapabilities',
                            ],
                        ],
                        'instructions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'protocolVersion' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'serverInfo' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Implementation',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '818e18e1f1222e4f0ae8149bed7b805afc4276c5f3ce1f4428eb11e940a18c8b' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'roots/list',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    '_meta' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'ref',
                                            'name' => 'MetaObject',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '81bfa683dc4c8a2a18638febca92835f2a436a34c74c2f6f8506d17de100de6a' => [
                    'kind' => 'record',
                    'fields' => [
                        'content' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ContentBlock',
                                ],
                            ],
                        ],
                        'isError' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                        'structuredContent' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                '81f76b626317b6c25c251f91fd3b050c465eb95df3e3ce3df279926565455458' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'server/discover',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '82a71d19afcb13219e9f8a18b3ec65f67acd96dab51450cbf26a565d6e96e87d' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'elicitation/create',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ElicitRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '833d58a25ad9bb04491fca9ab61f413caff3c32fc03378491dd0923e29a30eed' => [
                    'kind' => 'record',
                    'fields' => [
                        'code' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => -32700,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Error',
                        ],
                    ],
                    'additional' => false,
                ],
                '848b552c88ae7cc88524840e500ac1c760ce866d22c04a829928e8e86b669863' => [
                    'kind' => 'ref',
                    'name' => 'Result',
                ],
                '84d9e9d061a730fea182f1ef0c14ff78e74407923c888924718e0fa083bf6f38' => [
                    'kind' => 'record',
                    'fields' => [
                        'code' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => -32600,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Error',
                        ],
                    ],
                    'additional' => false,
                ],
                '866f467a4161e4b10ba496fb1dd69995a6924086416b6cdc8776d6d180bcb5ea' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'roots/list',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '869a7defca74b305452361c008db01797e22730ea8eaaf067acfd7111cf71349' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'format' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'email',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'uri',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'date',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'date-time',
                                    ],
                                ],
                            ],
                        ],
                        'maxLength' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'minLength' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '86b653ca1f90aa1a4e2ecf9c996eac446dbdc506089c5673dd39e2fa1e14a38b' => [
                    'kind' => 'record',
                    'fields' => [
                        'tools' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Tool',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CacheableResult',
                        ],
                    ],
                    'additional' => false,
                ],
                '88c8ee597e23e85d29b4080e0c24ad1a436752a80ba30349358f4a7958a9f895' => [
                    'kind' => 'record',
                    'fields' => [
                        'resources' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Resource',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                    ],
                    'additional' => false,
                ],
                '8951ff962222fed6abb39324bca1a70c8feef6029ed04c0fe26e31964e820b5a' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PingRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CreateMessageRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListRootsRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskPayloadRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListTasksRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CancelTaskRequest',
                        ],
                    ],
                ],
                '899f9d97c38bdfb5d06ee49d31bef836d187238de150ede6ae2594ca902fb301' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCErrorResponse',
                        ],
                    ],
                ],
                '8bbc9f2aa7a1a69e7788a2e901fc12f1cb33c27fc520dd5af9a37ded28ab271c' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ResultMetaObject',
                            ],
                        ],
                        'resultType' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ResultType',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => [
                        'kind' => 'any',
                    ],
                ],
                '8bfaef09126f5cd5d74ee74fa32d8623e62bc3bd50788886902c603c570d2052' => [
                    'kind' => 'ref',
                    'name' => 'CancelledNotification',
                ],
                '8d91ac57bface15cf61cb2835536b3f065e7553f73196f7560ae514ba72c2ba2' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'ReadResourceResult',
                                    ],
                                    [
                                        'kind' => 'ref',
                                        'name' => 'InputRequiredResult',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                '8f1a0a87efa7c60e206208553407418b0dc8386bfc0a1104554876f13d48dafc' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'literal',
                            'value' => 'user',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => 'assistant',
                        ],
                    ],
                ],
                '906fb72164ab2e62c74994d7a9e36d266c7fbcfb6dace66328e9127d988058bd' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'arguments' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'PromptArgument',
                                ],
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                '90c52f4cfe798a69d436c2b67fefb852f61e0eb3bef1ff0d5f0a3162bddcbde2' => [
                    'kind' => 'record',
                    'fields' => [
                        'reason' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'requestId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '91dd5a0a0817845a9810250deeaa3cd4bf704c847dada978476570c81b749ead' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tasks/list',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                '92dcfb3098611b01c3af44eb7980401dc946d0334495181207e91ae527245cd7' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResponse',
                        ],
                    ],
                ],
                '93b88bf767ed7bec83e37407c565eb98f2c4fe37a339008b784f6d1a601d7028' => [
                    'kind' => 'record',
                    'fields' => [
                        'level' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'LoggingLevel',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                '94416d54ea8e331c69f806303181fed040fe33d4b0e261dff88770bd081ee2f7' => [
                    'kind' => 'record',
                    'fields' => [
                        'ttl' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                '94f08740a82ec1d850f594570bc77f95bdaec03c74dc7544d5be072109b06795' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/message',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'LoggingMessageNotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                '95227a44893d5eaf02ea90425c86c6e6bcbe4f4e424f9465bcce1884135bcecb' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/progress',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ProgressNotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                '9595862a6acd88e8c27901b536b5ae2aa21b038ad21ab5cdd2264a7e651aa973' => [
                    'kind' => 'record',
                    'fields' => [
                        'io.modelcontextprotocol/serverInfo' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Implementation',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'MetaObject',
                        ],
                    ],
                    'additional' => false,
                ],
                '9bddc1fdfb55932e41cf34e4a0bf062564cf848d3e0070d86dcd3a998fdce008' => [
                    'kind' => 'string',
                ],
                '9be779c5ee4ff7cb68ae05fbf09c675f491b740059e0c18a44c6f53c879888c2' => [
                    'kind' => 'record',
                    'fields' => [
                        'tools' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Tool',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                    ],
                    'additional' => false,
                ],
                '9e72a946898b43ed2aee4d01f778f7d0cc62d81e2a34d6660cc504dd117074e6' => [
                    'kind' => 'record',
                    'fields' => [],
                    'parents' => [],
                    'additional' => [
                        'kind' => 'ref',
                        'name' => 'InputResponse',
                    ],
                ],
                'a01b5badd56aff721d35d81c8d20df1e263a473f4faab8eaba7fd8a24f82eb8b' => [
                    'kind' => 'record',
                    'fields' => [
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resource_link',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Resource',
                        ],
                    ],
                    'additional' => false,
                ],
                'a0ac81e894be01dcc9fd3c7093081c49096e02fb0d2245ec4f2a4c1050a5b94a' => [
                    'kind' => 'intersection',
                    'allOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Task',
                        ],
                    ],
                ],
                'a1b7d4fb9d84cfc6c43de0b806b0ef1b43592f342cadff304ac8207423ae1450' => [
                    'kind' => 'record',
                    'fields' => [
                        'task' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Task',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                'a2c59aaeaae253dc7ad831854bd4dc94ace52d820fc1cff8597b01062e5362fc' => [
                    'kind' => 'record',
                    'fields' => [
                        'elicitation' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'form' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'ref',
                                            'name' => 'JSONObject',
                                        ],
                                    ],
                                    'url' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'ref',
                                            'name' => 'JSONObject',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'experimental' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'ref',
                                    'name' => 'JSONObject',
                                ],
                            ],
                        ],
                        'extensions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'ref',
                                    'name' => 'JSONObject',
                                ],
                            ],
                        ],
                        'roots' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'sampling' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'context' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'ref',
                                            'name' => 'JSONObject',
                                        ],
                                    ],
                                    'tools' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'ref',
                                            'name' => 'JSONObject',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'a3270d527f86da39141d77600ecd3d5112048069ba70672aadd30494c4ecc5db' => [
                    'kind' => 'record',
                    'fields' => [
                        'io.modelcontextprotocol/clientCapabilities' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ClientCapabilities',
                            ],
                        ],
                        'io.modelcontextprotocol/clientInfo' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Implementation',
                            ],
                        ],
                        'io.modelcontextprotocol/logLevel' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'LoggingLevel',
                            ],
                        ],
                        'io.modelcontextprotocol/protocolVersion' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'progressToken' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ProgressToken',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'MetaObject',
                        ],
                    ],
                    'additional' => false,
                ],
                'a4873140d90e637032ac6b207ee0d86da75a62cb41aa094778fe6a74c9a6abd3' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/tools/list_changed',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'NotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                'a58107b851d812af49b2f4c508615f3ce5132383d72ead761855709dc6562b05' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'enum' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'a668dd1382bd356b50b60223c41adb70d843ece7e53d91a19ad32d853169142c' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/subscriptions/acknowledged',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscriptionsAcknowledgedNotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                'a7e89fe437a7b09aa709daa4778cb54239be0794fa76e51eb87483b8c070dcf7' => [
                    'kind' => 'record',
                    'fields' => [
                        'includeContext' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'none',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'thisServer',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'allServers',
                                    ],
                                ],
                            ],
                        ],
                        'maxTokens' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'messages' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'SamplingMessage',
                                ],
                            ],
                        ],
                        'metadata' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'map',
                                'values' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'modelPreferences' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ModelPreferences',
                            ],
                        ],
                        'stopSequences' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'systemPrompt' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'temperature' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'toolChoice' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ToolChoice',
                            ],
                        ],
                        'tools' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Tool',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'TaskAugmentedRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                'a7fafa6aa20355815aa3c3fcf7f09c759d05a59df68a962ad645aae07d6d966c' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'TextContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ImageContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'AudioContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ToolUseContent',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ToolResultContent',
                        ],
                    ],
                ],
                'aa252d98219442a18b7fde9e042ea5a166ff1d1e368b8b16f5ca8ee586721e90' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'UntitledMultiSelectEnumSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'TitledMultiSelectEnumSchema',
                        ],
                    ],
                ],
                'aa80f54eec259a269eba8833dce28598f597997abe756764ea9fc72f9c4bccdd' => [
                    'kind' => 'record',
                    'fields' => [
                        'completions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'JSONObject',
                            ],
                        ],
                        'experimental' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'ref',
                                    'name' => 'JSONObject',
                                ],
                            ],
                        ],
                        'extensions' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'ref',
                                    'name' => 'JSONObject',
                                ],
                            ],
                        ],
                        'logging' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'JSONObject',
                            ],
                        ],
                        'prompts' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'resources' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                    'subscribe' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'tools' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'listChanged' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'ab8806b20249e6b91b98231da341df47fc019269f4c9af7f51a36f6f8ffec155' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'progressToken' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'ref',
                                            'name' => 'ProgressToken',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'ac22447f4613a47c93b2f5e660bb856dd9e77011aa130f7d3c7f3c615e3f9a9c' => [
                    'kind' => 'record',
                    'fields' => [
                        'taskId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'aca4b5d615eb62df0d6ed4582139db992b6a46ffeba09837591677a4c318073b' => [
                    'kind' => 'record',
                    'fields' => [
                        'code' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => -32603,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Error',
                        ],
                    ],
                    'additional' => false,
                ],
                'ad5e0649ce479184bbcd9a1aca2e64408aa41b0ad55e38d1d15b0c7f3b31af8f' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'name' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'aebff34739361d6f6af193ca318cd6daff3f21cd4458de7a9e02d1b00a6e46ba' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/resources/updated',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ResourceUpdatedNotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                'aec911e218b1f2c37ab192d0b916619a92981ffc772d2bbfd502abc9e75205df' => [
                    'kind' => 'record',
                    'fields' => [
                        'tasks' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Task',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                    ],
                    'additional' => false,
                ],
                'b0a19f4ebe08bc4df2bdfc2b55d2005ff15abf8f1d3abde6da50fdbe02db5157' => [
                    'kind' => 'record',
                    'fields' => [
                        'mode' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'auto',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'required',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'none',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'b11013bed05ebbe3ccd84861c0987bb5dec98f78f10b551d73929a749867e614' => [
                    'kind' => 'record',
                    'fields' => [
                        'data' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'any',
                            ],
                        ],
                        'level' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'LoggingLevel',
                            ],
                        ],
                        'logger' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                    ],
                    'additional' => false,
                ],
                'b17b025c882cf210820c3cbb0016377466ec14bd70bb665d648e3cd68a1129bc' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/initialized',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'NotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                'b3227810c5f840253486f6a9ae273faefbe6bb8cb8e4d3989a1cb16639aae914' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'DiscoverRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CompleteRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetPromptRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListPromptsRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourcesRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourceTemplatesRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ReadResourceRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'SubscriptionsListenRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CallToolRequest',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListToolsRequest',
                        ],
                    ],
                ],
                'b430cd158c442064ef65c9f54e26490cbb47e6d2c987b64c0e19d335e7322fec' => [
                    'kind' => 'record',
                    'fields' => [
                        'capabilities' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ClientCapabilities',
                            ],
                        ],
                        'clientInfo' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Implementation',
                            ],
                        ],
                        'protocolVersion' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                'b44ff99e73b003533a8c8cdcb1d6e395cdfbfb4076f86a054363635fe705bbb8' => [
                    'kind' => 'record',
                    'fields' => [
                        'notifications' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscriptionFilter',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                'b517a61dfcf0525ac412c8c01459c96dfa3d72c3bc5d003db404d72e551cc801' => [
                    'kind' => 'record',
                    'fields' => [
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'required' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'boolean',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                    ],
                    'additional' => false,
                ],
                'b5297a7fdf74b3a8b5d55320db910a7a99bdddb2041ced2fdd79c3a20f49248e' => [
                    'kind' => 'record',
                    'fields' => [
                        'text' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceContents',
                        ],
                    ],
                    'additional' => false,
                ],
                'b595eec3e3855073264ee5e3b32a4a57b54974781cfdb0b4b4656c8663eb21ec' => [
                    'kind' => 'record',
                    'fields' => [],
                    'parents' => [],
                    'additional' => [
                        'kind' => 'ref',
                        'name' => 'JSONValue',
                    ],
                ],
                'b7226dad87dd3327dccb852d3b9ef31feeae46b2c9933242d0089d7fc3c26a36' => [
                    'kind' => 'record',
                    'fields' => [
                        'task' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'TaskMetadata',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'RequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                'b7aaffea8d1f42e1aa29cf7a748e4ba4739b5bd8013b49cf7ab028f35e9e8b2d' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resources/templates/list',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'b7f349e4964481f431b2c24e25c9b6aef09a84bc1e8bed361b27181363a05eb1' => [
                    'kind' => 'record',
                    'fields' => [
                        'io.modelcontextprotocol/subscriptionId' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'MetaObject',
                        ],
                    ],
                    'additional' => false,
                ],
                'b8aa317ddaaca339b65e386d1b43e4539669f4c61bee7d7db031b04fb49f0d2f' => [
                    'kind' => 'record',
                    'fields' => [
                        'error' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'intersection',
                                'allOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'Error',
                                    ],
                                    [
                                        'kind' => 'record',
                                        'fields' => [
                                            'code' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'literal',
                                                    'value' => -32020,
                                                ],
                                            ],
                                        ],
                                        'parents' => [],
                                        'additional' => false,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'omit',
                            'from' => [
                                'kind' => 'ref',
                                'name' => 'JSONRPCErrorResponse',
                            ],
                            'keys' => [
                                'error',
                            ],
                        ],
                    ],
                    'additional' => false,
                ],
                'b9a132338083bcffef2866a03a780bf2365593a14218189d421a218f76a9f5a2' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => [
                        'kind' => 'any',
                    ],
                ],
                'ba54cbde50aa10c8c7b7fb87cb8b223c7edef27ea24750ab31c6b831c57f8771' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resources/subscribe',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscribeRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'baefbbdbba0a3354b735583fc9b5b8247c28dcc07a4e953dfe8d38aa4de4e5b2' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'size' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                'bc8d5f7f61033da11ed1233551f833dd17074348c348364157fe443d8b44775e' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'sampling/createMessage',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'CreateMessageRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'bd1d6f7e224c0e7f9390b276abbb7d1653e996a942bdf5b6e7a2f00817c5397d' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'bdaa6d447221b0cf0a42c56eb1ca3378e3ac48ccfed5040a1f3a14fcb70d1c37' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'elicitation/create',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ElicitRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'c17a130d00b6eb43ec07c125fd5e4f897941ee34efe20dfe8aed270a8d252279' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'subscriptions/listen',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SubscriptionsListenRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'c1ce7f8c06ecf99e5b3dd0f1a95956005b6706da8d7416cf4886407b38af5e90' => [
                    'kind' => 'record',
                    'fields' => [
                        'prompts' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Prompt',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                    ],
                    'additional' => false,
                ],
                'c1ef1eaafb13ce513292da2be3ed7bef04bbf0264d96081d47f4d04a821ccd37' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'ListResourceTemplatesResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                'c37bdb945be74165cb365903afadb87e2b02da02605959588a9ec5c8021d6ced' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'string',
                        ],
                        [
                            'kind' => 'number',
                        ],
                        [
                            'kind' => 'boolean',
                        ],
                        [
                            'kind' => 'literal',
                            'value' => null,
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'JSONObject',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'JSONArray',
                        ],
                    ],
                ],
                'c3abe71235fdcf042adddaefd55485a887684fbf9cad4dfb3a50667d015d3c5d' => [
                    'kind' => 'record',
                    'fields' => [
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'version' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'websiteUrl' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Icons',
                        ],
                    ],
                    'additional' => false,
                ],
                'c586c52751aef3b40171900fe36de1d782e136f9a2eee3f2b1c00984c602f15b' => [
                    'kind' => 'record',
                    'fields' => [
                        'completion' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'hasMore' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'boolean',
                                        ],
                                    ],
                                    'total' => [
                                        'required' => false,
                                        'type' => [
                                            'kind' => 'number',
                                        ],
                                    ],
                                    'values' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                'c8b68584586f7c72fd8291a2b94d456e0bd4b500f76b9755c46337d3f63453cd' => [
                    'kind' => 'record',
                    'fields' => [
                        'resourceTemplates' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ResourceTemplate',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CacheableResult',
                        ],
                    ],
                    'additional' => false,
                ],
                'cbf08416aefdef4f7cb20a51ee569c46c8697179fc28dac04136d88cf9a86262' => [
                    'kind' => 'record',
                    'fields' => [
                        'code' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'data' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'any',
                            ],
                        ],
                        'message' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'd2138088265d38716276841ed1d893f5232f7ac279a83bdbc598c305ddf33b48' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'enum' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'enumNames' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'd2df755d73cf75af72f6df8591f31518f700847ba3f81ad06f28d80b07ed91d8' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CreateMessageResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListRootsResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ElicitResult',
                        ],
                    ],
                ],
                'd3295fd0e0479d2b738f616e5374b820efd3266029af2c8e5f4244a123da347d' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'logging/setLevel',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'SetLevelRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'd3d68fbb283a777f1d3c8a50dab389e2b598301423447325f0cc86260cab7f11' => [
                    'kind' => 'record',
                    'fields' => [],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceRequestParams',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'InputResponseRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
                'd3e7acef8998c45e036cd100d7777956a0e9faa6437ac73e6a2954a150cb0880' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'resources/unsubscribe',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'UnsubscribeRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'd57e88d443bd49c8df230161c445a6107e7ddf414c5ca1ee6e353c999dd44c71' => [
                    'kind' => 'record',
                    'fields' => [
                        'resources' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'Resource',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CacheableResult',
                        ],
                    ],
                    'additional' => false,
                ],
                'd62229b8f797a00ce38c07749a171c7c625332a0943153cade2f48c32441ebd9' => [
                    'kind' => 'record',
                    'fields' => [
                        'contents' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'union',
                                    'anyOf' => [
                                        [
                                            'kind' => 'ref',
                                            'name' => 'TextResourceContents',
                                        ],
                                        [
                                            'kind' => 'ref',
                                            'name' => 'BlobResourceContents',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                'd908535bde749822e82207083cf37682b0ba799d9a1c425c4430b78b7103cd94' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'SingleSelectEnumSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'MultiSelectEnumSchema',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'LegacyTitledEnumSchema',
                        ],
                    ],
                ],
                'd95bbf0d66b4c391767525976d8bdf940a8c2b8f3c6e0e13995bec5df5cc1051' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'EmptyResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'InitializeResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CompleteResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetPromptResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListPromptsResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourceTemplatesResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListResourcesResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ReadResourceResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CallToolResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListToolsResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'GetTaskPayloadResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ListTasksResult',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'CancelTaskResult',
                        ],
                    ],
                ],
                'db7a6d10a8cb4be19b3228461f0634568c747f79d6122e93780980397587fbdc' => [
                    'kind' => 'record',
                    'fields' => [
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'DiscoverResult',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCResultResponse',
                        ],
                    ],
                    'additional' => false,
                ],
                'df215f6b66866f63609af4d9c5a0b257c2891d64d9547ff38a0cc598dfba6841' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'annotations' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Annotations',
                            ],
                        ],
                        'data' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mimeType' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'image',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'e1fc6b4953384af5ab4c24560bc6b4e1916ed10f2ba38479e42008ad42e0800f' => [
                    'kind' => 'record',
                    'fields' => [
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'PaginatedRequestParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCRequest',
                        ],
                    ],
                    'additional' => false,
                ],
                'e3e1c4dc09adc34b4f43d055e8a3cfc9d8a688fdd97b4908e91fa81bd19d0516' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'id' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'input' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'tool_use',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'e4bb4b42445382237876d16670531f2ab9eb197f1994836decc5d7f9955d42e3' => [
                    'kind' => 'ref',
                    'name' => 'EmptyResult',
                ],
                'e514232a0ef4ae24de54d5837f01d7cdc0c2020f7889c04cfc6df3a9ef1c7e83' => [
                    'kind' => 'record',
                    'fields' => [
                        'id' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                        'jsonrpc' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => '2.0',
                            ],
                        ],
                        'result' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Result',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'e54e09d37f54628e27867274f64d92d0ac03ad0accc0d4ce50b765ca0545c532' => [
                    'kind' => 'union',
                    'anyOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CancelledNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ProgressNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'LoggingMessageNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceUpdatedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ResourceListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'ToolListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'PromptListChangedNotification',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'SubscriptionsAcknowledgedNotification',
                        ],
                    ],
                ],
                'e58db7fa8eaa2110327dbc8468cf735b14ce489d2406141bb28bdf31ea58a0eb' => [
                    'kind' => 'record',
                    'fields' => [
                        'resourceTemplates' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'ref',
                                    'name' => 'ResourceTemplate',
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'PaginatedResult',
                        ],
                    ],
                    'additional' => false,
                ],
                'e8de88e3a6c048f0ea951482b4d5384009dca3a8d00bb854adf9d36e3725680a' => [
                    'kind' => 'record',
                    'fields' => [
                        'error' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'intersection',
                                'allOf' => [
                                    [
                                        'kind' => 'ref',
                                        'name' => 'Error',
                                    ],
                                    [
                                        'kind' => 'record',
                                        'fields' => [
                                            'code' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'literal',
                                                    'value' => -32021,
                                                ],
                                            ],
                                            'data' => [
                                                'required' => true,
                                                'type' => [
                                                    'kind' => 'record',
                                                    'fields' => [
                                                        'requiredCapabilities' => [
                                                            'required' => true,
                                                            'type' => [
                                                                'kind' => 'ref',
                                                                'name' => 'ClientCapabilities',
                                                            ],
                                                        ],
                                                    ],
                                                    'parents' => [],
                                                    'additional' => false,
                                                ],
                                            ],
                                        ],
                                        'parents' => [],
                                        'additional' => false,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'omit',
                            'from' => [
                                'kind' => 'ref',
                                'name' => 'JSONRPCErrorResponse',
                            ],
                            'keys' => [
                                'error',
                            ],
                        ],
                    ],
                    'additional' => false,
                ],
                'eb3b2d980723dae6246e278d618e1b2380f8ac1e62e9c77e197fae71b7c1e7ce' => [
                    'kind' => 'record',
                    'fields' => [
                        'nextCursor' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'Cursor',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Result',
                        ],
                    ],
                    'additional' => false,
                ],
                'edb123f0f494e6a9fe6763130f096763bdff0e4772f88b077ef7ef8b4f59f3ed' => [
                    'kind' => 'record',
                    'fields' => [
                        'message' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'mode' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'url',
                            ],
                        ],
                        'url' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'ef2d78e131efbe00b3a37ae5346d4c12dbd795eeedf85a96c24244caba5d6763' => [
                    'kind' => 'record',
                    'fields' => [
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'ref/resource',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'f01a74823018c1ee2b8d9ddfd99211b14c3c12c68cee6e80c8328baa78de4309' => [
                    'kind' => 'record',
                    'fields' => [
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'sizes' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'src' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'theme' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'light',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'dark',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'f1a072579db118c0dd9d335f537df3d19bea23c95661c40808f76c246e17ddcc' => [
                    'kind' => 'record',
                    'fields' => [
                        'createdAt' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'lastUpdatedAt' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'pollInterval' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'status' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'TaskStatus',
                            ],
                        ],
                        'statusMessage' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'taskId' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'ttl' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'number',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'f2c9a5740f2e04b30ce0b8cf1cb1fc2e90289d5028786c6fdf895c20e3fb16d1' => [
                    'kind' => 'record',
                    'fields' => [
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'ref/prompt',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'BaseMetadata',
                        ],
                    ],
                    'additional' => false,
                ],
                'f56adc0fe73b6edd8018154b634324b7106945e20ea7284c6f122d8c668690ff' => [
                    'kind' => 'record',
                    'fields' => [
                        'id' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'RequestId',
                            ],
                        ],
                        'jsonrpc' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => '2.0',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'Request',
                        ],
                    ],
                    'additional' => false,
                ],
                'f61721d0522776066beda66b637b3b5d22739ad191a0b75082a97c980e4f1fd2' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'oneOf' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'record',
                                    'fields' => [
                                        'const' => [
                                            'required' => true,
                                            'type' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                        'title' => [
                                            'required' => true,
                                            'type' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                    'parents' => [],
                                    'additional' => false,
                                ],
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'f7b0963164530e607656578e7e671b7c583d7521c825e5468626bdd8a8e0e9d0' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/cancelled',
                            ],
                        ],
                        'params' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'CancelledNotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                'f7b3459d22977703a7a5b239f62224a677bccf869070097cb60080fac0ea096e' => [
                    'kind' => 'record',
                    'fields' => [
                        'method' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'notifications/roots/list_changed',
                            ],
                        ],
                        'params' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'NotificationParams',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'JSONRPCNotification',
                        ],
                    ],
                    'additional' => false,
                ],
                'f7d926795300c12074321bb163a7fd6f9a0c27d4e7ad345d6182ac0837fcd69b' => [
                    'kind' => 'intersection',
                    'allOf' => [
                        [
                            'kind' => 'ref',
                            'name' => 'NotificationParams',
                        ],
                        [
                            'kind' => 'ref',
                            'name' => 'Task',
                        ],
                    ],
                ],
                'f9001327ea8c1b652d3d92178b6303c649d2319ca0a1967f2b68e3dc7aa27942' => [
                    'kind' => 'record',
                    'fields' => [
                        'model' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'stopReason' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'union',
                                'anyOf' => [
                                    [
                                        'kind' => 'literal',
                                        'value' => 'endTurn',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'stopSequence',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'maxTokens',
                                    ],
                                    [
                                        'kind' => 'literal',
                                        'value' => 'toolUse',
                                    ],
                                    [
                                        'kind' => 'string',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'SamplingMessage',
                        ],
                    ],
                    'additional' => false,
                ],
                'f9ec6ef233f8cad733ebe004793c40af89ee1c7b19ea1d79757fe58b95e8e11a' => [
                    'kind' => 'record',
                    'fields' => [
                        '_meta' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'ref',
                                'name' => 'MetaObject',
                            ],
                        ],
                        'mimeType' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'uri' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'fb5359f6094f88f4d8f3574d7e82bfe09a649895be68bde3aa2cdaadb9a2c2ae' => [
                    'kind' => 'record',
                    'fields' => [
                        'default' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'string',
                                ],
                            ],
                        ],
                        'description' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'items' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [
                                    'enum' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'list',
                                            'items' => [
                                                'kind' => 'string',
                                            ],
                                        ],
                                    ],
                                    'type' => [
                                        'required' => true,
                                        'type' => [
                                            'kind' => 'literal',
                                            'value' => 'string',
                                        ],
                                    ],
                                ],
                                'parents' => [],
                                'additional' => false,
                            ],
                        ],
                        'maxItems' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'minItems' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'number',
                            ],
                        ],
                        'title' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                        'type' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'literal',
                                'value' => 'array',
                            ],
                        ],
                    ],
                    'parents' => [],
                    'additional' => false,
                ],
                'fd8c267e0ce9d9d847ed953dadf7bf209bb9a6fe6f0537c1b82a598d1bf92a0b' => [
                    'kind' => 'record',
                    'fields' => [
                        'contents' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'list',
                                'items' => [
                                    'kind' => 'union',
                                    'anyOf' => [
                                        [
                                            'kind' => 'ref',
                                            'name' => 'TextResourceContents',
                                        ],
                                        [
                                            'kind' => 'ref',
                                            'name' => 'BlobResourceContents',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'CacheableResult',
                        ],
                    ],
                    'additional' => false,
                ],
                'fe697eb27adaceabc4d7d97aecc99ab30dd8b179ac490061e3e3b8da99773f53' => [
                    'kind' => 'record',
                    'fields' => [
                        'arguments' => [
                            'required' => false,
                            'type' => [
                                'kind' => 'record',
                                'fields' => [],
                                'parents' => [],
                                'additional' => [
                                    'kind' => 'any',
                                ],
                            ],
                        ],
                        'name' => [
                            'required' => true,
                            'type' => [
                                'kind' => 'string',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'kind' => 'ref',
                            'name' => 'TaskAugmentedRequestParams',
                        ],
                    ],
                    'additional' => false,
                ],
            ];
        }
        return self::$descriptorCache;
    }

    /** @return array<string, array{fingerprint: string, roots: array<int, string>, types: array<string, string>}> */
    public static function manifests(): array
    {
        if (self::$manifestCache === null) {
            self::$manifestCache = [
                '2025-11-25' => [
                    'fingerprint' => '57685761f4b49479e16577446fb7bd05adebc58a91b4435db118d2ee987e1cf0',
                    'roots' => [
                        'Annotations',
                        'AudioContent',
                        'BaseMetadata',
                        'BlobResourceContents',
                        'BooleanSchema',
                        'CallToolRequest',
                        'CallToolRequestParams',
                        'CallToolResult',
                        'CancelTaskRequest',
                        'CancelTaskResult',
                        'CancelledNotification',
                        'CancelledNotificationParams',
                        'ClientCapabilities',
                        'ClientNotification',
                        'ClientRequest',
                        'ClientResult',
                        'CompleteRequest',
                        'CompleteRequestParams',
                        'CompleteResult',
                        'ContentBlock',
                        'CreateMessageRequest',
                        'CreateMessageRequestParams',
                        'CreateMessageResult',
                        'CreateTaskResult',
                        'ElicitRequest',
                        'ElicitRequestFormParams',
                        'ElicitRequestParams',
                        'ElicitRequestURLParams',
                        'ElicitResult',
                        'ElicitationCompleteNotification',
                        'EmbeddedResource',
                        'EmptyResult',
                        'EnumSchema',
                        'Error',
                        'GetPromptRequest',
                        'GetPromptRequestParams',
                        'GetPromptResult',
                        'GetTaskPayloadRequest',
                        'GetTaskPayloadResult',
                        'GetTaskRequest',
                        'GetTaskResult',
                        'Icon',
                        'Icons',
                        'ImageContent',
                        'Implementation',
                        'InitializeRequest',
                        'InitializeRequestParams',
                        'InitializeResult',
                        'InitializedNotification',
                        'JSONRPCErrorResponse',
                        'JSONRPCMessage',
                        'JSONRPCNotification',
                        'JSONRPCRequest',
                        'JSONRPCResponse',
                        'JSONRPCResultResponse',
                        'LegacyTitledEnumSchema',
                        'ListPromptsRequest',
                        'ListPromptsResult',
                        'ListResourceTemplatesRequest',
                        'ListResourceTemplatesResult',
                        'ListResourcesRequest',
                        'ListResourcesResult',
                        'ListRootsRequest',
                        'ListRootsResult',
                        'ListTasksRequest',
                        'ListTasksResult',
                        'ListToolsRequest',
                        'ListToolsResult',
                        'LoggingMessageNotification',
                        'LoggingMessageNotificationParams',
                        'ModelHint',
                        'ModelPreferences',
                        'MultiSelectEnumSchema',
                        'Notification',
                        'NotificationParams',
                        'NumberSchema',
                        'PaginatedRequest',
                        'PaginatedRequestParams',
                        'PaginatedResult',
                        'PingRequest',
                        'PrimitiveSchemaDefinition',
                        'ProgressNotification',
                        'ProgressNotificationParams',
                        'Prompt',
                        'PromptArgument',
                        'PromptListChangedNotification',
                        'PromptMessage',
                        'PromptReference',
                        'ReadResourceRequest',
                        'ReadResourceRequestParams',
                        'ReadResourceResult',
                        'RelatedTaskMetadata',
                        'Request',
                        'RequestParams',
                        'Resource',
                        'ResourceContents',
                        'ResourceLink',
                        'ResourceListChangedNotification',
                        'ResourceRequestParams',
                        'ResourceTemplate',
                        'ResourceTemplateReference',
                        'ResourceUpdatedNotification',
                        'ResourceUpdatedNotificationParams',
                        'Result',
                        'Root',
                        'RootsListChangedNotification',
                        'SamplingMessage',
                        'SamplingMessageContentBlock',
                        'ServerCapabilities',
                        'ServerNotification',
                        'ServerRequest',
                        'ServerResult',
                        'SetLevelRequest',
                        'SetLevelRequestParams',
                        'SingleSelectEnumSchema',
                        'StringSchema',
                        'SubscribeRequest',
                        'SubscribeRequestParams',
                        'Task',
                        'TaskAugmentedRequestParams',
                        'TaskMetadata',
                        'TaskStatusNotification',
                        'TaskStatusNotificationParams',
                        'TextContent',
                        'TextResourceContents',
                        'TitledMultiSelectEnumSchema',
                        'TitledSingleSelectEnumSchema',
                        'Tool',
                        'ToolAnnotations',
                        'ToolChoice',
                        'ToolExecution',
                        'ToolListChangedNotification',
                        'ToolResultContent',
                        'ToolUseContent',
                        'URLElicitationRequiredError',
                        'UnsubscribeRequest',
                        'UnsubscribeRequestParams',
                        'UntitledMultiSelectEnumSchema',
                        'UntitledSingleSelectEnumSchema',
                    ],
                    'types' => [
                        'Annotations' => '06b681c280f6d5d4e15c2af16e038a70ebf8cab7fa9bae19531b3125c7e23600',
                        'AudioContent' => '5ce1bf64c40d638fb26e70b541a9105b04fbf76cef13e0af5eb0103e64ea3e4b',
                        'BaseMetadata' => '1198169004537cee13a1c72a64290ece1bbe93f71fe621b9bb02da0d1f46ec79',
                        'BlobResourceContents' => '6ed0530eb5ed8455b39ccf926bc832366d376a740096dae7133b13afbc3c2998',
                        'BooleanSchema' => '7b4d6f829ad188dc30a95abb063d9f71f3d6bb8237e308cb10d52deb858cada1',
                        'CallToolRequest' => '0682e30e407afdccee01ae9154b819dc9b582beaf93d89d2ac33dcd5f09e5a81',
                        'CallToolRequestParams' => 'fe697eb27adaceabc4d7d97aecc99ab30dd8b179ac490061e3e3b8da99773f53',
                        'CallToolResult' => '81bfa683dc4c8a2a18638febca92835f2a436a34c74c2f6f8506d17de100de6a',
                        'CancelTaskRequest' => '173a507389b4062fb5e8bcd8849f556eee20b5ed611fc30b261f39eb6d6163bc',
                        'CancelTaskResult' => 'a0ac81e894be01dcc9fd3c7093081c49096e02fb0d2245ec4f2a4c1050a5b94a',
                        'CancelledNotification' => 'f7b0963164530e607656578e7e671b7c583d7521c825e5468626bdd8a8e0e9d0',
                        'CancelledNotificationParams' => '6f4bfab7f8882d3cabc00eb3a5d11c25a9747db2e7638612647f0119845da87c',
                        'ClientCapabilities' => '5213ffc1eab629ab0b86f14ec13d0cfa8cd68eda1de9dcc85a4d0f3f92cdfd86',
                        'ClientNotification' => '062495eaed59502f95ae2507d2923cb037203a6721037fdfd6aeeb1a0ac5e273',
                        'ClientRequest' => '25120ae1290f91b6713038686b7f1495ac0267d1e0dd1ea222814e9acea5d759',
                        'ClientResult' => '29e785376eaf520767b24793a1a72ae86d8a0bc84955bd0eb97f7f315c5fc1fe',
                        'CompleteRequest' => '30bc1535145a88fe06e5e3b636f7e756ae269df6208dd8351318733bbb40e97f',
                        'CompleteRequestParams' => '626936d9cbef8e9af527ae81cf3a8b344bb96cb18944ba9b7b83dcf1250ef505',
                        'CompleteResult' => 'c586c52751aef3b40171900fe36de1d782e136f9a2eee3f2b1c00984c602f15b',
                        'ContentBlock' => '58edbce6c09c2dc39fa280613fe09af5e3e505d4da2cf515670a9a8358661a6e',
                        'CreateMessageRequest' => 'bc8d5f7f61033da11ed1233551f833dd17074348c348364157fe443d8b44775e',
                        'CreateMessageRequestParams' => 'a7e89fe437a7b09aa709daa4778cb54239be0794fa76e51eb87483b8c070dcf7',
                        'CreateMessageResult' => '546a1f096fe349b8a4fbb54a0424a0205be466d8b16bfe18c9047937253c7cd8',
                        'CreateTaskResult' => 'a1b7d4fb9d84cfc6c43de0b806b0ef1b43592f342cadff304ac8207423ae1450',
                        'Cursor' => '9bddc1fdfb55932e41cf34e4a0bf062564cf848d3e0070d86dcd3a998fdce008',
                        'ElicitRequest' => '82a71d19afcb13219e9f8a18b3ec65f67acd96dab51450cbf26a565d6e96e87d',
                        'ElicitRequestFormParams' => '1bf4ca6ddc402d7a5722b1dff1667472b4ec7e8e99ac1526b4584eb99b157f1c',
                        'ElicitRequestParams' => '3968ccd7d06c471868fc013c31d1090055064ac79ca77e44a13fc3c043ec6dde',
                        'ElicitRequestURLParams' => '50f4b3e75734ebac318affc8bdf4a363dd383b19612ddf538fad8b8f96de5a59',
                        'ElicitResult' => '301c17a7e94ede4d119114aaffa2201a951ad78f9a65a7bb7bd216fef56848d4',
                        'ElicitationCompleteNotification' => '295e00f59cbb648ff699658ea2564e7e67ebc078878e558bfa15a0cba075904b',
                        'EmbeddedResource' => '690c4bb85d7a06c44f30755f12db0c8f52bd42afc15d717e9fbf018b47ab2f9d',
                        'EmptyResult' => '848b552c88ae7cc88524840e500ac1c760ce866d22c04a829928e8e86b669863',
                        'EnumSchema' => 'd908535bde749822e82207083cf37682b0ba799d9a1c425c4430b78b7103cd94',
                        'Error' => 'cbf08416aefdef4f7cb20a51ee569c46c8697179fc28dac04136d88cf9a86262',
                        'GetPromptRequest' => '4c512a8998ac6aaa54ba845663f65a47e2b7aae2a2b9e13fbf9c0e9e08304135',
                        'GetPromptRequestParams' => '61410b4e6b3512301f072f92a90ff5de90b0718e78784e8f384d60908fce4dd4',
                        'GetPromptResult' => '769ff700e91ecf0b0a2884ec8c7f3fdc40c243e461da27bc848a2b3d701598ac',
                        'GetTaskPayloadRequest' => '6f84ee7da3e0deeb925cb10fe5800182e1beb5f19528acb8e7db199aee34dc18',
                        'GetTaskPayloadResult' => '752c90fd455e3c6279efcb95f4f7fa8661fa577f81d23275a9dd75b843e918ec',
                        'GetTaskRequest' => '5af96c5e34bd823a6cf43bbaea2570dab3b6b3eaf958c343cda6de8a630b4981',
                        'GetTaskResult' => 'a0ac81e894be01dcc9fd3c7093081c49096e02fb0d2245ec4f2a4c1050a5b94a',
                        'Icon' => 'f01a74823018c1ee2b8d9ddfd99211b14c3c12c68cee6e80c8328baa78de4309',
                        'Icons' => '3b75b6cc8fa1c8abf3ac6e014397a5f4b5136d4d65ff5cf046337b2587a9cbcf',
                        'ImageContent' => 'df215f6b66866f63609af4d9c5a0b257c2891d64d9547ff38a0cc598dfba6841',
                        'Implementation' => 'c3abe71235fdcf042adddaefd55485a887684fbf9cad4dfb3a50667d015d3c5d',
                        'InitializeRequest' => '0435f730fdd966d579c3b5bf97eea24bdcef49313893ca34bf895992a33faee5',
                        'InitializeRequestParams' => 'b430cd158c442064ef65c9f54e26490cbb47e6d2c987b64c0e19d335e7322fec',
                        'InitializeResult' => '7d628467e39ecf4d9bacc204e1518596ef1bda879bf8f3ebee67d663e8a8f8d3',
                        'InitializedNotification' => 'b17b025c882cf210820c3cbb0016377466ec14bd70bb665d648e3cd68a1129bc',
                        'JSONRPCErrorResponse' => '08229e5261a03f670d31cbf25c192592ffbace3ae0f5218d5e9afcecb608e9bf',
                        'JSONRPCMessage' => '92dcfb3098611b01c3af44eb7980401dc946d0334495181207e91ae527245cd7',
                        'JSONRPCNotification' => '1e72e8f425f41d63fcf7dca6e27141b937a59be86cf8c4fc5c9359dac2e9e271',
                        'JSONRPCRequest' => 'f56adc0fe73b6edd8018154b634324b7106945e20ea7284c6f122d8c668690ff',
                        'JSONRPCResponse' => '899f9d97c38bdfb5d06ee49d31bef836d187238de150ede6ae2594ca902fb301',
                        'JSONRPCResultResponse' => 'e514232a0ef4ae24de54d5837f01d7cdc0c2020f7889c04cfc6df3a9ef1c7e83',
                        'LegacyTitledEnumSchema' => 'd2138088265d38716276841ed1d893f5232f7ac279a83bdbc598c305ddf33b48',
                        'ListPromptsRequest' => '0e73f320c9cdb2d2807681af31d2909f902d1b613892686932ac0b1c0fc01d7f',
                        'ListPromptsResult' => 'c1ce7f8c06ecf99e5b3dd0f1a95956005b6706da8d7416cf4886407b38af5e90',
                        'ListResourceTemplatesRequest' => 'b7aaffea8d1f42e1aa29cf7a748e4ba4739b5bd8013b49cf7ab028f35e9e8b2d',
                        'ListResourceTemplatesResult' => 'e58db7fa8eaa2110327dbc8468cf735b14ce489d2406141bb28bdf31ea58a0eb',
                        'ListResourcesRequest' => '3b0087e87a687cb76aa36a4719dbca92a93843e7e6ef1b7c441747592b4f63fe',
                        'ListResourcesResult' => '88c8ee597e23e85d29b4080e0c24ad1a436752a80ba30349358f4a7958a9f895',
                        'ListRootsRequest' => '866f467a4161e4b10ba496fb1dd69995a6924086416b6cdc8776d6d180bcb5ea',
                        'ListRootsResult' => '0d5e123a9ad82310d9a40102f3f02f307eda51cd70669a8b8eacb2a74490148f',
                        'ListTasksRequest' => '91dd5a0a0817845a9810250deeaa3cd4bf704c847dada978476570c81b749ead',
                        'ListTasksResult' => 'aec911e218b1f2c37ab192d0b916619a92981ffc772d2bbfd502abc9e75205df',
                        'ListToolsRequest' => '5a6cb1683f693e8ce7b06ab2c2090bb0948c4213f89b901b07bf1c54e7a73098',
                        'ListToolsResult' => '9be779c5ee4ff7cb68ae05fbf09c675f491b740059e0c18a44c6f53c879888c2',
                        'LoggingLevel' => '25241836c35f79aa2215f3e07dfe1f2b8318a09a5c6c9e86b8fe3af31ab7fc5e',
                        'LoggingMessageNotification' => '94f08740a82ec1d850f594570bc77f95bdaec03c74dc7544d5be072109b06795',
                        'LoggingMessageNotificationParams' => 'b11013bed05ebbe3ccd84861c0987bb5dec98f78f10b551d73929a749867e614',
                        'ModelHint' => '360ace9e4a4a109ff81b2b12727502c85cd68b2ba60f1efc3afccf92f6c335ad',
                        'ModelPreferences' => '50cfdd75a1f62d3d8290f34a5507afd41d9bcc3e6158bab469513d4892611ed5',
                        'MultiSelectEnumSchema' => 'aa252d98219442a18b7fde9e042ea5a166ff1d1e368b8b16f5ca8ee586721e90',
                        'Notification' => '4318f0dcbd6026298fdd4de241f25d8b6494f7cc03947d4efe63b34dfb8c2c8f',
                        'NotificationParams' => '7b557ebe6667b288c0df12f3d48244f58baa45d5e3441a8ffecf0297ed029daf',
                        'NumberSchema' => '5f5a6c0edbd3e3253f8d491614580ede44dc9445fe00fe23cb89ceaf7ae4ffb2',
                        'PaginatedRequest' => 'e1fc6b4953384af5ab4c24560bc6b4e1916ed10f2ba38479e42008ad42e0800f',
                        'PaginatedRequestParams' => '429254f94eb268c77e18f487bf1cdc17285a03a46fcd1f45cb11e8914c78d969',
                        'PaginatedResult' => 'eb3b2d980723dae6246e278d618e1b2380f8ac1e62e9c77e197fae71b7c1e7ce',
                        'PingRequest' => '0f4af5fa59be04f97e378df8664202b088853d17b2e732592048419c85f452e9',
                        'PrimitiveSchemaDefinition' => '45b24d375421a6339075c1718183bc346f08b56aacf239b663b01439e1448254',
                        'ProgressNotification' => '95227a44893d5eaf02ea90425c86c6e6bcbe4f4e424f9465bcce1884135bcecb',
                        'ProgressNotificationParams' => '0ecf1f75aeb465760305167b4cb0ec3b88bfceae18fe8b1f715053514b66191c',
                        'ProgressToken' => '725d88eafeb72df891575802199de52f6629c541ae90851f7fbce23b773e0dc5',
                        'Prompt' => '906fb72164ab2e62c74994d7a9e36d266c7fbcfb6dace66328e9127d988058bd',
                        'PromptArgument' => 'b517a61dfcf0525ac412c8c01459c96dfa3d72c3bc5d003db404d72e551cc801',
                        'PromptListChangedNotification' => '41a8d446a77f218768f2800682cbc4b9cfdfc2cb9a2ba8cccc126e659b1c2a61',
                        'PromptMessage' => '361c7a3ef14691b83d8c228b311eccf6a2c10e1f66627252d9f29023923f7005',
                        'PromptReference' => 'f2c9a5740f2e04b30ce0b8cf1cb1fc2e90289d5028786c6fdf895c20e3fb16d1',
                        'ReadResourceRequest' => '54ca6636b0bd81c3b890263c077f1642a76477aea0e3cf3fa45987c95aeaee6f',
                        'ReadResourceRequestParams' => '401466ba2f1d38a56e98414806d23cc73de3177b4bfa401f614e018953c4c9cf',
                        'ReadResourceResult' => 'd62229b8f797a00ce38c07749a171c7c625332a0943153cade2f48c32441ebd9',
                        'RelatedTaskMetadata' => 'ac22447f4613a47c93b2f5e660bb856dd9e77011aa130f7d3c7f3c615e3f9a9c',
                        'Request' => '4318f0dcbd6026298fdd4de241f25d8b6494f7cc03947d4efe63b34dfb8c2c8f',
                        'RequestId' => '725d88eafeb72df891575802199de52f6629c541ae90851f7fbce23b773e0dc5',
                        'RequestParams' => 'ab8806b20249e6b91b98231da341df47fc019269f4c9af7f51a36f6f8ffec155',
                        'Resource' => 'baefbbdbba0a3354b735583fc9b5b8247c28dcc07a4e953dfe8d38aa4de4e5b2',
                        'ResourceContents' => 'bd1d6f7e224c0e7f9390b276abbb7d1653e996a942bdf5b6e7a2f00817c5397d',
                        'ResourceLink' => 'a01b5badd56aff721d35d81c8d20df1e263a473f4faab8eaba7fd8a24f82eb8b',
                        'ResourceListChangedNotification' => '68f37295e42b0448d455dcba49b1b37f4e3919bfb2cc07738f3f2ba46177c654',
                        'ResourceRequestParams' => '02a785e52c4ded35f38d7e8022ce631717679e387d93c3bfd380ef993e010719',
                        'ResourceTemplate' => '54cb8fe437ac131d47102487c921e8eb100befbdf18a080a7b1012a3785606f0',
                        'ResourceTemplateReference' => 'ef2d78e131efbe00b3a37ae5346d4c12dbd795eeedf85a96c24244caba5d6763',
                        'ResourceUpdatedNotification' => 'aebff34739361d6f6af193ca318cd6daff3f21cd4458de7a9e02d1b00a6e46ba',
                        'ResourceUpdatedNotificationParams' => '2c53786b4677151108cecaef442de4ede73143c767ab82ada7e5dc3e3ac5a29d',
                        'Result' => 'b9a132338083bcffef2866a03a780bf2365593a14218189d421a218f76a9f5a2',
                        'Role' => '8f1a0a87efa7c60e206208553407418b0dc8386bfc0a1104554876f13d48dafc',
                        'Root' => '37ad14d1634ad77cfb57f096836a3903aef498328ed15fe4fa4f163e61333448',
                        'RootsListChangedNotification' => 'f7b3459d22977703a7a5b239f62224a677bccf869070097cb60080fac0ea096e',
                        'SamplingMessage' => '2785806074e1ca3f943c78507f902cc7415c03530765dfee4ac25a6277a69456',
                        'SamplingMessageContentBlock' => 'a7fafa6aa20355815aa3c3fcf7f09c759d05a59df68a962ad645aae07d6d966c',
                        'ServerCapabilities' => '30ce0f971a9e9ccd492196072b348fe07b7ab83d00e77584b05d7e38a695a0cb',
                        'ServerNotification' => '7a58596cfa5e869521d64bf89c24bf747c05685f72c6c272d76ebe0c3f4eea3d',
                        'ServerRequest' => '8951ff962222fed6abb39324bca1a70c8feef6029ed04c0fe26e31964e820b5a',
                        'ServerResult' => 'd95bbf0d66b4c391767525976d8bdf940a8c2b8f3c6e0e13995bec5df5cc1051',
                        'SetLevelRequest' => 'd3295fd0e0479d2b738f616e5374b820efd3266029af2c8e5f4244a123da347d',
                        'SetLevelRequestParams' => '93b88bf767ed7bec83e37407c565eb98f2c4fe37a339008b784f6d1a601d7028',
                        'SingleSelectEnumSchema' => '0ddf3de2710bc15f516a76eb2674d3d0f9d829ad9d8e7f0cb6825da0e0399522',
                        'StringSchema' => '869a7defca74b305452361c008db01797e22730ea8eaaf067acfd7111cf71349',
                        'SubscribeRequest' => 'ba54cbde50aa10c8c7b7fb87cb8b223c7edef27ea24750ab31c6b831c57f8771',
                        'SubscribeRequestParams' => '401466ba2f1d38a56e98414806d23cc73de3177b4bfa401f614e018953c4c9cf',
                        'Task' => 'f1a072579db118c0dd9d335f537df3d19bea23c95661c40808f76c246e17ddcc',
                        'TaskAugmentedRequestParams' => 'b7226dad87dd3327dccb852d3b9ef31feeae46b2c9933242d0089d7fc3c26a36',
                        'TaskMetadata' => '94416d54ea8e331c69f806303181fed040fe33d4b0e261dff88770bd081ee2f7',
                        'TaskStatus' => '692c8f9ae10b4a377b66af309abf469a25f4e77f24f9f14cf94e3bd4e2da77f6',
                        'TaskStatusNotification' => '5210c5cc12ec8098ad895319a731f8e0046b10beac53e0359d7505c48bdab114',
                        'TaskStatusNotificationParams' => 'f7d926795300c12074321bb163a7fd6f9a0c27d4e7ad345d6182ac0837fcd69b',
                        'TextContent' => '1467e2d3876a36a1aee3f53590a928100c614edb7c8c5ecfe12a5c68b414fff7',
                        'TextResourceContents' => 'b5297a7fdf74b3a8b5d55320db910a7a99bdddb2041ced2fdd79c3a20f49248e',
                        'TitledMultiSelectEnumSchema' => '785b579babed86ceb5d4c05cc9a7dae9cd824a47c7b1e9a7d1ff9be09d7c9ecf',
                        'TitledSingleSelectEnumSchema' => 'f61721d0522776066beda66b637b3b5d22739ad191a0b75082a97c980e4f1fd2',
                        'Tool' => '434fc25a0d1b570c19298479aa93958d32a3810f8dce3eef0439147884641cab',
                        'ToolAnnotations' => '499ac59976c772fa2945117a16e26e2f9eb30bbb2a7e6374e51b683936220f53',
                        'ToolChoice' => 'b0a19f4ebe08bc4df2bdfc2b55d2005ff15abf8f1d3abde6da50fdbe02db5157',
                        'ToolExecution' => '039748ac3152d7e2f4ded61c630ad55b830ee7144f9adb2189fa7bc34a02840c',
                        'ToolListChangedNotification' => 'a4873140d90e637032ac6b207ee0d86da75a62cb41aa094778fe6a74c9a6abd3',
                        'ToolResultContent' => '038a1cabbaa71a2048730dba03065c24b55590d50ba8c54e773f79a5baa267bc',
                        'ToolUseContent' => 'e3e1c4dc09adc34b4f43d055e8a3cfc9d8a688fdd97b4908e91fa81bd19d0516',
                        'URLElicitationRequiredError' => '00eebe9a38c6d244c4026d8b931025858099a8e49b5300247b4025e41722f03b',
                        'UnsubscribeRequest' => 'd3e7acef8998c45e036cd100d7777956a0e9faa6437ac73e6a2954a150cb0880',
                        'UnsubscribeRequestParams' => '401466ba2f1d38a56e98414806d23cc73de3177b4bfa401f614e018953c4c9cf',
                        'UntitledMultiSelectEnumSchema' => 'fb5359f6094f88f4d8f3574d7e82bfe09a649895be68bde3aa2cdaadb9a2c2ae',
                        'UntitledSingleSelectEnumSchema' => 'a58107b851d812af49b2f4c508615f3ce5132383d72ead761855709dc6562b05',
                    ],
                ],
                '2026-07-28' => [
                    'fingerprint' => 'c271b0d338d7f9b2109acff0cab8b005a992e919335ed9ca4741c72ff8a8a092',
                    'roots' => [
                        'Annotations',
                        'AudioContent',
                        'BaseMetadata',
                        'BlobResourceContents',
                        'BooleanSchema',
                        'CacheableResult',
                        'CallToolRequest',
                        'CallToolRequestParams',
                        'CallToolResult',
                        'CallToolResultResponse',
                        'CancelledNotification',
                        'CancelledNotificationParams',
                        'ClientCapabilities',
                        'ClientNotification',
                        'ClientRequest',
                        'ClientResult',
                        'CompleteRequest',
                        'CompleteRequestParams',
                        'CompleteResult',
                        'CompleteResultResponse',
                        'ContentBlock',
                        'CreateMessageRequest',
                        'CreateMessageRequestParams',
                        'CreateMessageResult',
                        'DiscoverRequest',
                        'DiscoverResult',
                        'DiscoverResultResponse',
                        'ElicitRequest',
                        'ElicitRequestFormParams',
                        'ElicitRequestParams',
                        'ElicitRequestURLParams',
                        'ElicitResult',
                        'EmbeddedResource',
                        'EmptyResult',
                        'EnumSchema',
                        'Error',
                        'GetPromptRequest',
                        'GetPromptRequestParams',
                        'GetPromptResult',
                        'GetPromptResultResponse',
                        'HeaderMismatchError',
                        'Icon',
                        'Icons',
                        'ImageContent',
                        'Implementation',
                        'InputRequest',
                        'InputRequests',
                        'InputRequiredResult',
                        'InputResponse',
                        'InputResponseRequestParams',
                        'InputResponses',
                        'InternalError',
                        'InvalidParamsError',
                        'InvalidRequestError',
                        'JSONObject',
                        'JSONRPCErrorResponse',
                        'JSONRPCMessage',
                        'JSONRPCNotification',
                        'JSONRPCRequest',
                        'JSONRPCResponse',
                        'JSONRPCResultResponse',
                        'LegacyTitledEnumSchema',
                        'ListPromptsRequest',
                        'ListPromptsResult',
                        'ListPromptsResultResponse',
                        'ListResourceTemplatesRequest',
                        'ListResourceTemplatesResult',
                        'ListResourceTemplatesResultResponse',
                        'ListResourcesRequest',
                        'ListResourcesResult',
                        'ListResourcesResultResponse',
                        'ListRootsRequest',
                        'ListRootsResult',
                        'ListToolsRequest',
                        'ListToolsResult',
                        'ListToolsResultResponse',
                        'LoggingMessageNotification',
                        'LoggingMessageNotificationParams',
                        'MetaObject',
                        'MethodNotFoundError',
                        'MissingRequiredClientCapabilityError',
                        'ModelHint',
                        'ModelPreferences',
                        'MultiSelectEnumSchema',
                        'Notification',
                        'NotificationMetaObject',
                        'NotificationParams',
                        'NumberSchema',
                        'PaginatedRequest',
                        'PaginatedRequestParams',
                        'PaginatedResult',
                        'ParseError',
                        'PrimitiveSchemaDefinition',
                        'ProgressNotification',
                        'ProgressNotificationParams',
                        'Prompt',
                        'PromptArgument',
                        'PromptListChangedNotification',
                        'PromptMessage',
                        'PromptReference',
                        'ReadResourceRequest',
                        'ReadResourceRequestParams',
                        'ReadResourceResult',
                        'ReadResourceResultResponse',
                        'Request',
                        'RequestMetaObject',
                        'RequestParams',
                        'Resource',
                        'ResourceContents',
                        'ResourceLink',
                        'ResourceListChangedNotification',
                        'ResourceRequestParams',
                        'ResourceTemplate',
                        'ResourceTemplateReference',
                        'ResourceUpdatedNotification',
                        'ResourceUpdatedNotificationParams',
                        'Result',
                        'ResultMetaObject',
                        'Root',
                        'SamplingMessage',
                        'SamplingMessageContentBlock',
                        'ServerCapabilities',
                        'ServerNotification',
                        'ServerResult',
                        'SingleSelectEnumSchema',
                        'StringSchema',
                        'SubscriptionFilter',
                        'SubscriptionsAcknowledgedNotification',
                        'SubscriptionsAcknowledgedNotificationParams',
                        'SubscriptionsListenRequest',
                        'SubscriptionsListenRequestParams',
                        'SubscriptionsListenResult',
                        'SubscriptionsListenResultMetaObject',
                        'SubscriptionsListenResultResponse',
                        'TextContent',
                        'TextResourceContents',
                        'TitledMultiSelectEnumSchema',
                        'TitledSingleSelectEnumSchema',
                        'Tool',
                        'ToolAnnotations',
                        'ToolChoice',
                        'ToolListChangedNotification',
                        'ToolResultContent',
                        'ToolUseContent',
                        'UnsupportedProtocolVersionError',
                        'UntitledMultiSelectEnumSchema',
                        'UntitledSingleSelectEnumSchema',
                    ],
                    'types' => [
                        'Annotations' => '06b681c280f6d5d4e15c2af16e038a70ebf8cab7fa9bae19531b3125c7e23600',
                        'AudioContent' => '523b0717d5565753f9bec8260de70efc6dd26c2d8249fec9a0419e8cc41a95da',
                        'BaseMetadata' => '1198169004537cee13a1c72a64290ece1bbe93f71fe621b9bb02da0d1f46ec79',
                        'BlobResourceContents' => '6ed0530eb5ed8455b39ccf926bc832366d376a740096dae7133b13afbc3c2998',
                        'BooleanSchema' => '7b4d6f829ad188dc30a95abb063d9f71f3d6bb8237e308cb10d52deb858cada1',
                        'CacheableResult' => '566bb526abf9b5f8c02178a278a26a10aa0797dbb8af88a05598f612c8dcb82f',
                        'CallToolRequest' => '0682e30e407afdccee01ae9154b819dc9b582beaf93d89d2ac33dcd5f09e5a81',
                        'CallToolRequestParams' => '23040a3ad89efb6f6ed4253cd298aa26c5e7c2642471f6fcd2ee1f4ca06fc1ae',
                        'CallToolResult' => '3ba037cd7c67dbe690ac262101c26acd9e3b40e404536eac92e9e1ea0273856c',
                        'CallToolResultResponse' => '51abcabd577addabdee1910403da49b498cb92e73d9ba43222183f3748d08d9c',
                        'CancelledNotification' => 'f7b0963164530e607656578e7e671b7c583d7521c825e5468626bdd8a8e0e9d0',
                        'CancelledNotificationParams' => '90c52f4cfe798a69d436c2b67fefb852f61e0eb3bef1ff0d5f0a3162bddcbde2',
                        'ClientCapabilities' => 'a2c59aaeaae253dc7ad831854bd4dc94ace52d820fc1cff8597b01062e5362fc',
                        'ClientNotification' => '8bfaef09126f5cd5d74ee74fa32d8623e62bc3bd50788886902c603c570d2052',
                        'ClientRequest' => 'b3227810c5f840253486f6a9ae273faefbe6bb8cb8e4d3989a1cb16639aae914',
                        'ClientResult' => 'e4bb4b42445382237876d16670531f2ab9eb197f1994836decc5d7f9955d42e3',
                        'CompleteRequest' => '30bc1535145a88fe06e5e3b636f7e756ae269df6208dd8351318733bbb40e97f',
                        'CompleteRequestParams' => '626936d9cbef8e9af527ae81cf3a8b344bb96cb18944ba9b7b83dcf1250ef505',
                        'CompleteResult' => 'c586c52751aef3b40171900fe36de1d782e136f9a2eee3f2b1c00984c602f15b',
                        'CompleteResultResponse' => '23c09b678529cff02e622d5c13cfb881beff2a52629125b73eb099fef082e1de',
                        'ContentBlock' => '58edbce6c09c2dc39fa280613fe09af5e3e505d4da2cf515670a9a8358661a6e',
                        'CreateMessageRequest' => '4401359e729b31cebede1ac81e593d182794ffef82039c164da5777e0e37f2c3',
                        'CreateMessageRequestParams' => '45153f59f307c9cf31c05c364f254b6362c433944e73b99e8fc9cbf02fbb5623',
                        'CreateMessageResult' => 'f9001327ea8c1b652d3d92178b6303c649d2319ca0a1967f2b68e3dc7aa27942',
                        'Cursor' => '9bddc1fdfb55932e41cf34e4a0bf062564cf848d3e0070d86dcd3a998fdce008',
                        'DiscoverRequest' => '81f76b626317b6c25c251f91fd3b050c465eb95df3e3ce3df279926565455458',
                        'DiscoverResult' => '095c7450c0e1763364b1ddc01fb775fe32d05b2a483ac58cd925125b9c8fcd3a',
                        'DiscoverResultResponse' => 'db7a6d10a8cb4be19b3228461f0634568c747f79d6122e93780980397587fbdc',
                        'ElicitRequest' => 'bdaa6d447221b0cf0a42c56eb1ca3378e3ac48ccfed5040a1f3a14fcb70d1c37',
                        'ElicitRequestFormParams' => '5497b0290d183bec6460a81ec04661a3f6ac3739741f23c97a38be4b52535e8e',
                        'ElicitRequestParams' => '3968ccd7d06c471868fc013c31d1090055064ac79ca77e44a13fc3c043ec6dde',
                        'ElicitRequestURLParams' => 'edb123f0f494e6a9fe6763130f096763bdff0e4772f88b077ef7ef8b4f59f3ed',
                        'ElicitResult' => '22010e063c633e68bcd6d5295ce1d17abff40ea946cc60d449196dd3efe70cc3',
                        'EmbeddedResource' => '02c2ea676c3cdec865ce281934522c7c3c5b622ac6eb61f2cc1006b0f111f35e',
                        'EmptyResult' => '848b552c88ae7cc88524840e500ac1c760ce866d22c04a829928e8e86b669863',
                        'EnumSchema' => 'd908535bde749822e82207083cf37682b0ba799d9a1c425c4430b78b7103cd94',
                        'Error' => 'cbf08416aefdef4f7cb20a51ee569c46c8697179fc28dac04136d88cf9a86262',
                        'GetPromptRequest' => '4c512a8998ac6aaa54ba845663f65a47e2b7aae2a2b9e13fbf9c0e9e08304135',
                        'GetPromptRequestParams' => '20b5ed5b30889e268d3919da448eab52f7ee0e29650744326c5892a948ec7055',
                        'GetPromptResult' => '769ff700e91ecf0b0a2884ec8c7f3fdc40c243e461da27bc848a2b3d701598ac',
                        'GetPromptResultResponse' => '35a3d3d66e0154666946d406382b171ed4ac967078d9c10ee3e98a5d39e8efb6',
                        'HeaderMismatchError' => 'b8aa317ddaaca339b65e386d1b43e4539669f4c61bee7d7db031b04fb49f0d2f',
                        'Icon' => 'f01a74823018c1ee2b8d9ddfd99211b14c3c12c68cee6e80c8328baa78de4309',
                        'Icons' => '3b75b6cc8fa1c8abf3ac6e014397a5f4b5136d4d65ff5cf046337b2587a9cbcf',
                        'ImageContent' => '34e00c9f48330725a119d354a3e0eab63b67fa2f642a96bafd89880bd929db29',
                        'Implementation' => 'c3abe71235fdcf042adddaefd55485a887684fbf9cad4dfb3a50667d015d3c5d',
                        'InputRequest' => '144965b224ec7329f8dc380d660c2e23271180e766c4e71d177e5755a332cea3',
                        'InputRequests' => '245e460e4769be5504d54b789c0ad3b13351bb339b3a320e4740b9d94a7ed431',
                        'InputRequiredResult' => '1d4a1ba22f169f0c315499627a4de83e40812b51053e9cee23d268fd1220cfc4',
                        'InputResponse' => 'd2df755d73cf75af72f6df8591f31518f700847ba3f81ad06f28d80b07ed91d8',
                        'InputResponseRequestParams' => '2dae2d6778bc00bab2d0ca6c6474d8d199b9ffea2a78d0f6f8de16d090cb1963',
                        'InputResponses' => '9e72a946898b43ed2aee4d01f778f7d0cc62d81e2a34d6660cc504dd117074e6',
                        'InternalError' => 'aca4b5d615eb62df0d6ed4582139db992b6a46ffeba09837591677a4c318073b',
                        'InvalidParamsError' => '157f385956ed26a38437f61731daeeb8044c13df7fedb553f2eeb4f6a5a297f0',
                        'InvalidRequestError' => '84d9e9d061a730fea182f1ef0c14ff78e74407923c888924718e0fa083bf6f38',
                        'JSONArray' => '305bd3074a7d818adffcfc57516791fc8b477fea64910bc02578cb2a65df7ead',
                        'JSONObject' => 'b595eec3e3855073264ee5e3b32a4a57b54974781cfdb0b4b4656c8663eb21ec',
                        'JSONRPCErrorResponse' => '08229e5261a03f670d31cbf25c192592ffbace3ae0f5218d5e9afcecb608e9bf',
                        'JSONRPCMessage' => '92dcfb3098611b01c3af44eb7980401dc946d0334495181207e91ae527245cd7',
                        'JSONRPCNotification' => '1e72e8f425f41d63fcf7dca6e27141b937a59be86cf8c4fc5c9359dac2e9e271',
                        'JSONRPCRequest' => 'f56adc0fe73b6edd8018154b634324b7106945e20ea7284c6f122d8c668690ff',
                        'JSONRPCResponse' => '899f9d97c38bdfb5d06ee49d31bef836d187238de150ede6ae2594ca902fb301',
                        'JSONRPCResultResponse' => 'e514232a0ef4ae24de54d5837f01d7cdc0c2020f7889c04cfc6df3a9ef1c7e83',
                        'JSONValue' => 'c37bdb945be74165cb365903afadb87e2b02da02605959588a9ec5c8021d6ced',
                        'LegacyTitledEnumSchema' => 'd2138088265d38716276841ed1d893f5232f7ac279a83bdbc598c305ddf33b48',
                        'ListPromptsRequest' => '0e73f320c9cdb2d2807681af31d2909f902d1b613892686932ac0b1c0fc01d7f',
                        'ListPromptsResult' => '77ff7357ce669b24231945f3e99de044d00749e259c274606548f8e781585f94',
                        'ListPromptsResultResponse' => '641cce8bbf27ea0b198ab81e2f472c3efdbac5e83a069d1675816a774e13ce32',
                        'ListResourceTemplatesRequest' => 'b7aaffea8d1f42e1aa29cf7a748e4ba4739b5bd8013b49cf7ab028f35e9e8b2d',
                        'ListResourceTemplatesResult' => 'c8b68584586f7c72fd8291a2b94d456e0bd4b500f76b9755c46337d3f63453cd',
                        'ListResourceTemplatesResultResponse' => 'c1ef1eaafb13ce513292da2be3ed7bef04bbf0264d96081d47f4d04a821ccd37',
                        'ListResourcesRequest' => '3b0087e87a687cb76aa36a4719dbca92a93843e7e6ef1b7c441747592b4f63fe',
                        'ListResourcesResult' => 'd57e88d443bd49c8df230161c445a6107e7ddf414c5ca1ee6e353c999dd44c71',
                        'ListResourcesResultResponse' => '3170b223f1084c6e9ed6041e54ec12e011631db497b6ad65640d279435d0bdd3',
                        'ListRootsRequest' => '818e18e1f1222e4f0ae8149bed7b805afc4276c5f3ce1f4428eb11e940a18c8b',
                        'ListRootsResult' => '1382f8fac6f98093f262114aa7c940cf1d7fcadd60a121868a85d7a4b46a9fde',
                        'ListToolsRequest' => '5a6cb1683f693e8ce7b06ab2c2090bb0948c4213f89b901b07bf1c54e7a73098',
                        'ListToolsResult' => '86b653ca1f90aa1a4e2ecf9c996eac446dbdc506089c5673dd39e2fa1e14a38b',
                        'ListToolsResultResponse' => '226dcd5648135361b96230cb96e84345b6ab9f55c446ef76dd714d129f195a11',
                        'LoggingLevel' => '25241836c35f79aa2215f3e07dfe1f2b8318a09a5c6c9e86b8fe3af31ab7fc5e',
                        'LoggingMessageNotification' => '94f08740a82ec1d850f594570bc77f95bdaec03c74dc7544d5be072109b06795',
                        'LoggingMessageNotificationParams' => 'b11013bed05ebbe3ccd84861c0987bb5dec98f78f10b551d73929a749867e614',
                        'MetaObject' => '7718f5ae79c70b7968cffca1dc89fe768646af27c1c7e67f28f755990f197123',
                        'MethodNotFoundError' => '12d9c00d75ac7bc1aedd449ba900507349d62867be6efdfc891e481132e80811',
                        'MissingRequiredClientCapabilityError' => 'e8de88e3a6c048f0ea951482b4d5384009dca3a8d00bb854adf9d36e3725680a',
                        'ModelHint' => '360ace9e4a4a109ff81b2b12727502c85cd68b2ba60f1efc3afccf92f6c335ad',
                        'ModelPreferences' => '50cfdd75a1f62d3d8290f34a5507afd41d9bcc3e6158bab469513d4892611ed5',
                        'MultiSelectEnumSchema' => 'aa252d98219442a18b7fde9e042ea5a166ff1d1e368b8b16f5ca8ee586721e90',
                        'Notification' => '4318f0dcbd6026298fdd4de241f25d8b6494f7cc03947d4efe63b34dfb8c2c8f',
                        'NotificationMetaObject' => 'b7f349e4964481f431b2c24e25c9b6aef09a84bc1e8bed361b27181363a05eb1',
                        'NotificationParams' => '41d31dd5fba7c58df47ec424cad112394670a22a29d28438040c9cba1bb5acbd',
                        'NumberSchema' => '5f5a6c0edbd3e3253f8d491614580ede44dc9445fe00fe23cb89ceaf7ae4ffb2',
                        'PaginatedRequest' => '0336d95611a4ad09b0162dde45e3c0ae1f26ea2342481c3175ec74d40571d16d',
                        'PaginatedRequestParams' => '429254f94eb268c77e18f487bf1cdc17285a03a46fcd1f45cb11e8914c78d969',
                        'PaginatedResult' => 'eb3b2d980723dae6246e278d618e1b2380f8ac1e62e9c77e197fae71b7c1e7ce',
                        'ParseError' => '833d58a25ad9bb04491fca9ab61f413caff3c32fc03378491dd0923e29a30eed',
                        'PrimitiveSchemaDefinition' => '45b24d375421a6339075c1718183bc346f08b56aacf239b663b01439e1448254',
                        'ProgressNotification' => '95227a44893d5eaf02ea90425c86c6e6bcbe4f4e424f9465bcce1884135bcecb',
                        'ProgressNotificationParams' => '0ecf1f75aeb465760305167b4cb0ec3b88bfceae18fe8b1f715053514b66191c',
                        'ProgressToken' => '725d88eafeb72df891575802199de52f6629c541ae90851f7fbce23b773e0dc5',
                        'Prompt' => '24c2b2e05f05235de1f6ac6aab05233cac676bd79b5355e5ecab054e6eb67656',
                        'PromptArgument' => 'b517a61dfcf0525ac412c8c01459c96dfa3d72c3bc5d003db404d72e551cc801',
                        'PromptListChangedNotification' => '41a8d446a77f218768f2800682cbc4b9cfdfc2cb9a2ba8cccc126e659b1c2a61',
                        'PromptMessage' => '361c7a3ef14691b83d8c228b311eccf6a2c10e1f66627252d9f29023923f7005',
                        'PromptReference' => 'f2c9a5740f2e04b30ce0b8cf1cb1fc2e90289d5028786c6fdf895c20e3fb16d1',
                        'ReadResourceRequest' => '54ca6636b0bd81c3b890263c077f1642a76477aea0e3cf3fa45987c95aeaee6f',
                        'ReadResourceRequestParams' => 'd3d68fbb283a777f1d3c8a50dab389e2b598301423447325f0cc86260cab7f11',
                        'ReadResourceResult' => 'fd8c267e0ce9d9d847ed953dadf7bf209bb9a6fe6f0537c1b82a598d1bf92a0b',
                        'ReadResourceResultResponse' => '8d91ac57bface15cf61cb2835536b3f065e7553f73196f7560ae514ba72c2ba2',
                        'Request' => '4318f0dcbd6026298fdd4de241f25d8b6494f7cc03947d4efe63b34dfb8c2c8f',
                        'RequestId' => '725d88eafeb72df891575802199de52f6629c541ae90851f7fbce23b773e0dc5',
                        'RequestMetaObject' => 'a3270d527f86da39141d77600ecd3d5112048069ba70672aadd30494c4ecc5db',
                        'RequestParams' => '72133c4790109a76018ceeba83b83e26ec15ab5386c1901d566ea16a7e102400',
                        'Resource' => '24dd5d2401798a0fcc27b3a39729aecb935852a5bd1bf91133a13bdeaf9de4c0',
                        'ResourceContents' => 'f9ec6ef233f8cad733ebe004793c40af89ee1c7b19ea1d79757fe58b95e8e11a',
                        'ResourceLink' => 'a01b5badd56aff721d35d81c8d20df1e263a473f4faab8eaba7fd8a24f82eb8b',
                        'ResourceListChangedNotification' => '68f37295e42b0448d455dcba49b1b37f4e3919bfb2cc07738f3f2ba46177c654',
                        'ResourceRequestParams' => '02a785e52c4ded35f38d7e8022ce631717679e387d93c3bfd380ef993e010719',
                        'ResourceTemplate' => '184774a364cde4a4fd595b7d60d55047cc50af153525e1f9b8e67a1b600a70ce',
                        'ResourceTemplateReference' => 'ef2d78e131efbe00b3a37ae5346d4c12dbd795eeedf85a96c24244caba5d6763',
                        'ResourceUpdatedNotification' => 'aebff34739361d6f6af193ca318cd6daff3f21cd4458de7a9e02d1b00a6e46ba',
                        'ResourceUpdatedNotificationParams' => '2c53786b4677151108cecaef442de4ede73143c767ab82ada7e5dc3e3ac5a29d',
                        'Result' => '8bbc9f2aa7a1a69e7788a2e901fc12f1cb33c27fc520dd5af9a37ded28ab271c',
                        'ResultMetaObject' => '9595862a6acd88e8c27901b536b5ae2aa21b038ad21ab5cdd2264a7e651aa973',
                        'ResultType' => '0a7cf6d688dd548cbbdd452d63523ce7a91ea0765a328563724e1db2531cb385',
                        'Role' => '8f1a0a87efa7c60e206208553407418b0dc8386bfc0a1104554876f13d48dafc',
                        'Root' => 'ad5e0649ce479184bbcd9a1aca2e64408aa41b0ad55e38d1d15b0c7f3b31af8f',
                        'SamplingMessage' => '0ba77fb802ae20b0d5bf227a0ee2d77ddb82a54b56995a23c4000586feae61e3',
                        'SamplingMessageContentBlock' => 'a7fafa6aa20355815aa3c3fcf7f09c759d05a59df68a962ad645aae07d6d966c',
                        'ServerCapabilities' => 'aa80f54eec259a269eba8833dce28598f597997abe756764ea9fc72f9c4bccdd',
                        'ServerNotification' => 'e54e09d37f54628e27867274f64d92d0ac03ad0accc0d4ce50b765ca0545c532',
                        'ServerResult' => '32616a0089cb87a3f0a98b951fea12b83b593ab6966d3527e10e32ecf0ef9474',
                        'SingleSelectEnumSchema' => '0ddf3de2710bc15f516a76eb2674d3d0f9d829ad9d8e7f0cb6825da0e0399522',
                        'StringSchema' => '869a7defca74b305452361c008db01797e22730ea8eaaf067acfd7111cf71349',
                        'SubscriptionFilter' => '09e61292f1fe2be0cd8ae007fc1a21c9b573d71f5ceb5d1c86c206b7ddee4b9e',
                        'SubscriptionsAcknowledgedNotification' => 'a668dd1382bd356b50b60223c41adb70d843ece7e53d91a19ad32d853169142c',
                        'SubscriptionsAcknowledgedNotificationParams' => '55ef98235c635e09ee04e9dc9ea65bf94f4cd94fb136e474739b4484c17b087b',
                        'SubscriptionsListenRequest' => 'c17a130d00b6eb43ec07c125fd5e4f897941ee34efe20dfe8aed270a8d252279',
                        'SubscriptionsListenRequestParams' => 'b44ff99e73b003533a8c8cdcb1d6e395cdfbfb4076f86a054363635fe705bbb8',
                        'SubscriptionsListenResult' => '6664f3cb433836877cc82fba57ced43a4ed7b96e24217bed5d1cadea75618256',
                        'SubscriptionsListenResultMetaObject' => '703a7b4530cfb40973761b2121d16f0213c5c2971a16699001f3e3d986512222',
                        'SubscriptionsListenResultResponse' => '038f22010a85461e704b31831d9029915c2a4213972522f9ec370e23e5d1dca9',
                        'TextContent' => '3f30e7283c0e3fd9063cbf310486b235c3b316d8dd92bfc7e9a1f7d8c7c8260c',
                        'TextResourceContents' => 'b5297a7fdf74b3a8b5d55320db910a7a99bdddb2041ced2fdd79c3a20f49248e',
                        'TitledMultiSelectEnumSchema' => '785b579babed86ceb5d4c05cc9a7dae9cd824a47c7b1e9a7d1ff9be09d7c9ecf',
                        'TitledSingleSelectEnumSchema' => 'f61721d0522776066beda66b637b3b5d22739ad191a0b75082a97c980e4f1fd2',
                        'Tool' => '5a34b2a6b75b91757220871c97853878957bae332bac433a64ef6492bc594830',
                        'ToolAnnotations' => '499ac59976c772fa2945117a16e26e2f9eb30bbb2a7e6374e51b683936220f53',
                        'ToolChoice' => 'b0a19f4ebe08bc4df2bdfc2b55d2005ff15abf8f1d3abde6da50fdbe02db5157',
                        'ToolListChangedNotification' => 'a4873140d90e637032ac6b207ee0d86da75a62cb41aa094778fe6a74c9a6abd3',
                        'ToolResultContent' => '026e1d328f378957cdd6754d14f255205ba41d716f13175af75703edf3e7914c',
                        'ToolUseContent' => '5830749c2c7b93efbe2ef8e5067271de94493be507d3ec9a12216271615b040b',
                        'UnsupportedProtocolVersionError' => '03fe9291179adc130ada0c76c50c383d64704c155296df3b5fe137f65b3dd2f3',
                        'UntitledMultiSelectEnumSchema' => 'fb5359f6094f88f4d8f3574d7e82bfe09a649895be68bde3aa2cdaadb9a2c2ae',
                        'UntitledSingleSelectEnumSchema' => 'a58107b851d812af49b2f4c508615f3ce5132383d72ead761855709dc6562b05',
                    ],
                ],
            ];
        }
        return self::$manifestCache;
    }

    private function __construct()
    {
    }
}

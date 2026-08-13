import assert from 'node:assert/strict';
import test from 'node:test';
import { compileRevisions } from '../dist/record-model/compiler.js';
import {
  catalogMethodName,
  catalogMethodNames,
} from '../dist/record-model/php-renderer.js';

const revisions = ['2025-11-25', '2026-07-28'];

test('compiles both shipping revisions into a deduplicated deterministic pool', async () => {
  const first = await compileRevisions(revisions);
  const second = await compileRevisions(revisions);
  const logicalTypeCount = Object.values(first.manifests).reduce(
    (count, manifest) => count + Object.keys(manifest.types).length,
    0
  );

  assert.equal(logicalTypeCount, 300);
  assert.equal(Object.keys(first.pool).length, 225);
  assert.ok(Object.keys(first.pool).length < logicalTypeCount);
  assert.deepEqual(first.manifests, second.manifests);
  assert.deepEqual(first.pool, second.pool);
});

test('every descriptor reference resolves inside its revision', async () => {
  const bundle = await compileRevisions(revisions);

  for (const revision of Object.values(bundle.revisions)) {
    const names = new Set(Object.keys(revision.descriptors));
    for (const [name, descriptor] of Object.entries(revision.descriptors)) {
      for (const reference of referencesIn(descriptor)) {
        assert.ok(
          names.has(reference),
          `${revision.revision}:${name} references missing ${reference}`
        );
      }
    }
  }
});

test('publishes record-capable hard shapes through both catalogs', async () => {
  const bundle = await compileRevisions(revisions);
  const legacy = bundle.revisions['2025-11-25'];
  const modern = bundle.revisions['2026-07-28'];

  assert.ok(legacy.rootRecordTypes.includes('CallToolResult'));
  assert.ok(modern.rootRecordTypes.includes('CallToolResult'));
  assert.ok(modern.rootRecordTypes.includes('InputRequests'));
  assert.ok(modern.rootRecordTypes.includes('InputResponses'));
  assert.ok(modern.rootRecordTypes.includes('RequestMetaObject'));
});

test('retains revision-specific literal constants', async () => {
  const bundle = await compileRevisions(revisions);
  const legacy = bundle.revisions['2025-11-25'];
  const modern = bundle.revisions['2026-07-28'];

  assert.deepEqual(legacy.constants, {
    INTERNAL_ERROR: -32603,
    INVALID_PARAMS: -32602,
    INVALID_REQUEST: -32600,
    JSONRPC_VERSION: '2.0',
    LATEST_PROTOCOL_VERSION: '2025-11-25',
    METHOD_NOT_FOUND: -32601,
    PARSE_ERROR: -32700,
    URL_ELICITATION_REQUIRED: -32042,
  });
  assert.deepEqual(modern.constants, {
    HEADER_MISMATCH: -32020,
    INTERNAL_ERROR: -32603,
    INVALID_PARAMS: -32602,
    INVALID_REQUEST: -32600,
    JSONRPC_VERSION: '2.0',
    LATEST_PROTOCOL_VERSION: '2026-07-28',
    METHOD_NOT_FOUND: -32601,
    MISSING_REQUIRED_CLIENT_CAPABILITY: -32021,
    PARSE_ERROR: -32700,
    UNSUPPORTED_PROTOCOL_VERSION: -32022,
  });
});

test('compiles numeric JSDoc bounds and curated cross-field constraints', async () => {
  const bundle = await compileRevisions(revisions);
  const legacy = bundle.revisions['2025-11-25'];
  const modern = bundle.revisions['2026-07-28'];

  assert.deepEqual(modern.descriptors.CacheableResult.fields.ttlMs.type, {
    kind: 'number',
    minimum: 0,
  });
  assert.deepEqual(legacy.descriptors.Annotations.fields.priority.type, {
    kind: 'number',
    minimum: 0,
    maximum: 1,
  });
  assert.deepEqual(modern.descriptors.InputRequiredResult.atLeastOneOf, [
    ['inputRequests', 'requestState'],
  ]);
  assert.equal(legacy.descriptors.InputRequiredResult, undefined);
});

test('renders acronym-aware catalog methods and rejects unsafe names', () => {
  assert.equal(catalogMethodName('JSONRPCErrorResponse'), 'jsonrpcErrorResponse');
  assert.equal(catalogMethodName('URLSchema'), 'urlSchema');
  assert.equal(catalogMethodName('TextContent'), 'textContent');
  assert.throws(() => catalogMethodName('Type'), /inherited method type/);
  assert.throws(() => catalogMethodName('Class'), /reserved PHP method class/);
  assert.throws(
    () => catalogMethodNames(['JSONRPCError', 'JsonrpcError']),
    /both produce PHP method jsonrpcError/
  );
});

function referencesIn(value) {
  if (Array.isArray(value)) {
    return value.flatMap(referencesIn);
  }
  if (value === null || typeof value !== 'object') {
    return [];
  }

  const own = value.kind === 'ref' ? [value.name] : [];
  return own.concat(Object.values(value).flatMap(referencesIn));
}

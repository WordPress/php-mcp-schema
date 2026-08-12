import assert from 'node:assert/strict';
import test from 'node:test';
import { compileRevisions } from '../dist/record-model/compiler.js';

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

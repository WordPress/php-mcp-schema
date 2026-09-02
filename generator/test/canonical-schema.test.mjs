import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import test from 'node:test';
import { fileURLToPath } from 'node:url';
import Ajv2020 from 'ajv/dist/2020.js';
import { assertCompatibilityDecisions } from '../generate.mjs';
import { loadCanonicalSchemas } from '../lib/canonical-schema.mjs';
import {
  assertSupportedSchemaDocument,
  SUPPORTED_SCHEMA_KEYWORDS,
} from '../lib/schema-tools.mjs';

const generatorDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const repositoryDirectory = resolve(generatorDirectory, '..');
const canonical = await loadCanonicalSchemas();
const compatibility = JSON.parse(
  await readFile(resolve(repositoryDirectory, 'resources/schema/compatibility-manifest.json'), 'utf8'),
);

for (const [version, source] of Object.entries(canonical.sources)) {
  test(`${version} loads the reviewed effective Draft 2020-12 schema`, () => {
    const schema = canonical.documents[version];
    const metadata = canonical.metadata[version];

    assert.equal(metadata.rawSha256, source.sha256);
    assert.equal(metadata.effectiveSha256, source.patch.effectiveSha256);
    assert.equal(metadata.patch.sha256, source.patch.sha256);
    assert.equal(metadata.patch.path, source.patch.path);

    const ajv = new Ajv2020({ strict: false, validateFormats: false });
    assert.equal(ajv.validateSchema(schema), true, JSON.stringify(ajv.errors));

    const state = assertSupportedSchemaDocument(schema, version);
    assert.deepEqual([...state.keywords].sort(), metadata.keywords);
  });
}

test('the effective canonical schemas exercise the supported vocabulary', () => {
  const usedKeywords = new Set();
  for (const metadata of Object.values(canonical.metadata)) {
    for (const keyword of metadata.keywords) usedKeywords.add(keyword);
  }
  assert.deepEqual([...usedKeywords].sort(), [...SUPPORTED_SCHEMA_KEYWORDS].sort());
});

test('the reviewed corrections exist only in the effective canonical documents', () => {
  const v2025 = canonical.documents['2025-11-25'].$defs;
  const v2026 = canonical.documents['2026-07-28'].$defs;

  assert.deepEqual(
    v2025.ElicitResult.properties.content.additionalProperties.anyOf[1].type,
    ['string', 'number', 'boolean'],
  );
  assert.deepEqual(
    v2026.ElicitResult.properties.content.additionalProperties.anyOf[1].type,
    ['string', 'number', 'boolean'],
  );
  assert.deepEqual(v2026.JSONValue.anyOf[2].type, ['string', 'number', 'boolean', 'null']);
  for (const field of ['default', 'minimum', 'maximum']) {
    assert.equal(v2025.NumberSchema.properties[field].type, 'number');
    assert.equal(v2026.NumberSchema.properties[field].type, 'number');
  }
});

test('the canonical gate rejects unknown keywords and semantic $ref siblings', () => {
  const unknownKeyword = structuredClone(canonical.documents['2026-07-28']);
  unknownKeyword.$defs.Tool.properties.name.pattern = '^[a-z]+$';
  assert.throws(
    () => assertSupportedSchemaDocument(unknownKeyword, '2026-07-28'),
    /unsupported keyword pattern/u,
  );

  const refSibling = structuredClone(canonical.documents['2026-07-28']);
  refSibling.$defs.EmptyResult.type = 'object';
  assert.throws(
    () => assertSupportedSchemaDocument(refSibling, '2026-07-28'),
    /unsupported \$ref siblings: type/u,
  );
});

test('the generator gate rejects an unreviewed native getter category change', () => {
  assert.doesNotThrow(() => assertCompatibilityDecisions(canonical.documents, compatibility));

  const changed = structuredClone(canonical.documents);
  changed['2026-07-28'].$defs.Tool.properties.title.type = 'integer';
  assert.throws(
    () => assertCompatibilityDecisions(changed, compatibility),
    /missing=getter:Tool.title/u,
  );
});

test('the compatibility inventory matches the effective canonical definitions', () => {
  for (const [version, document] of Object.entries(canonical.documents)) {
    const expectedNames = Object.keys(document.$defs).sort();
    const revision = compatibility.revisions[version];
    assert.equal(revision.definitionCount, expectedNames.length);
    assert.deepEqual(revision.definitions.map((item) => item.name), expectedNames);
    assert.equal(revision.sha256, canonical.metadata[version].rawSha256);
    assert.equal(revision.effectiveSha256, canonical.metadata[version].effectiveSha256);
    assert.deepEqual(revision.patch, canonical.metadata[version].patch);
  }

  const older = new Set(Object.keys(canonical.documents['2025-11-25'].$defs));
  const newer = new Set(Object.keys(canonical.documents['2026-07-28'].$defs));
  const comparison = compatibility.comparisons['2025-11-25__2026-07-28'];
  assert.deepEqual(
    comparison.definitionChanges.added,
    [...newer].filter((name) => !older.has(name)).sort(),
  );
  assert.deepEqual(
    comparison.definitionChanges.removed,
    [...older].filter((name) => !newer.has(name)).sort(),
  );
  assert.deepEqual(
    comparison.definitionChanges.unchanged,
    [...older].filter((name) => newer.has(name) && !comparison.definitionChanges.changed.includes(name)).sort(),
  );
});

test('the compatibility manifest covers every revision pair and directional method change', () => {
  const expectedPairs = (Object.keys(canonical.sources).length * (Object.keys(canonical.sources).length - 1)) / 2;
  assert.equal(compatibility.formatVersion, 3);
  assert.equal(Object.keys(compatibility.comparisons).length, expectedPairs);

  const comparison = compatibility.comparisons['2025-11-25__2026-07-28'];
  assert.deepEqual(
    comparison.definitionChanges.kindChanges.map((item) => item.name),
    ['ClientNotification', 'ClientResult'],
  );

  const older = comparison.messageAvailability['2025-11-25'];
  const newer = comparison.messageAvailability['2026-07-28'];
  assert.equal(older.clientToServer.requests.ping, 'PingRequest');
  assert.equal('ping' in newer.clientToServer.requests, false);
  assert.equal(newer.clientToServer.requests['server/discover'], 'DiscoverRequest');
  assert.deepEqual(newer.serverToClient.requests, {});
  assert.equal(newer.embeddedInputs['sampling/createMessage'], 'CreateMessageRequest');
  assert.equal(newer.clientToServer.requests['sampling/createMessage'], undefined);
});

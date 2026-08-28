import assert from 'node:assert/strict';
import { createHash } from 'node:crypto';
import { readFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import test from 'node:test';
import { fileURLToPath } from 'node:url';
import Ajv2020 from 'ajv/dist/2020.js';

const generatorDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const repositoryDirectory = resolve(generatorDirectory, '..');
const manifest = JSON.parse(await readFile(resolve(generatorDirectory, 'schema-sources.json'), 'utf8'));
const supportedKeywords = [
  '$ref',
  'additionalProperties',
  'allOf',
  'anyOf',
  'const',
  'description',
  'enum',
  'format',
  'items',
  'maxItems',
  'maximum',
  'minimum',
  'properties',
  'required',
  'type',
];
const keywordUseByVersion = {
  '2025-11-25': supportedKeywords.filter((keyword) => keyword !== 'maxItems'),
  '2026-07-28': supportedKeywords,
};

function sha256(content) {
  return createHash('sha256').update(content).digest('hex');
}

function auditSchema(schema, path, state) {
  assert.equal(typeof schema, 'object', `${path} must be a schema object`);
  assert.ok(schema !== null && !Array.isArray(schema), `${path} must be a schema object`);

  for (const keyword of Object.keys(schema)) {
    assert.ok(supportedKeywords.includes(keyword), `${path} uses unsupported keyword ${keyword}`);
    state.keywords.add(keyword);
  }

  if ('$ref' in schema) {
    assert.match(schema.$ref, /^#\/\$defs\/[A-Za-z][A-Za-z0-9]*$/u, `${path} uses a non-local reference`);
    state.references.add(schema.$ref);
  }
  if (schema.properties) {
    for (const [name, property] of Object.entries(schema.properties)) {
      auditSchema(property, `${path}/properties/${name}`, state);
    }
  }
  if (schema.additionalProperties && typeof schema.additionalProperties === 'object') {
    auditSchema(schema.additionalProperties, `${path}/additionalProperties`, state);
  }
  if (schema.items) auditSchema(schema.items, `${path}/items`, state);
  for (const combinator of ['allOf', 'anyOf']) {
    if (schema[combinator]) {
      schema[combinator].forEach((member, index) => auditSchema(member, `${path}/${combinator}/${index}`, state));
    }
  }
}

for (const [version, source] of Object.entries(manifest)) {
  test(`${version} is the reviewed canonical Draft 2020-12 schema`, async () => {
    const content = await readFile(resolve(repositoryDirectory, 'resources/schema', version, 'schema.json'));
    assert.equal(sha256(content), source.sha256);

    const schema = JSON.parse(content.toString('utf8'));
    assert.equal(schema.$schema, 'https://json-schema.org/draft/2020-12/schema');
    assert.equal(Object.keys(schema).sort().join(','), '$defs,$schema');

    const ajv = new Ajv2020({ strict: false, validateFormats: false });
    assert.equal(ajv.validateSchema(schema), true, JSON.stringify(ajv.errors));

    const state = { keywords: new Set(), references: new Set() };
    for (const [name, definition] of Object.entries(schema.$defs)) {
      auditSchema(definition, `#/$defs/${name}`, state);
    }

    assert.deepEqual([...state.keywords].sort(), keywordUseByVersion[version]);
    for (const reference of state.references) {
      assert.ok(reference.slice(8) in schema.$defs, `${reference} does not resolve`);
    }
  });
}

test('the supported canonical definition taxonomy is frozen', async () => {
  const documents = {};
  for (const version of Object.keys(manifest)) {
    documents[version] = JSON.parse(
      await readFile(resolve(repositoryDirectory, 'resources/schema', version, 'schema.json'), 'utf8'),
    );
  }

  const older = new Set(Object.keys(documents['2025-11-25'].$defs));
  const newer = new Set(Object.keys(documents['2026-07-28'].$defs));
  assert.equal(older.size, 145);
  assert.equal(newer.size, 155);
  assert.equal([...newer].filter((name) => !older.has(name)).length, 42);
  assert.equal([...older].filter((name) => !newer.has(name)).length, 32);
  assert.equal([...older].filter((name) => newer.has(name)).length, 113);
});

test('the compatibility manifest covers every revision pair and directional method change', async () => {
  const compatibility = JSON.parse(
    await readFile(resolve(repositoryDirectory, 'resources/schema/compatibility-manifest.json'), 'utf8'),
  );
  const expectedPairs = (Object.keys(manifest).length * (Object.keys(manifest).length - 1)) / 2;
  assert.equal(compatibility.formatVersion, 2);
  assert.equal(Object.keys(compatibility.comparisons).length, expectedPairs);

  const comparison = compatibility.comparisons['2025-11-25__2026-07-28'];
  assert.equal(comparison.definitionChanges.added.length, 42);
  assert.equal(comparison.definitionChanges.removed.length, 32);
  assert.deepEqual(
    comparison.definitionChanges.kindChanges.map((item) => item.name),
    ['ClientNotification', 'ClientResult'],
  );
  assert.equal(comparison.classification.classifiedDifferences && Object.keys(comparison.classification.classifiedDifferences).length, 381);

  const older = comparison.messageAvailability['2025-11-25'];
  const newer = comparison.messageAvailability['2026-07-28'];
  assert.equal(older.clientToServer.requests.ping, 'PingRequest');
  assert.equal('ping' in newer.clientToServer.requests, false);
  assert.equal(newer.clientToServer.requests['server/discover'], 'DiscoverRequest');
  assert.deepEqual(newer.serverToClient.requests, {});
  assert.equal(newer.embeddedInputs['sampling/createMessage'], 'CreateMessageRequest');
  assert.equal(newer.clientToServer.requests['sampling/createMessage'], undefined);
});

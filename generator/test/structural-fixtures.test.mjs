import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import test from 'node:test';
import { fileURLToPath } from 'node:url';
import Ajv2020 from 'ajv/dist/2020.js';
import { SUPPORTED_SCHEMA_KEYWORDS } from '../lib/schema-tools.mjs';

const repositoryDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..', '..');
const fixture = JSON.parse(
  await readFile(resolve(repositoryDirectory, 'tests/fixtures/structural/schema-runtime.cases.json'), 'utf8'),
);

function collectKeywords(schema, output = new Set()) {
  if (!schema || typeof schema !== 'object' || Array.isArray(schema)) return output;
  for (const key of Object.keys(schema)) output.add(key);
  if (schema.$defs) for (const child of Object.values(schema.$defs)) collectKeywords(child, output);
  if (schema.properties) for (const child of Object.values(schema.properties)) collectKeywords(child, output);
  if (schema.additionalProperties && typeof schema.additionalProperties === 'object') {
    collectKeywords(schema.additionalProperties, output);
  }
  if (schema.items) collectKeywords(schema.items, output);
  for (const key of ['allOf', 'anyOf']) {
    for (const child of schema[key] || []) collectKeywords(child, output);
  }
  return output;
}

test('shared structural fixtures cover the bounded schema vocabulary', () => {
  const keywords = new Set();
  for (const item of fixture.cases) collectKeywords(item.schema, keywords);
  assert.deepEqual([...keywords].sort(), ['$defs', ...SUPPORTED_SCHEMA_KEYWORDS].sort());
});

for (const item of fixture.cases) {
  test(`AJV oracle: ${item.id}`, () => {
    const validate = new Ajv2020({ strict: false, validateFormats: false }).compile(item.schema);
    for (const value of item.valid) {
      assert.equal(validate(value), true, `${item.id} rejected ${JSON.stringify(value)}: ${JSON.stringify(validate.errors)}`);
    }
    for (const value of item.invalid) {
      assert.equal(validate(value), false, `${item.id} accepted ${JSON.stringify(value)}`);
    }
  });
}

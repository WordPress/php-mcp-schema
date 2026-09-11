import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import test from 'node:test';
import { fileURLToPath } from 'node:url';
import Ajv2020 from 'ajv/dist/2020.js';
import { loadCanonicalSchemas } from '../lib/canonical-schema.mjs';

const repositoryDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..', '..');
const fixture = JSON.parse(
  await readFile(resolve(repositoryDirectory, 'tests/fixtures/canonical/schema-runtime.cases.json'), 'utf8'),
);
const canonical = await loadCanonicalSchemas();

assert.equal(fixture.formatVersion, 1);

for (const item of fixture.cases) {
  test(`canonical AJV oracle: ${item.id}`, () => {
    const document = structuredClone(canonical.documents[item.revision]);
    assert.ok(document, `${item.id} uses unsupported revision ${item.revision}`);
    assert.ok(document.$defs[item.definition], `${item.id} uses unknown definition ${item.definition}`);
    document.$ref = `#/$defs/${item.definition}`;

    const validate = new Ajv2020({ strict: false, validateFormats: false }).compile(document);
    for (const value of item.valid) {
      assert.equal(
        validate(value),
        true,
        `${item.id} rejected ${JSON.stringify(value)}: ${JSON.stringify(validate.errors)}`,
      );
    }
    for (const value of item.invalid) {
      assert.equal(validate(value), false, `${item.id} accepted ${JSON.stringify(value)}`);
    }
  });
}

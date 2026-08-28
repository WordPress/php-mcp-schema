import assert from 'node:assert/strict';
import { createHash } from 'node:crypto';
import { mkdtemp, readFile, readdir, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import test from 'node:test';
import { generate } from '../generate.mjs';

async function inventory(directory, relative = '') {
  const output = {};
  const entries = await readdir(join(directory, relative), { withFileTypes: true });
  for (const entry of entries.sort((a, b) => a.name.localeCompare(b.name))) {
    const path = relative ? `${relative}/${entry.name}` : entry.name;
    if (entry.isDirectory()) Object.assign(output, await inventory(directory, path));
    else output[path] = createHash('sha256').update(await readFile(join(directory, path))).digest('hex');
  }
  return output;
}

test('generation replaces only a resolved Generated subtree and is deterministic', async () => {
  const temporary = await mkdtemp(join(tmpdir(), 'php-mcp-schema-generator-'));
  const output = join(temporary, 'Generated');
  try {
    const first = await generate(output);
    const firstInventory = await inventory(output);
    const second = await generate(output);
    const secondInventory = await inventory(output);

    assert.deepEqual(second, first);
    assert.deepEqual(secondInventory, firstInventory);
    assert.equal(first.catalogs, 2);
    assert.ok(first.records > 130);
    assert.ok(first.contracts >= 15);
    assert.equal(first.values, 3);
    assert.equal('Record/PingRequest.php' in firstInventory, true);
    assert.equal('Record/DiscoverRequest.php' in firstInventory, true);
    assert.equal('Contract/ClientNotification.php' in firstInventory, true);
    assert.equal('Record/ClientNotification.php' in firstInventory, true);
    assert.equal('Contract/ClientResult.php' in firstInventory, true);
    assert.equal('Record/ClientResult.php' in firstInventory, true);
  } finally {
    await rm(temporary, { recursive: true, force: true });
  }
});

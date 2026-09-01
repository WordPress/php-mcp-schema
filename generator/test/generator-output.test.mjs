import assert from 'node:assert/strict';
import { createHash } from 'node:crypto';
import { mkdir, mkdtemp, readFile, readdir, rm, writeFile } from 'node:fs/promises';
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

test('generation stages and replaces only explicit generated paths deterministically', async () => {
  const temporary = await mkdtemp(join(tmpdir(), 'php-mcp-schema-generator-'));
  const output = join(temporary, 'repository');
  try {
    await mkdir(join(output, 'src', 'Generated'), { recursive: true });
    await mkdir(join(output, 'src', 'Internal'), { recursive: true });
    await mkdir(join(output, 'src', 'Record'), { recursive: true });
    await writeFile(join(output, 'src', 'Generated', 'Legacy.php'), 'legacy');
    await writeFile(join(output, 'src', 'Internal', 'Handwritten.php'), 'internal sentinel');
    await writeFile(join(output, 'src', 'Record', 'Stale.php'), 'stale generated file');
    await writeFile(join(output, 'src', 'Record.php'), 'record sentinel');
    await writeFile(join(output, 'src', 'Schema.php'), 'schema sentinel');

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
    assert.equal('src/Record/PingRequest.php' in firstInventory, true);
    assert.equal('src/Record/DiscoverRequest.php' in firstInventory, true);
    assert.equal('src/Contract/ClientNotification.php' in firstInventory, true);
    assert.equal('src/Record/ClientNotification.php' in firstInventory, true);
    assert.equal('src/Contract/ClientResult.php' in firstInventory, true);
    assert.equal('src/Record/ClientResult.php' in firstInventory, true);
    assert.equal('src/Internal/Catalog/V2025_11_25.php' in firstInventory, true);
    assert.equal('src/Internal/Catalog/V2026_07_28.php' in firstInventory, true);
    assert.equal('src/Internal/TypeRegistry.php' in firstInventory, true);
    assert.equal('src/Generated/Legacy.php' in firstInventory, false);
    assert.equal('src/Record/Stale.php' in firstInventory, false);
    assert.equal(await readFile(join(output, 'src', 'Internal', 'Handwritten.php'), 'utf8'), 'internal sentinel');
    assert.equal(await readFile(join(output, 'src', 'Record.php'), 'utf8'), 'record sentinel');
    assert.equal(await readFile(join(output, 'src', 'Schema.php'), 'utf8'), 'schema sentinel');

    assert.match(
      await readFile(join(output, 'src', 'Record', 'Tool.php'), 'utf8'),
      /namespace WP\\McpSchema\\Record;/u,
    );
    assert.match(
      await readFile(join(output, 'src', 'Contract', 'ContentBlock.php'), 'utf8'),
      /namespace WP\\McpSchema\\Contract;/u,
    );
    assert.match(
      await readFile(join(output, 'src', 'Value', 'Role.php'), 'utf8'),
      /namespace WP\\McpSchema\\Value;/u,
    );
    assert.match(
      await readFile(join(output, 'src', 'Internal', 'Catalog', 'V2025_11_25.php'), 'utf8'),
      /namespace WP\\McpSchema\\Internal\\Catalog;/u,
    );
    assert.match(
      await readFile(join(output, 'src', 'Internal', 'TypeRegistry.php'), 'utf8'),
      /namespace WP\\McpSchema\\Internal;/u,
    );
    assert.equal(
      Object.keys(firstInventory).some((path) => path.includes('Generated')),
      false,
    );
  } finally {
    await rm(temporary, { recursive: true, force: true });
  }
});

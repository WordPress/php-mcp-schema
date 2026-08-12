import assert from 'node:assert/strict';
import { mkdtemp, mkdir, readFile, rm, writeFile } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { describe, it } from 'node:test';

import { createConfig } from '../dist/config/index.js';
import { FileWriter } from '../dist/writers/index.js';

describe('revision-tree cleanup', () => {
  it('removes only the configured revision tree', async () => {
    const root = await mkdtemp(join(tmpdir(), 'php-mcp-schema-writer-'));
    const revisionTree = join(root, 'V20260728');
    const siblingTree = join(root, 'V20251125');

    try {
      await mkdir(revisionTree);
      await mkdir(siblingTree);
      await writeFile(join(revisionTree, 'remove.txt'), 'remove');
      await writeFile(join(siblingTree, 'keep.txt'), 'keep');

      const config = createConfig({
        schema: { version: '2026-07-28' },
        output: { outputDir: revisionTree },
      });
      await new FileWriter(config).clearOutput();

      await assert.rejects(readFile(join(revisionTree, 'remove.txt')), /ENOENT/);
      assert.equal(await readFile(join(siblingTree, 'keep.txt'), 'utf8'), 'keep');
    } finally {
      await rm(root, { recursive: true, force: true });
    }
  });

  it('rejects a broad or mismatched cleanup target before deleting it', async () => {
    const root = await mkdtemp(join(tmpdir(), 'php-mcp-schema-writer-'));
    const broadTarget = join(root, 'src');

    try {
      await mkdir(broadTarget);
      await writeFile(join(broadTarget, 'keep.txt'), 'keep');

      const validConfig = createConfig({ schema: { version: '2026-07-28' } });
      const config = {
        ...validConfig,
        output: { ...validConfig.output, outputDir: broadTarget },
      };

      await assert.rejects(
        new FileWriter(config).clearOutput(),
        /Refusing to clear output directory.*expected a V20260728 revision tree/
      );
      assert.equal(await readFile(join(broadTarget, 'keep.txt'), 'utf8'), 'keep');
    } finally {
      await rm(root, { recursive: true, force: true });
    }
  });
});

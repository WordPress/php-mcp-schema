import assert from 'node:assert/strict';
import { describe, it } from 'node:test';

import {
  createConfig,
  getOutputPath,
  getRevisionSegment,
  loadShippingRevisionConfigs,
  validateConfig,
} from '../dist/config/index.js';

describe('generator configuration', () => {
  it('derives the default namespace and output tree from the revision', () => {
    const config = createConfig({ schema: { version: '2025-11-25' } });

    assert.deepEqual(config.schema, {
      repository: 'modelcontextprotocol/modelcontextprotocol',
      branch: 'main',
      path: 'schema',
      version: '2025-11-25',
    });
    assert.equal(config.output.namespace, 'WP\\McpSchema\\V20251125');
    assert.equal(config.output.outputDir, '../src/V20251125');
    assert.deepEqual(config.skill, {
      enabled: false,
      outputDir: '../skill',
    });
    assert.equal(config.verbose, false);
    assert.equal(config.dryRun, false);
  });

  it('preserves explicit schema and output overrides', () => {
    const config = createConfig({
      schema: {
        version: '2025-11-25',
        repository: 'example/mcp',
        branch: 'release',
      },
      output: {
        namespace: 'Example\\Schema',
        outputDir: '/tmp/V20251125',
      },
      verbose: true,
      dryRun: true,
    });

    assert.equal(config.schema.repository, 'example/mcp');
    assert.equal(config.schema.branch, 'release');
    assert.equal(config.schema.path, 'schema');
    assert.equal(config.output.namespace, 'Example\\Schema');
    assert.equal(config.output.outputDir, '/tmp/V20251125');
    assert.equal(config.verbose, true);
    assert.equal(config.dryRun, true);
  });

  it('requires a schema version', () => {
    assert.throws(() => createConfig({ schema: { version: '' } }), /Schema version is required/);
  });

  it('rejects revisions that cannot form a deterministic namespace segment', () => {
    assert.throws(
      () => createConfig({ schema: { version: 'draft' } }),
      /Invalid schema revision: draft/
    );
    assert.equal(getRevisionSegment('2026-07-28'), 'V20260728');
  });

  it('rejects output overrides that are not revision trees', () => {
    assert.throws(
      () =>
        createConfig({
          schema: { version: '2026-07-28' },
          output: { outputDir: '/tmp/src' },
        }),
      /Output directory must end in the V20260728 revision tree/
    );
  });

  it('loads the shipping revision manifest in chronological order', () => {
    const configs = loadShippingRevisionConfigs();

    assert.deepEqual(
      configs.map((config) => config.schema.version),
      ['2025-11-25', '2026-07-28']
    );
    assert.deepEqual(
      configs.map((config) => config.output.namespace),
      ['WP\\McpSchema\\V20251125', 'WP\\McpSchema\\V20260728']
    );
    assert.deepEqual(
      configs.map((config) => config.skill.enabled),
      [false, true]
    );
  });

  it('rejects invalid indentation widths', () => {
    const config = createConfig({ schema: { version: '2025-11-25' } });

    assert.throws(
      () => validateConfig({ ...config, output: { ...config.output, indentSize: 0 } }),
      /Indent size must be between 1 and 8/
    );
    assert.throws(
      () => validateConfig({ ...config, output: { ...config.output, indentSize: 9 } }),
      /Indent size must be between 1 and 8/
    );
  });

  it('keeps DTO and supporting-type output paths stable', () => {
    const config = createConfig({ schema: { version: '2025-11-25' } });

    assert.equal(
      getOutputPath(config, 'Server', 'Tools', 'Dto', 'Tool.php'),
      '../src/V20251125/Server/Tools/Tool.php'
    );
    assert.equal(
      getOutputPath(config, 'Server', 'Tools', 'Union', 'Content.php'),
      '../src/V20251125/Server/Tools/Union/Content.php'
    );
  });
});

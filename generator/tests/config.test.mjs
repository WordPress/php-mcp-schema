import assert from 'node:assert/strict';
import { describe, it } from 'node:test';

import { createConfig, getOutputPath, validateConfig } from '../dist/config/index.js';

describe('generator configuration', () => {
  it('keeps the current single-revision defaults stable', () => {
    const config = createConfig({ schema: { version: '2025-11-25' } });

    assert.deepEqual(config.schema, {
      repository: 'modelcontextprotocol/modelcontextprotocol',
      branch: 'main',
      path: 'schema',
      version: '2025-11-25',
    });
    assert.equal(config.output.namespace, 'WP\\McpSchema');
    assert.equal(config.output.outputDir, '../src');
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
        outputDir: '/tmp/example-schema',
      },
      verbose: true,
      dryRun: true,
    });

    assert.equal(config.schema.repository, 'example/mcp');
    assert.equal(config.schema.branch, 'release');
    assert.equal(config.schema.path, 'schema');
    assert.equal(config.output.namespace, 'Example\\Schema');
    assert.equal(config.output.outputDir, '/tmp/example-schema');
    assert.equal(config.verbose, true);
    assert.equal(config.dryRun, true);
  });

  it('requires a schema version', () => {
    assert.throws(() => createConfig({ schema: { version: '' } }), /Schema version is required/);
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
      '../src/Server/Tools/Tool.php'
    );
    assert.equal(
      getOutputPath(config, 'Server', 'Tools', 'Union', 'Content.php'),
      '../src/Server/Tools/Union/Content.php'
    );
  });
});

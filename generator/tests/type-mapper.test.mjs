import assert from 'node:assert/strict';
import { describe, it } from 'node:test';

import { ConstantsGenerator, TypeMapper, createConstantsMap } from '../dist/generators/index.js';
import { createConfig } from '../dist/config/index.js';

describe('current type and constant generation', () => {
  it('resolves a string typeof constant as a string literal', () => {
    const constants = createConstantsMap([
      {
        name: 'JSONRPC_VERSION',
        value: '2.0',
        valueType: 'string',
      },
    ]);

    assert.deepEqual(TypeMapper.mapType('typeof JSONRPC_VERSION', undefined, constants), {
      type: 'string',
      nullable: false,
      isArray: false,
      phpDocType: "'2.0'",
    });
  });

  it('emits numeric constants without string quoting', () => {
    const generator = new ConstantsGenerator(createConfig({ schema: { version: '2025-11-25' } }));
    const php = generator.generate([
      {
        name: 'PARSE_ERROR',
        value: -32700,
        valueType: 'number',
      },
    ]);

    assert.match(php, /public const PARSE_ERROR = -32700;/);
    assert.doesNotMatch(php, /public const PARSE_ERROR = '-32700';/);
  });
});

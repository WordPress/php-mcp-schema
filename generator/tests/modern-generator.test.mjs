import assert from 'node:assert/strict';
import { describe, it } from 'node:test';

import {
  createConfig,
  createEmptyVersionTracker,
  DtoGenerator,
  EnumGenerator,
  getVersionsUpTo,
  parseSchema,
  UnionGenerator,
} from '../dist/index.js';
import { createConstantsMap } from '../dist/generators/index.js';

const config = createConfig({ schema: { version: '2026-07-28' } });

function generateDto(source, interfaceName) {
  const ast = parseSchema(source);
  const iface = ast.interfaces.find(({ name }) => name === interfaceName);
  assert.ok(iface, `Expected ${interfaceName} in fixture`);

  return new DtoGenerator(
    ast.interfaces,
    config,
    {},
    ast.typeAliases,
    undefined,
    undefined,
    createEmptyVersionTracker(),
    createConstantsMap(ast.constants)
  ).generate(iface);
}

describe('modern revision generator regressions', () => {
  it('uses legal PHP members while preserving exact namespaced wire keys', () => {
    const php = generateDto(
      `
        export interface RequestMetaObject {
          "io.modelcontextprotocol/protocolVersion": string;
        }
      `,
      'RequestMetaObject'
    );

    assert.match(php, /protected string \$protocolVersion;/);
    assert.match(php, /\$data\['io\.modelcontextprotocol\/protocolVersion'\]/);
    assert.match(
      php,
      /\$result\['io\.modelcontextprotocol\/protocolVersion'\] = \$this->protocolVersion;/
    );
    assert.match(php, /public function getProtocolVersion\(\): string/);
    assert.doesNotMatch(php, /\$"io\.modelcontextprotocol\/protocolVersion"/);
  });

  it('rejects PHP member-name collisions deterministically', () => {
    assert.throws(
      () =>
        generateDto(
          `
            export interface CollidingMeta {
              "io.modelcontextprotocol/a-b": string;
              "io.modelcontextprotocol/a_b": string;
            }
          `,
          'CollidingMeta'
        ),
      /CollidingMeta.*a-b.*a_b.*a_b/
    );
  });

  it('passes required narrowed values to non-nullable parent constructors', () => {
    const php = generateDto(
      `
        export const PARSE_ERROR = -32700;

        export interface Error {
          code: number;
          message: string;
        }

        export interface ParseError extends Error {
          code: typeof PARSE_ERROR;
        }
      `,
      'ParseError'
    );

    assert.match(php, /\/\*\* @var -32700 \$code \*\//);
    assert.match(php, /\$code = self::asInt\(\$data\['code'\]\);/);
    assert.match(php, /parent::__construct\(\$code, \$message\);/);
    assert.doesNotMatch(php, /parent::__construct\(null,/);
  });

  it('fails when the target revision is absent from version history', () => {
    assert.throws(
      () => getVersionsUpTo(['2025-11-25'], '2026-07-28'),
      /2026-07-28.*version history/
    );
  });

  it('carries declaration and property deprecations into generated PHPDoc', () => {
    const php = generateDto(
      `
        /**
         * Legacy capability.
         * @deprecated Deprecated in the modern revision.
         */
        export interface LegacyCapability {
          /**
           * Old field.
           * @deprecated Use the replacement field.
           */
          oldField?: string;
        }
      `,
      'LegacyCapability'
    );

    assert.match(php, /@deprecated Deprecated in the modern revision\./);
    assert.match(php, /@deprecated Use the replacement field\./);
  });

  it('carries deprecations into generated enum and union PHPDoc', () => {
    const ast = parseSchema(`
      export interface One {}
      export interface Two {}

      /** @deprecated Use ModernLevel. */
      export type LegacyLevel = "info" | "error";

      /** @deprecated Use ModernContent. */
      export type LegacyContent = One | Two;
    `);
    const legacyLevel = ast.typeAliases.find(({ name }) => name === 'LegacyLevel');
    const legacyContent = ast.typeAliases.find(({ name }) => name === 'LegacyContent');
    assert.ok(legacyLevel);
    assert.ok(legacyContent);

    assert.match(new EnumGenerator(config).generate(legacyLevel), /@deprecated Use ModernLevel\./);
    assert.match(
      new UnionGenerator(config, ast.typeAliases).generate(legacyContent),
      /@deprecated Use ModernContent\./
    );
  });
});

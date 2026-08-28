#!/usr/bin/env node

import { createHash } from 'node:crypto';
import { mkdir, readFile, rename, rm, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const generatorDirectory = dirname(fileURLToPath(import.meta.url));
const repositoryDirectory = resolve(generatorDirectory, '..');
const manifestPath = resolve(generatorDirectory, 'schema-sources.json');
const manifest = JSON.parse(await readFile(manifestPath, 'utf8'));
const checkOnly = process.argv.includes('--check');

function digest(content) {
  return createHash('sha256').update(content).digest('hex');
}

for (const [version, source] of Object.entries(manifest)) {
  const target = resolve(repositoryDirectory, 'resources', 'schema', version, 'schema.json');
  let content;

  if (checkOnly) {
    content = await readFile(target);
  } else {
    const response = await fetch(source.url, {
      headers: { 'user-agent': 'wordpress-php-mcp-schema-generator' },
    });
    if (!response.ok) {
      throw new Error(`Unable to fetch MCP ${version}: ${response.status} ${response.statusText}`);
    }
    content = Buffer.from(await response.arrayBuffer());
  }

  const actualDigest = digest(content);
  if (actualDigest !== source.sha256) {
    throw new Error(`MCP ${version} digest mismatch: expected ${source.sha256}, got ${actualDigest}`);
  }

  const schema = JSON.parse(content.toString('utf8'));
  if (schema.$schema !== 'https://json-schema.org/draft/2020-12/schema' || !schema.$defs) {
    throw new Error(`MCP ${version} is not the expected Draft 2020-12 canonical schema`);
  }

  if (!checkOnly) {
    await mkdir(dirname(target), { recursive: true });
    const temporary = `${target}.tmp`;
    await writeFile(temporary, content);
    try {
      await rename(temporary, target);
    } finally {
      await rm(temporary, { force: true });
    }
  }

  console.log(`${checkOnly ? 'verified' : 'updated'} ${version} ${actualDigest}`);
}

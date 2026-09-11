#!/usr/bin/env node

import { mkdir, readFile, rename, rm, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { loadCanonicalSchemas } from './lib/canonical-schema.mjs';
import { sha256 } from './lib/schema-tools.mjs';

const generatorDirectory = dirname(fileURLToPath(import.meta.url));
const repositoryDirectory = resolve(generatorDirectory, '..');
const manifestPath = resolve(generatorDirectory, 'schema-sources.json');
const manifest = JSON.parse(await readFile(manifestPath, 'utf8'));
const checkOnly = process.argv.includes('--check');

if (checkOnly) {
  const canonical = await loadCanonicalSchemas();
  for (const [version, metadata] of Object.entries(canonical.metadata)) {
    console.log(`verified ${version} ${metadata.rawSha256} effective ${metadata.effectiveSha256}`);
  }
  process.exit(0);
}

for (const [version, source] of Object.entries(manifest)) {
  const target = resolve(repositoryDirectory, 'resources', 'schema', version, 'schema.json');
  const response = await fetch(source.url, {
    headers: { 'user-agent': 'wordpress-php-mcp-schema-generator' },
  });
  if (!response.ok) {
    throw new Error(`Unable to fetch MCP ${version}: ${response.status} ${response.statusText}`);
  }
  const content = Buffer.from(await response.arrayBuffer());

  const actualDigest = sha256(content);
  if (actualDigest !== source.sha256) {
    throw new Error(`MCP ${version} digest mismatch: expected ${source.sha256}, got ${actualDigest}`);
  }

  const schema = JSON.parse(content.toString('utf8'));
  if (schema.$schema !== 'https://json-schema.org/draft/2020-12/schema' || !schema.$defs) {
    throw new Error(`MCP ${version} is not the expected Draft 2020-12 canonical schema`);
  }

  await mkdir(dirname(target), { recursive: true });
  const temporary = `${target}.tmp`;
  await writeFile(temporary, content);
  try {
    await rename(temporary, target);
  } finally {
    await rm(temporary, { force: true });
  }

  console.log(`updated ${version} ${actualDigest}`);
}

await loadCanonicalSchemas();

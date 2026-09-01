#!/usr/bin/env node

import { createHash } from 'node:crypto';
import { lstat, mkdtemp, readFile, readdir, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import {
  GENERATED_DIRECTORIES,
  GENERATED_FILES,
  LEGACY_GENERATED_PATHS,
} from './lib/generated-layout.mjs';
import { generate } from './generate.mjs';

const repositoryDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..');

async function inventoryPath(root, path, inventory) {
  const target = resolve(root, path);
  const stats = await lstat(target);
  if (stats.isDirectory()) {
    const entries = await readdir(target, { withFileTypes: true });
    for (const entry of entries.sort((a, b) => a.name.localeCompare(b.name))) {
      await inventoryPath(root, `${path}/${entry.name}`, inventory);
    }
    return;
  }

  inventory[path] = createHash('sha256').update(await readFile(target)).digest('hex');
}

async function generatedInventory(root) {
  const inventory = {};
  for (const path of [...GENERATED_DIRECTORIES, ...GENERATED_FILES]) {
    await inventoryPath(root, path, inventory);
  }

  return inventory;
}

function describeDifference(expected, actual) {
  const missing = Object.keys(expected).filter((path) => !(path in actual));
  const unexpected = Object.keys(actual).filter((path) => !(path in expected));
  const changed = Object.keys(expected).filter((path) => path in actual && expected[path] !== actual[path]);
  return [
    missing.length > 0 ? `missing: ${missing.join(', ')}` : '',
    unexpected.length > 0 ? `unexpected: ${unexpected.join(', ')}` : '',
    changed.length > 0 ? `changed: ${changed.join(', ')}` : '',
  ].filter(Boolean).join('\n');
}

async function assertAbsent(root, path) {
  try {
    await lstat(resolve(root, path));
  } catch (error) {
    if (error && error.code === 'ENOENT') return;
    throw error;
  }
  throw new Error(`Legacy generated path still exists: ${path}`);
}

const temporary = await mkdtemp(join(tmpdir(), 'php-mcp-schema-generated-check-'));
try {
  await generate(temporary);
  const expected = await generatedInventory(temporary);
  const actual = await generatedInventory(repositoryDirectory);
  const difference = describeDifference(expected, actual);
  if (difference !== '') {
    throw new Error(`Generated output is stale:\n${difference}`);
  }
  for (const path of LEGACY_GENERATED_PATHS) {
    await assertAbsent(repositoryDirectory, path);
  }
} finally {
  await rm(temporary, { recursive: true, force: true });
}

console.log('verified deterministic generated output');

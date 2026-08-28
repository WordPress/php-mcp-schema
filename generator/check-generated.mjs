#!/usr/bin/env node

import { execFileSync } from 'node:child_process';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const repositoryDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..');

execFileSync('git', ['diff', '--exit-code', '--', 'src/Generated'], {
  cwd: repositoryDirectory,
  stdio: 'inherit',
});

const status = execFileSync('git', ['status', '--porcelain', '--', 'src/Generated'], {
  cwd: repositoryDirectory,
  encoding: 'utf8',
});
if (status.trim() !== '') {
  throw new Error(`Generated output contains untracked files:\n${status}`);
}

console.log('verified deterministic generated output');

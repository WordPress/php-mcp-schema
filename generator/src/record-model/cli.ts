#!/usr/bin/env node

import { compileRevisions, SHIPPING_REVISIONS } from './compiler.js';
import { writePhpPackage } from './php-renderer.js';

try {
  const bundle = await compileRevisions(SHIPPING_REVISIONS);
  await writePhpPackage(bundle);

  const logicalTypes = Object.values(bundle.manifests).reduce(
    (total, manifest) => total + Object.keys(manifest.types).length,
    0
  );
  process.stdout.write(
    `Generated ${Object.keys(bundle.pool).length} unique descriptors for ${logicalTypes} revision-bound logical types.\n`
  );
} catch (error) {
  const message = error instanceof Error ? (error.stack ?? error.message) : String(error);
  process.stderr.write(`${message}\n`);
  process.exitCode = 1;
}

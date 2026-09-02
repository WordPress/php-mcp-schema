import { readFile } from 'node:fs/promises';
import { dirname, isAbsolute, relative, resolve, sep } from 'node:path';
import { fileURLToPath, pathToFileURL } from 'node:url';
import {
  assertSupportedSchemaDocument,
  sha256,
  stableJson,
  stableValue,
} from './schema-tools.mjs';

const generatorDirectory = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const repositoryDirectory = resolve(generatorDirectory, '..');
const sourceManifestPath = resolve(generatorDirectory, 'schema-sources.json');

function exactKeys(value, expected, label) {
  const actual = Object.keys(value).sort();
  const wanted = [...expected].sort();
  if (actual.join(',') !== wanted.join(',')) {
    throw new Error(`${label} keys must be ${wanted.join(',')}; got ${actual.join(',')}`);
  }
}

function resolveWithin(root, path, label) {
  const target = resolve(root, path);
  const relativePath = relative(root, target);
  if (relativePath === '' || relativePath === '..' || relativePath.startsWith(`..${sep}`) || isAbsolute(relativePath)) {
    throw new Error(`${label} escapes ${root}: ${path}`);
  }

  return target;
}

function cloneJson(value) {
  return JSON.parse(JSON.stringify(value));
}

function sameJson(left, right) {
  return JSON.stringify(stableValue(left)) === JSON.stringify(stableValue(right));
}

function pointerSegments(pointer) {
  if (typeof pointer !== 'string' || !pointer.startsWith('/') || pointer === '/') {
    throw new Error(`Patch pointer must be a non-root JSON pointer: ${pointer}`);
  }

  return pointer.slice(1).split('/').map((segment) => {
    if (/~(?![01])/u.test(segment)) throw new Error(`Patch pointer has an invalid escape: ${pointer}`);
    return segment.replaceAll('~1', '/').replaceAll('~0', '~');
  });
}

function replaceAtPointer(document, patch, revision) {
  const segments = pointerSegments(patch.pointer);
  let parent = document;
  for (const segment of segments.slice(0, -1)) {
    if (!parent || typeof parent !== 'object' || !(segment in parent)) {
      throw new Error(`MCP ${revision} patch path does not exist: ${patch.pointer}`);
    }
    parent = parent[segment];
  }

  const key = segments[segments.length - 1];
  if (!parent || typeof parent !== 'object' || !(key in parent)) {
    throw new Error(`MCP ${revision} patch target does not exist: ${patch.pointer}`);
  }
  if (!sameJson(parent[key], patch.oldValue)) {
    throw new Error(
      `MCP ${revision} patch ${patch.pointer} expected ${JSON.stringify(patch.oldValue)}, got ${JSON.stringify(parent[key])}`,
    );
  }

  parent[key] = cloneJson(patch.newValue);
}

function validatePatchDocument(patchDocument, revision, source) {
  if (!patchDocument || typeof patchDocument !== 'object' || Array.isArray(patchDocument)) {
    throw new Error(`MCP ${revision} patch document must be an object`);
  }
  exactKeys(patchDocument, ['formatVersion', 'patches', 'revision'], `MCP ${revision} patch document`);
  if (patchDocument.formatVersion !== 1) throw new Error(`MCP ${revision} patch format must be 1`);
  if (patchDocument.revision !== revision) throw new Error(`MCP ${revision} patch revision does not match`);
  if (!Array.isArray(patchDocument.patches)) throw new Error(`MCP ${revision} patches must be an array`);

  const sourcePrefix =
    `https://github.com/modelcontextprotocol/modelcontextprotocol/blob/${source.commit}/schema/${revision}/schema.ts#L`;
  const seenPointers = new Set();
  for (const [index, patch] of patchDocument.patches.entries()) {
    if (!patch || typeof patch !== 'object' || Array.isArray(patch)) {
      throw new Error(`MCP ${revision} patch ${index} must be an object`);
    }
    exactKeys(
      patch,
      ['newValue', 'oldValue', 'pointer', 'reason', 'source'],
      `MCP ${revision} patch ${index}`,
    );
    pointerSegments(patch.pointer);
    if (seenPointers.has(patch.pointer)) throw new Error(`MCP ${revision} repeats patch ${patch.pointer}`);
    seenPointers.add(patch.pointer);
    if (typeof patch.reason !== 'string' || patch.reason.trim() === '') {
      throw new Error(`MCP ${revision} patch ${patch.pointer} needs a rationale`);
    }
    if (typeof patch.source !== 'string' || !patch.source.startsWith(sourcePrefix)) {
      throw new Error(`MCP ${revision} patch ${patch.pointer} needs a pinned schema.ts line source`);
    }
  }
}

export async function loadCanonicalSchemas({ verifyRecordedDigests = true } = {}) {
  const sources = JSON.parse(await readFile(sourceManifestPath, 'utf8'));
  const documents = {};
  const metadata = {};

  for (const [revision, source] of Object.entries(sources)) {
    if (!source || typeof source !== 'object' || Array.isArray(source)) {
      throw new Error(`MCP ${revision} source entry must be an object`);
    }
    exactKeys(source, ['commit', 'patch', 'sha256', 'url'], `MCP ${revision} source`);
    exactKeys(source.patch, ['effectiveSha256', 'path', 'sha256'], `MCP ${revision} patch source`);

    const rawPath = resolve(repositoryDirectory, 'resources', 'schema', revision, 'schema.json');
    const rawContent = await readFile(rawPath);
    const rawSha256 = sha256(rawContent);
    if (rawSha256 !== source.sha256) {
      throw new Error(`MCP ${revision} raw schema digest mismatch: expected ${source.sha256}, got ${rawSha256}`);
    }

    let document;
    try {
      document = JSON.parse(rawContent.toString('utf8'));
    } catch (error) {
      throw new Error(`MCP ${revision} raw schema is not valid JSON: ${error.message}`);
    }

    const patchPath = resolveWithin(generatorDirectory, source.patch.path, `MCP ${revision} patch path`);
    const patchContent = await readFile(patchPath);
    const patchSha256 = sha256(patchContent);
    if (verifyRecordedDigests && patchSha256 !== source.patch.sha256) {
      throw new Error(
        `MCP ${revision} patch digest mismatch: expected ${source.patch.sha256}, got ${patchSha256}`,
      );
    }
    const patchDocument = JSON.parse(patchContent.toString('utf8'));
    validatePatchDocument(patchDocument, revision, source);

    const effectiveDocument = cloneJson(document);
    for (const patch of patchDocument.patches) replaceAtPointer(effectiveDocument, patch, revision);
    const effectiveSha256 = sha256(stableJson(effectiveDocument));
    if (verifyRecordedDigests && effectiveSha256 !== source.patch.effectiveSha256) {
      throw new Error(
        `MCP ${revision} effective schema digest mismatch: expected ${source.patch.effectiveSha256}, got ${effectiveSha256}`,
      );
    }

    const vocabulary = assertSupportedSchemaDocument(effectiveDocument, revision);
    documents[revision] = effectiveDocument;
    metadata[revision] = {
      rawSha256,
      effectiveSha256,
      patch: {
        path: source.patch.path,
        sha256: patchSha256,
        count: patchDocument.patches.length,
      },
      keywords: [...vocabulary.keywords].sort(),
    };
  }

  return { documents, metadata, sources };
}

if (process.argv[1] && import.meta.url === pathToFileURL(process.argv[1]).href) {
  if (!process.argv.includes('--print-digests')) throw new Error('Use --print-digests');
  const canonical = await loadCanonicalSchemas({ verifyRecordedDigests: false });
  const digests = Object.fromEntries(
    Object.entries(canonical.metadata).map(([revision, metadata]) => [revision, {
      patchSha256: metadata.patch.sha256,
      effectiveSha256: metadata.effectiveSha256,
    }]),
  );
  console.log(stableJson(digests).trim());
}

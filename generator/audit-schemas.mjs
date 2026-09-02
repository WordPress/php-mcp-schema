#!/usr/bin/env node

import { mkdir, readFile, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { loadCanonicalSchemas } from './lib/canonical-schema.mjs';
import {
  aggregateMethods,
  definitionInventory,
  effectiveObject,
  nativeSchemaCategories,
  rawKind,
  requiredCompatibilityDecisions,
  resolvedKind,
  schemaTypeSignature,
  sha256,
  stableJson,
  stableValue,
  structuralDefinition,
} from './lib/schema-tools.mjs';

const generatorDirectory = dirname(fileURLToPath(import.meta.url));
const repositoryDirectory = resolve(generatorDirectory, '..');
const outputPath = resolve(repositoryDirectory, 'resources', 'schema', 'compatibility-manifest.json');
const {
  documents,
  metadata: sourceMetadata,
  sources: sourceManifest,
} = await loadCanonicalSchemas();
const versions = Object.keys(sourceManifest);

function revisionSource(version) {
  return {
    sha256: sourceMetadata[version].rawSha256,
    effectiveSha256: sourceMetadata[version].effectiveSha256,
    patch: sourceMetadata[version].patch,
  };
}

function sameStructure(left, right) {
  return JSON.stringify(stableValue(structuralDefinition(left))) === JSON.stringify(stableValue(structuralDefinition(right)));
}

function messageAvailability(definitions) {
  return {
    clientToServer: {
      requests: aggregateMethods('ClientRequest', definitions),
      notifications: aggregateMethods('ClientNotification', definitions),
    },
    serverToClient: {
      requests: aggregateMethods('ServerRequest', definitions),
      notifications: aggregateMethods('ServerNotification', definitions),
    },
    embeddedInputs: aggregateMethods('InputRequest', definitions),
  };
}

function compareMessages(older, newer) {
  const changes = [];
  const groups = [
    ['clientToServer.requests', older.clientToServer.requests, newer.clientToServer.requests],
    ['clientToServer.notifications', older.clientToServer.notifications, newer.clientToServer.notifications],
    ['serverToClient.requests', older.serverToClient.requests, newer.serverToClient.requests],
    ['serverToClient.notifications', older.serverToClient.notifications, newer.serverToClient.notifications],
    ['embeddedInputs', older.embeddedInputs, newer.embeddedInputs],
  ];
  for (const [group, olderMethods, newerMethods] of groups) {
    const methods = [...new Set([...Object.keys(olderMethods), ...Object.keys(newerMethods)])].sort();
    for (const method of methods) {
      if (!(method in olderMethods)) changes.push({ group, method, change: 'added', definition: newerMethods[method] });
      else if (!(method in newerMethods)) changes.push({ group, method, change: 'removed', definition: olderMethods[method] });
      else if (olderMethods[method] !== newerMethods[method]) {
        changes.push({
          group,
          method,
          change: 'definition',
          definitionOlder: olderMethods[method],
          definitionNewer: newerMethods[method],
        });
      }
    }
  }
  return changes;
}

function comparePair(olderVersion, newerVersion) {
  const olderDefinitions = documents[olderVersion].$defs;
  const newerDefinitions = documents[newerVersion].$defs;
  const olderNames = new Set(Object.keys(olderDefinitions));
  const newerNames = new Set(Object.keys(newerDefinitions));
  const sharedNames = [...olderNames].filter((name) => newerNames.has(name)).sort();
  const definitionChanges = {
    added: [...newerNames].filter((name) => !olderNames.has(name)).sort(),
    removed: [...olderNames].filter((name) => !newerNames.has(name)).sort(),
    unchanged: sharedNames.filter((name) => sameStructure(olderDefinitions[name], newerDefinitions[name])),
    changed: sharedNames.filter((name) => !sameStructure(olderDefinitions[name], newerDefinitions[name])),
    kindChanges: sharedNames
      .filter((name) => rawKind(olderDefinitions[name]) !== rawKind(newerDefinitions[name]))
      .map((name) => ({
        name,
        [olderVersion]: rawKind(olderDefinitions[name]),
        [newerVersion]: rawKind(newerDefinitions[name]),
        resolvedOlder: resolvedKind(name, olderDefinitions),
        resolvedNewer: resolvedKind(name, newerDefinitions),
      })),
  };

  const objectChanges = [];
  const fieldChanges = [];
  const getterChanges = [];
  for (const name of sharedNames) {
    if (resolvedKind(name, olderDefinitions) !== 'object' || resolvedKind(name, newerDefinitions) !== 'object') {
      continue;
    }
    const olderObject = effectiveObject(name, olderDefinitions);
    const newerObject = effectiveObject(name, newerDefinitions);
    if (!sameStructure(olderObject.additionalProperties, newerObject.additionalProperties)) {
      objectChanges.push({
        name,
        change: 'additionalProperties',
        older: olderObject.additionalProperties === undefined ? true : olderObject.additionalProperties,
        newer: newerObject.additionalProperties === undefined ? true : newerObject.additionalProperties,
      });
    }
    const fields = [...new Set([...Object.keys(olderObject.properties), ...Object.keys(newerObject.properties)])].sort();
    const changes = [];
    for (const field of fields) {
      const olderSchema = olderObject.properties[field];
      const newerSchema = newerObject.properties[field];
      const requiredOlder = olderObject.required.has(field);
      const requiredNewer = newerObject.required.has(field);
      const typeOlder = olderSchema ? schemaTypeSignature(olderSchema, olderDefinitions) : null;
      const typeNewer = newerSchema ? schemaTypeSignature(newerSchema, newerDefinitions) : null;
      const nativeCategoriesOlder = olderSchema ? nativeSchemaCategories(olderSchema, olderDefinitions) : null;
      const nativeCategoriesNewer = newerSchema ? nativeSchemaCategories(newerSchema, newerDefinitions) : null;
      let change = null;
      if (!olderSchema || !newerSchema) change = olderSchema ? 'removed' : 'added';
      else if (!sameStructure(olderSchema, newerSchema)) change = 'schema';
      else if (requiredOlder !== requiredNewer) change = 'requiredness';
      if (!change) continue;

      changes.push({
        field,
        change,
        requiredOlder,
        requiredNewer,
        typeOlder,
        typeNewer,
        nativeCategoriesOlder,
        nativeCategoriesNewer,
      });
      if (
        JSON.stringify(typeOlder) !== JSON.stringify(typeNewer) ||
        requiredOlder !== requiredNewer
      ) {
        getterChanges.push({
          name,
          field,
          requiredOlder,
          requiredNewer,
          typeOlder,
          typeNewer,
          nativeCategoriesOlder,
          nativeCategoriesNewer,
        });
      }
    }
    if (changes.length > 0) fieldChanges.push({ name, changes });
  }

  const availability = {
    [olderVersion]: messageAvailability(olderDefinitions),
    [newerVersion]: messageAvailability(newerDefinitions),
  };
  const messageChanges = compareMessages(availability[olderVersion], availability[newerVersion]);
  const structuralReview = {
    revisions: {
      [olderVersion]: revisionSource(olderVersion),
      [newerVersion]: revisionSource(newerVersion),
    },
    definitionChanges,
    objectChanges,
    fieldChanges,
    getterChanges,
    messageAvailability: availability,
    messageChanges,
  };
  return {
    older: olderVersion,
    newer: newerVersion,
    structuralFingerprint: sha256(JSON.stringify(stableValue(structuralReview))),
    ...structuralReview,
  };
}

function exactKeys(value, expected, label) {
  const actual = Object.keys(value).sort();
  const wanted = [...expected].sort();
  if (actual.join(',') !== wanted.join(',')) {
    throw new Error(`${label} keys must be ${wanted.join(',')}; got ${actual.join(',')}`);
  }
}

const pairComparisons = {};
for (let olderIndex = 0; olderIndex < versions.length; olderIndex += 1) {
  for (let newerIndex = olderIndex + 1; newerIndex < versions.length; newerIndex += 1) {
    const olderVersion = versions[olderIndex];
    const newerVersion = versions[newerIndex];
    const pair = `${olderVersion}__${newerVersion}`;
    pairComparisons[pair] = comparePair(olderVersion, newerVersion);
  }
}

if (process.argv.includes('--print-fingerprint')) {
  const fingerprints = Object.fromEntries(
    Object.entries(pairComparisons).map(([pair, comparison]) => [pair, comparison.structuralFingerprint]),
  );
  console.log(versions.length === 2 ? Object.values(fingerprints)[0] : stableJson(fingerprints).trim());
  process.exit(0);
}
if (process.argv.includes('--print-required-decisions')) {
  const decisions = Object.fromEntries(
    Object.entries(pairComparisons).map(([pair, comparison]) => [
      pair,
      requiredCompatibilityDecisions(
        comparison.older,
        documents[comparison.older].$defs,
        comparison.newer,
        documents[comparison.newer].$defs,
      ),
    ]),
  );
  console.log(stableJson(decisions).trim());
  process.exit(0);
}

const comparisons = {};
for (const [pair, comparison] of Object.entries(pairComparisons)) {
  const classificationPath = resolve(generatorDirectory, 'compatibility', `${pair}.json`);
  const classification = JSON.parse(await readFile(classificationPath, 'utf8'));
  exactKeys(
    classification,
    ['decisions', 'formatVersion', 'reviewedStructuralFingerprint'],
    `${pair} compatibility review`,
  );
  if (classification.formatVersion !== 1) throw new Error(`${pair} compatibility review format must be 1`);
  if (classification.reviewedStructuralFingerprint !== comparison.structuralFingerprint) {
    throw new Error(
      `${pair} classification is stale: expected ${classification.reviewedStructuralFingerprint}, got ${comparison.structuralFingerprint}`,
    );
  }
  const expected = requiredCompatibilityDecisions(
    comparison.older,
    documents[comparison.older].$defs,
    comparison.newer,
    documents[comparison.newer].$defs,
  );
  const actual = classification.decisions;
  const expectedKeys = Object.keys(expected);
  const actualKeys = Object.keys(actual).sort();
  const missing = expectedKeys.filter((key) => !(key in actual));
  const extra = actualKeys.filter((key) => !(key in expected));
  if (missing.length > 0 || extra.length > 0) {
    throw new Error(`${pair} classifications mismatch; missing=${missing.join(',')} extra=${extra.join(',')}`);
  }
  const reviewDecisions = {};
  for (const key of expectedKeys) {
    if (!actual[key] || typeof actual[key] !== 'object' || Array.isArray(actual[key])) {
      throw new Error(`${pair} decision ${key} must be an object`);
    }
    exactKeys(actual[key], ['classification', 'rationale'], `${pair} decision ${key}`);
    if (actual[key].classification !== expected[key].classification) {
      throw new Error(
        `${pair} decision ${key} must use classification ${expected[key].classification}`,
      );
    }
    if (typeof actual[key].rationale !== 'string' || actual[key].rationale.trim().length < 20) {
      throw new Error(`${pair} decision ${key} must carry a substantive rationale`);
    }
    reviewDecisions[key] = { ...expected[key], rationale: actual[key].rationale };
  }
  comparisons[pair] = { ...comparison, reviewDecisions };
}

const manifest = {
  formatVersion: 3,
  revisions: Object.fromEntries(
    versions.map((version) => [version, {
      commit: sourceManifest[version].commit,
      ...revisionSource(version),
      definitionCount: Object.keys(documents[version].$defs).length,
      definitions: definitionInventory(documents[version].$defs),
    }]),
  ),
  comparisons,
};
const serialized = stableJson(manifest);

if (process.argv.includes('--check')) {
  const current = await readFile(outputPath, 'utf8');
  if (current !== serialized) throw new Error('Compatibility manifest is not deterministic; run npm run audit');
  console.log(`verified ${Object.keys(comparisons).length} compatibility comparison(s)`);
} else {
  await mkdir(dirname(outputPath), { recursive: true });
  await writeFile(outputPath, serialized);
  console.log(`generated ${Object.keys(comparisons).length} compatibility comparison(s)`);
}

#!/usr/bin/env node

import { mkdir, mkdtemp, readFile, rename, rm, writeFile } from 'node:fs/promises';
import { dirname, isAbsolute, relative, resolve, sep } from 'node:path';
import { fileURLToPath, pathToFileURL } from 'node:url';
import {
  GENERATED_DIRECTORIES,
  GENERATED_FILES,
} from './lib/generated-layout.mjs';
import { loadCanonicalSchemas } from './lib/canonical-schema.mjs';
import { phpFile, phpLiteral, phpString } from './lib/php-code.mjs';
import {
  SUPPORTED_SCHEMA_KEYWORDS,
  aggregateMethods,
  effectiveObject,
  nominalAllOfRecordName,
  publicSymbol,
  rawKind,
  referenceName,
  requiredCompatibilityDecisions,
  stableValue,
} from './lib/schema-tools.mjs';

const generatorDirectory = dirname(fileURLToPath(import.meta.url));
const repositoryDirectory = resolve(generatorDirectory, '..');
const compatibility = JSON.parse(
  await readFile(resolve(repositoryDirectory, 'resources', 'schema', 'compatibility-manifest.json'), 'utf8'),
);

function versionClass(version) {
  return `V${version.replaceAll('-', '_')}`;
}

function className(name) {
  if (!/^[A-Za-z][A-Za-z0-9]*$/u.test(name)) throw new Error(`Invalid PHP class name ${name}`);
  return name;
}

function getterName(field) {
  const words = field.replace(/^_+/u, '').split(/[^A-Za-z0-9]+/u).filter(Boolean);
  const suffix = words
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join('');
  if (suffix === '') throw new Error(`Cannot generate getter for ${field}`);
  return `get${suffix}`;
}

function contractMembers(name, definitions, seen = new Set()) {
  if (seen.has(name)) return [];
  const schema = definitions[name];
  if (!schema || rawKind(schema) !== 'union') return [];
  const members = [];
  for (const member of schema.anyOf) {
    if (!member.$ref) continue;
    const memberName = referenceName(member.$ref);
    const symbol = publicSymbol(memberName, definitions);
    if (symbol === 'record') members.push(memberName);
    else if (symbol === 'contract') members.push(...contractMembers(memberName, definitions, new Set([...seen, name])));
  }
  return [...new Set(members)].sort();
}

function literalDocType(value) {
  if (typeof value === 'string') return `'${value.replaceAll("'", "\\'")}'`;
  if (typeof value === 'number') return String(value);
  if (typeof value === 'boolean') return value ? 'true' : 'false';
  if (value === null) return 'null';
  return 'mixed';
}

function docTypes(schema, definitions, seen = new Set()) {
  if (schema.$ref) {
    const name = referenceName(schema.$ref);
    if (seen.has(name)) return ['mixed'];
    const symbol = publicSymbol(name, definitions);
    if (symbol === 'record') return [`\\WP\\McpSchema\\Record\\${className(name)}`];
    if (symbol === 'contract') return [`\\WP\\McpSchema\\Contract\\${className(name)}`];
    return docTypes(definitions[name], definitions, new Set([...seen, name]));
  }
  if (schema.anyOf) {
    return [...new Set(schema.anyOf.flatMap((member) => docTypes(member, definitions, seen)))].sort();
  }
  const nominalAllOf = nominalAllOfRecordName(schema, definitions);
  if (nominalAllOf) return [`\\WP\\McpSchema\\Record\\${className(nominalAllOf)}`];
  if (schema.allOf || schema.properties || schema.type === 'object') return ['\\stdClass'];
  if (schema.enum) return [...new Set(schema.enum.map(literalDocType))].sort();
  if (schema.const !== undefined) return [literalDocType(schema.const)];
  const types = Array.isArray(schema.type) ? schema.type : schema.type ? [schema.type] : [];
  if (types.length === 0) return ['mixed'];
  return [...new Set(types.flatMap((type) => {
    if (type === 'string') return ['string'];
    if (type === 'integer') return ['float', 'int'];
    if (type === 'number') return ['float', 'int'];
    if (type === 'boolean') return ['bool'];
    if (type === 'null') return ['null'];
    if (type === 'object') return ['\\stdClass'];
    if (type === 'array') {
      const itemTypes = schema.items ? docTypes(schema.items, definitions, seen) : ['mixed'];
      return [`array<int, ${itemTypes.join('|')}>`];
    }
    return ['mixed'];
  }))].sort();
}

function nativeCategory(type) {
  if (type === 'string' || /^'.*'$/u.test(type)) return 'string';
  if (/^-?\d+(?:\.\d+)?$/u.test(type)) return 'number';
  if (type === 'int') return 'int';
  if (type === 'float') return 'float';
  if (type === 'bool' || type === 'true' || type === 'false') return 'bool';
  if (type.startsWith('array<')) return 'array';
  if (type === '\\stdClass' || type.startsWith('\\WP\\')) return type;
  return null;
}

function getterContract(types) {
  const unique = [...new Set(types)].sort();
  const nullable = unique.includes('null');
  const nonNull = unique.filter((type) => type !== 'null');
  const categories = [...new Set(nonNull.map(nativeCategory).filter(Boolean))];
  let native = null;
  if (
    categories.length === 1 &&
    categories[0] !== 'number' &&
    nonNull.every((type) => nativeCategory(type) === categories[0])
  ) {
    native = categories[0];
    if (nullable) native = `?${native}`;
  }
  return { doc: unique.join('|') || 'mixed', native };
}

function renderGetter(field, types, declaredVersions = []) {
  const method = getterName(field);
  const contract = getterContract(types);
  const returnType = contract.native ? `: ${contract.native}` : '';
  const declared = declaredVersions.length > 0
    ? `     * Declared in: ${declaredVersions.join(', ')}.\n     *\n`
    : '';
  return `    /**\n${declared}     * @return ${contract.doc}\n     */\n    public function ${method}()${returnType}\n    {\n        /** @var ${contract.doc} $value */\n        $value = $this->declaredValue(${phpString(field)});\n\n        return $value;\n    }`;
}

function siblingSymbolDoc(name, kind, versions, siblingKind, siblingVersions) {
  const siblingNamespace = siblingKind === 'record' ? 'Record' : 'Contract';
  const role = kind === 'record' ? 'record' : 'union construction root';
  const siblingRole = siblingKind === 'record' ? 'record' : 'union construction root';
  return `/**\n * Canonical ${role} available in: ${versions.join(', ')}.\n *\n * The same short name is also used by \\WP\\McpSchema\\${siblingNamespace}\\${name},\n * a canonical ${siblingRole} available in: ${siblingVersions.join(', ')}.\n */\n`;
}

function enumConstants(values) {
  const names = new Map();
  return values.map((value) => {
    const raw = String(value)
      .replace(/([a-z0-9])([A-Z])/gu, '$1_$2')
      .replace(/[^A-Za-z0-9]+/gu, '_')
      .replace(/^_+|_+$/gu, '')
      .toUpperCase();
    const base = /^[A-Z_]/u.test(raw) ? raw : `VALUE_${raw}`;
    if (!base) throw new Error(`Cannot generate constant for ${JSON.stringify(value)}`);
    const count = (names.get(base) || 0) + 1;
    names.set(base, count);
    const name = count === 1 ? base : `${base}_${count}`;
    return `    public const ${name} = ${phpLiteral(value)};`;
  });
}

function catalogBody(version, document, availability) {
  return `final class ${versionClass(version)}\n{\n    public const VERSION = ${phpString(version)};\n\n    /**\n     * @return array<string, mixed>\n     */\n    public static function document(): array\n    {\n        return ${phpLiteral(stableValue(document), 2)};\n    }\n\n    /**\n     * @return array{\n     *   clientToServer: array{requests: array<string, string>, notifications: array<string, string>},\n     *   serverToClient: array{requests: array<string, string>, notifications: array<string, string>},\n     *   embeddedInputs: array<string, string>\n     * }\n     */\n    public static function messageAvailability(): array\n    {\n        return ${phpLiteral(stableValue(availability), 2)};\n    }\n}`;
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

export function assertCompatibilityDecisions(documents, manifest = compatibility) {
  const versions = Object.keys(documents);
  const expectedComparisonCount = (versions.length * (versions.length - 1)) / 2;
  if (Object.keys(manifest.comparisons).length !== expectedComparisonCount) {
    throw new Error('Compatibility manifest does not cover every supported revision pair');
  }

  for (let olderIndex = 0; olderIndex < versions.length; olderIndex += 1) {
    for (let newerIndex = olderIndex + 1; newerIndex < versions.length; newerIndex += 1) {
      const olderVersion = versions[olderIndex];
      const newerVersion = versions[newerIndex];
      const pair = `${olderVersion}__${newerVersion}`;
      const comparison = manifest.comparisons[pair];
      if (!comparison) throw new Error(`Compatibility manifest is missing ${pair}`);
      const expected = requiredCompatibilityDecisions(
        olderVersion,
        documents[olderVersion].$defs,
        newerVersion,
        documents[newerVersion].$defs,
      );
      const actual = comparison.reviewDecisions || {};
      const expectedKeys = Object.keys(expected);
      const actualKeys = Object.keys(actual).sort();
      const missing = expectedKeys.filter((key) => !(key in actual));
      const extra = actualKeys.filter((key) => !(key in expected));
      if (missing.length > 0 || extra.length > 0) {
        throw new Error(`${pair} getter/kind decisions mismatch; missing=${missing.join(',')} extra=${extra.join(',')}`);
      }
      for (const key of expectedKeys) {
        if (
          actual[key].classification !== expected[key].classification ||
          JSON.stringify(stableValue(actual[key].evidence)) !== JSON.stringify(stableValue(expected[key].evidence)) ||
          typeof actual[key].rationale !== 'string' ||
          actual[key].rationale.trim().length < 20
        ) {
          throw new Error(`${pair} has an invalid getter/kind decision for ${key}`);
        }
      }
    }
  }
}

function resolveWithin(root, path) {
  const target = resolve(root, path);
  const relativePath = relative(root, target);
  if (relativePath === '' || relativePath === '..' || relativePath.startsWith(`..${sep}`) || isAbsolute(relativePath)) {
    throw new Error(`Generated path escapes output: ${path}`);
  }

  return target;
}

async function writeGenerated(output, path, contents) {
  const target = resolveWithin(output, path);
  await mkdir(dirname(target), { recursive: true });
  await writeFile(target, contents);
}

async function replaceGeneratedOutput(staging, output) {
  for (const path of GENERATED_DIRECTORIES) {
    const source = resolveWithin(staging, path);
    const target = resolveWithin(output, path);
    await rm(target, { recursive: true, force: true });
    await mkdir(dirname(target), { recursive: true });
    await rename(source, target);
  }

  for (const path of GENERATED_FILES) {
    const source = resolveWithin(staging, path);
    const target = resolveWithin(output, path);
    await rm(target, { force: true });
    await mkdir(dirname(target), { recursive: true });
    await rename(source, target);
  }

}

export async function generate(outputDirectory = repositoryDirectory) {
  const output = resolve(outputDirectory);
  if (dirname(output) === output) throw new Error(`Generator output cannot be a filesystem root: ${output}`);
  await mkdir(output, { recursive: true });
  const staging = await mkdtemp(resolve(output, '.php-mcp-schema-stage-'));

  try {
    const { documents } = await loadCanonicalSchemas();

    assertCompatibilityDecisions(documents);
    const availabilityByVersion = Object.fromEntries(
      Object.entries(documents).map(([version, document]) => [version, messageAvailability(document.$defs)]),
    );

    for (const [version, document] of Object.entries(documents)) {
      await writeGenerated(
        staging,
        `src/Internal/Catalog/${versionClass(version)}.php`,
        phpFile('WP\\McpSchema\\Internal\\Catalog', catalogBody(version, document, availabilityByVersion[version])),
      );
    }

    const recordAvailability = {};
    const contractAvailability = {};
    const valueAvailability = {};
    const recordFields = {};
    const recordContracts = {};
    const enumValues = {};

    for (const [version, document] of Object.entries(documents)) {
      const definitions = document.$defs;
      for (const name of Object.keys(definitions).sort()) {
        const symbol = publicSymbol(name, definitions);
        if (symbol === 'record') {
          recordAvailability[name] ||= [];
          recordAvailability[name].push(version);
          recordFields[name] ||= {};
          const object = effectiveObject(name, definitions);
          for (const [field, schema] of Object.entries(object.properties)) {
            recordFields[name][field] ||= { types: [], requiredEverywhere: true, appearances: [] };
            const entry = recordFields[name][field];
            entry.types.push(...docTypes(schema, definitions));
            entry.requiredEverywhere = entry.requiredEverywhere && object.required.has(field);
            entry.appearances.push(version);
          }
        } else if (symbol === 'contract') {
          contractAvailability[name] ||= [];
          contractAvailability[name].push(version);
          for (const member of contractMembers(name, definitions)) {
            recordContracts[member] ||= new Set();
            recordContracts[member].add(name);
          }
        } else if (symbol === 'value') {
          valueAvailability[name] ||= [];
          valueAvailability[name].push(version);
          enumValues[name] ||= [];
          enumValues[name].push(...definitions[name].enum);
        }
      }
    }

    for (const [name, versions] of Object.entries(recordAvailability).sort(([a], [b]) => a.localeCompare(b))) {
      const methods = [];
      const getterNames = new Map();
      for (const [field, entry] of Object.entries(recordFields[name] || {}).sort(([a], [b]) => a.localeCompare(b))) {
        if (entry.appearances.length !== versions.length || !entry.requiredEverywhere) entry.types.push('null');
        const method = getterName(field).toLowerCase();
        if (getterNames.has(method)) throw new Error(`${name} getter collision: ${field} and ${getterNames.get(method)}`);
        getterNames.set(method, field);
        methods.push(renderGetter(
          field,
          entry.types,
          entry.appearances.length === versions.length ? [] : entry.appearances,
        ));
      }
      const implementsList = [...(recordContracts[name] || new Set())]
        .sort()
        .map((contract) => `\\WP\\McpSchema\\Contract\\${contract}`);
      const implementsClause = implementsList.length > 0 ? ` implements ${implementsList.join(', ')}` : '';
      const classDoc = contractAvailability[name]
        ? siblingSymbolDoc(name, 'record', versions, 'contract', contractAvailability[name])
        : '';
      const body = `${classDoc}final class ${className(name)} extends \\WP\\McpSchema\\Record${implementsClause}\n{\n    public const DEFINITION = ${phpString(name)};${methods.length > 0 ? `\n\n${methods.join('\n\n')}` : ''}\n}`;
      await writeGenerated(staging, `src/Record/${name}.php`, phpFile('WP\\McpSchema\\Record', body));
    }

    for (const name of Object.keys(contractAvailability).sort()) {
      const classDoc = recordAvailability[name]
        ? siblingSymbolDoc(
          name,
          'contract',
          contractAvailability[name],
          'record',
          recordAvailability[name],
        )
        : '';
      const body = `${classDoc}interface ${className(name)} extends \\JsonSerializable\n{\n}`;
      await writeGenerated(staging, `src/Contract/${name}.php`, phpFile('WP\\McpSchema\\Contract', body));
    }

    for (const name of Object.keys(valueAvailability).sort()) {
      const constants = enumConstants([...new Set(enumValues[name].map((value) => JSON.stringify(value)))]
        .map((value) => JSON.parse(value)));
      const body = `final class ${className(name)}\n{\n${constants.join('\n')}\n\n    private function __construct()\n    {\n    }\n}`;
      await writeGenerated(staging, `src/Value/${name}.php`, phpFile('WP\\McpSchema\\Value', body));
    }

    const recordMap = Object.fromEntries(
      Object.entries(recordAvailability)
        .sort(([a], [b]) => a.localeCompare(b))
        .map(([name, versions]) => [`WP\\McpSchema\\Record\\${name}`, { definition: name, versions }]),
    );
    const contractMap = Object.fromEntries(
      Object.entries(contractAvailability)
        .sort(([a], [b]) => a.localeCompare(b))
        .map(([name, versions]) => [`WP\\McpSchema\\Contract\\${name}`, { definition: name, versions }]),
    );
    const registryBody = `final class TypeRegistry\n{\n    /**\n     * @return array<class-string, array{definition: string, versions: array<int, string>}>\n     */\n    public static function records(): array\n    {\n        return ${phpLiteral(recordMap, 2)};\n    }\n\n    /**\n     * @return array<class-string, array{definition: string, versions: array<int, string>}>\n     */\n    public static function contracts(): array\n    {\n        return ${phpLiteral(contractMap, 2)};\n    }\n\n    /**\n     * @return array<int, string>\n     */\n    public static function schemaKeywords(): array\n    {\n        return ${phpLiteral(SUPPORTED_SCHEMA_KEYWORDS, 2)};\n    }\n}`;
    await writeGenerated(
      staging,
      'src/Internal/TypeRegistry.php',
      phpFile('WP\\McpSchema\\Internal', registryBody),
    );

    await replaceGeneratedOutput(staging, output);

    return {
      output,
      catalogs: Object.keys(documents).length,
      records: Object.keys(recordAvailability).length,
      contracts: Object.keys(contractAvailability).length,
      values: Object.keys(valueAvailability).length,
    };
  } finally {
    await rm(staging, { recursive: true, force: true });
  }
}

if (import.meta.url === pathToFileURL(process.argv[1]).href) {
  const outputIndex = process.argv.indexOf('--output');
  const output = outputIndex === -1 ? undefined : process.argv[outputIndex + 1];
  if (outputIndex !== -1 && !output) throw new Error('--output requires a directory');
  const result = await generate(output);
  console.log(
    `generated ${result.catalogs} catalogs, ${result.records} records, ${result.contracts} contracts, and ${result.values} value classes`,
  );
}

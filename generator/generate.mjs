#!/usr/bin/env node

import { mkdir, readFile, rm, writeFile } from 'node:fs/promises';
import { basename, dirname, resolve } from 'node:path';
import { fileURLToPath, pathToFileURL } from 'node:url';
import { phpFile, phpLiteral, phpString } from './lib/php-code.mjs';
import {
  aggregateMethods,
  effectiveObject,
  publicSymbol,
  rawKind,
  referenceName,
  resolvedKind,
  stableValue,
} from './lib/schema-tools.mjs';

const generatorDirectory = dirname(fileURLToPath(import.meta.url));
const repositoryDirectory = resolve(generatorDirectory, '..');
const sourceManifest = JSON.parse(await readFile(resolve(generatorDirectory, 'schema-sources.json'), 'utf8'));
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
  if (typeof value === 'number') return Number.isInteger(value) ? String(value) : String(value);
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
  if (type === 'int' || /^-?\d+(?:\.\d+)?$/u.test(type)) return 'int';
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
  if (categories.length === 1 && nonNull.every((type) => nativeCategory(type) === categories[0])) {
    native = categories[0];
    if (nullable) native = `?${native}`;
  }
  return { doc: unique.join('|') || 'mixed', native };
}

function renderGetter(field, types) {
  const method = getterName(field);
  const contract = getterContract(types);
  const returnType = contract.native ? `: ${contract.native}` : '';
  return `    /**\n     * @return ${contract.doc}\n     */\n    public function ${method}()${returnType}\n    {\n        /** @var ${contract.doc} $value */\n        $value = $this->declaredValue(${phpString(field)});\n\n        return $value;\n    }`;
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

async function writeGenerated(output, relative, contents) {
  const target = resolve(output, relative);
  if (!target.startsWith(`${output}/`)) throw new Error(`Generated path escapes output: ${relative}`);
  await mkdir(dirname(target), { recursive: true });
  await writeFile(target, contents);
}

export async function generate(outputDirectory = resolve(repositoryDirectory, 'src', 'Generated')) {
  const output = resolve(outputDirectory);
  if (basename(output) !== 'Generated') throw new Error(`Generator output must end in Generated: ${output}`);
  await rm(output, { recursive: true, force: true });
  await mkdir(output, { recursive: true });

  const documents = {};
  for (const version of Object.keys(sourceManifest)) {
    documents[version] = JSON.parse(
      await readFile(resolve(repositoryDirectory, 'resources', 'schema', version, 'schema.json'), 'utf8'),
    );
  }

  const expectedComparisonCount = (Object.keys(sourceManifest).length * (Object.keys(sourceManifest).length - 1)) / 2;
  if (Object.keys(compatibility.comparisons).length !== expectedComparisonCount) {
    throw new Error('Compatibility manifest does not cover every supported revision pair');
  }
  const availabilityByVersion = Object.fromEntries(
    Object.entries(documents).map(([version, document]) => [version, messageAvailability(document.$defs)]),
  );

  for (const [version, document] of Object.entries(documents)) {
    await writeGenerated(
      output,
      `Catalog/${versionClass(version)}.php`,
      phpFile('WP\\McpSchema\\Generated\\Catalog', catalogBody(version, document, availabilityByVersion[version])),
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
          recordFields[name][field] ||= { types: [], requiredEverywhere: true, appearances: 0 };
          const entry = recordFields[name][field];
          entry.types.push(...docTypes(schema, definitions));
          entry.requiredEverywhere = entry.requiredEverywhere && object.required.has(field);
          entry.appearances += 1;
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
      if (entry.appearances !== versions.length || !entry.requiredEverywhere) entry.types.push('null');
      const method = getterName(field).toLowerCase();
      if (getterNames.has(method)) throw new Error(`${name} getter collision: ${field} and ${getterNames.get(method)}`);
      getterNames.set(method, field);
      methods.push(renderGetter(field, entry.types));
    }
    const implementsList = [...(recordContracts[name] || new Set())]
      .sort()
      .map((contract) => `\\WP\\McpSchema\\Contract\\${contract}`);
    const implementsClause = implementsList.length > 0 ? ` implements ${implementsList.join(', ')}` : '';
    const body = `final class ${className(name)} extends \\WP\\McpSchema\\Record${implementsClause}\n{\n    public const DEFINITION = ${phpString(name)};${methods.length > 0 ? `\n\n${methods.join('\n\n')}` : ''}\n}`;
    await writeGenerated(output, `Record/${name}.php`, phpFile('WP\\McpSchema\\Record', body));
  }

  for (const name of Object.keys(contractAvailability).sort()) {
    const body = `interface ${className(name)} extends \\JsonSerializable\n{\n}`;
    await writeGenerated(output, `Contract/${name}.php`, phpFile('WP\\McpSchema\\Contract', body));
  }

  for (const name of Object.keys(valueAvailability).sort()) {
    const constants = enumConstants([...new Set(enumValues[name].map((value) => JSON.stringify(value)))]
      .map((value) => JSON.parse(value)));
    const body = `final class ${className(name)}\n{\n${constants.join('\n')}\n\n    private function __construct()\n    {\n    }\n}`;
    await writeGenerated(output, `Value/${name}.php`, phpFile('WP\\McpSchema\\Value', body));
  }

  const registry = {
    records: Object.fromEntries(Object.entries(recordAvailability).sort(([a], [b]) => a.localeCompare(b))),
    contracts: Object.fromEntries(Object.entries(contractAvailability).sort(([a], [b]) => a.localeCompare(b))),
    values: Object.fromEntries(Object.entries(valueAvailability).sort(([a], [b]) => a.localeCompare(b))),
  };
  const recordMap = Object.fromEntries(
    Object.entries(registry.records).map(([name, versions]) => [`WP\\McpSchema\\Record\\${name}`, { definition: name, versions }]),
  );
  const contractMap = Object.fromEntries(
    Object.entries(registry.contracts).map(([name, versions]) => [`WP\\McpSchema\\Contract\\${name}`, { definition: name, versions }]),
  );
  const registryBody = `final class Registry\n{\n    /**\n     * @return array<class-string, array{definition: string, versions: array<int, string>}>\n     */\n    public static function records(): array\n    {\n        return ${phpLiteral(recordMap, 2)};\n    }\n\n    /**\n     * @return array<class-string, array{definition: string, versions: array<int, string>}>\n     */\n    public static function contracts(): array\n    {\n        return ${phpLiteral(contractMap, 2)};\n    }\n}`;
  await writeGenerated(output, 'Registry.php', phpFile('WP\\McpSchema\\Generated', registryBody));

  return {
    output,
    catalogs: Object.keys(documents).length,
    records: Object.keys(recordAvailability).length,
    contracts: Object.keys(contractAvailability).length,
    values: Object.keys(valueAvailability).length,
  };
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

import { createHash } from 'node:crypto';

export const SUPPORTED_SCHEMA_KEYWORDS = Object.freeze([
  '$ref',
  'additionalProperties',
  'allOf',
  'anyOf',
  'const',
  'description',
  'enum',
  'format',
  'items',
  'maxItems',
  'maximum',
  'minimum',
  'properties',
  'required',
  'type',
]);

export function sha256(content) {
  return createHash('sha256').update(content).digest('hex');
}

export function stableValue(value) {
  if (Array.isArray(value)) return value.map(stableValue);
  if (!value || typeof value !== 'object') return value;

  return Object.fromEntries(
    Object.keys(value)
      .sort()
      .map((key) => [key, stableValue(value[key])]),
  );
}

export function stableJson(value) {
  return `${JSON.stringify(stableValue(value), null, 2)}\n`;
}

function auditInlineSchema(schema, path, definitions, state) {
  if (!schema || typeof schema !== 'object' || Array.isArray(schema)) {
    throw new Error(`${path} must be a schema object`);
  }

  const keywords = Object.keys(schema);
  for (const keyword of keywords) {
    if (!SUPPORTED_SCHEMA_KEYWORDS.includes(keyword)) {
      throw new Error(`${path} uses unsupported keyword ${keyword}`);
    }
    state.keywords.add(keyword);
  }

  if ('$ref' in schema) {
    const name = referenceName(schema.$ref);
    if (!(name in definitions)) throw new Error(`${path} references unknown definition ${name}`);
    const siblings = keywords.filter((keyword) => keyword !== '$ref' && keyword !== 'description');
    if (siblings.length > 0) {
      throw new Error(`${path} has unsupported $ref siblings: ${siblings.join(',')}`);
    }
    state.references.add(schema.$ref);
  }

  if ('properties' in schema) {
    if (!schema.properties || typeof schema.properties !== 'object' || Array.isArray(schema.properties)) {
      throw new Error(`${path}/properties must be an object`);
    }
    for (const [name, child] of Object.entries(schema.properties)) {
      auditInlineSchema(child, `${path}/properties/${name}`, definitions, state);
    }
  }
  if (schema.additionalProperties && typeof schema.additionalProperties === 'object') {
    auditInlineSchema(schema.additionalProperties, `${path}/additionalProperties`, definitions, state);
  }
  if ('items' in schema) auditInlineSchema(schema.items, `${path}/items`, definitions, state);
  for (const combinator of ['allOf', 'anyOf']) {
    if (!(combinator in schema)) continue;
    if (!Array.isArray(schema[combinator])) throw new Error(`${path}/${combinator} must be an array`);
    schema[combinator].forEach((child, index) => {
      auditInlineSchema(child, `${path}/${combinator}/${index}`, definitions, state);
    });
  }
}

export function assertSupportedSchemaDocument(document, revision = 'unknown') {
  if (!document || typeof document !== 'object' || Array.isArray(document)) {
    throw new Error(`MCP ${revision} canonical schema must be an object`);
  }
  if (document.$schema !== 'https://json-schema.org/draft/2020-12/schema') {
    throw new Error(`MCP ${revision} is not a Draft 2020-12 schema`);
  }
  if (!document.$defs || typeof document.$defs !== 'object' || Array.isArray(document.$defs)) {
    throw new Error(`MCP ${revision} has no definition map`);
  }
  const topLevel = Object.keys(document).sort();
  if (topLevel.join(',') !== '$defs,$schema') {
    throw new Error(`MCP ${revision} has unsupported top-level keys: ${topLevel.join(',')}`);
  }

  const state = { keywords: new Set(), references: new Set() };
  for (const [name, schema] of Object.entries(document.$defs)) {
    auditInlineSchema(schema, `#/$defs/${name}`, document.$defs, state);
  }

  return state;
}

export function rawKind(schema) {
  if (schema.anyOf) return 'union';
  if (schema.allOf) return 'intersection';
  if (schema.$ref) return 'alias';
  if (schema.enum) return 'enum';
  if (schema.type === 'object' || schema.properties) return 'object';
  if (schema.type === 'array') return 'array';
  if (Array.isArray(schema.type)) return 'scalar-union';
  if (typeof schema.type === 'string') return 'scalar';
  return 'unconstrained';
}

export function referenceName(reference) {
  const match = /^#\/\$defs\/([A-Za-z][A-Za-z0-9]*)$/u.exec(reference);
  if (!match) throw new Error(`Unsupported reference ${reference}`);
  return match[1];
}

export function resolvedKind(name, definitions, seen = new Set()) {
  if (seen.has(name)) return 'recursive';
  const schema = definitions[name];
  if (!schema) throw new Error(`Unknown definition ${name}`);
  const kind = rawKind(schema);
  if (kind !== 'alias') return kind === 'intersection' ? 'object' : kind;
  return resolvedKind(referenceName(schema.$ref), definitions, new Set([...seen, name]));
}

function unionMembersAreObjects(schema, definitions, seen) {
  if (schema.$ref) {
    const name = referenceName(schema.$ref);
    if (seen.has(name)) return false;
    const target = definitions[name];
    if (!target) throw new Error(`Unknown definition ${name}`);
    if (target.anyOf) {
      return target.anyOf.every((member) => unionMembersAreObjects(member, definitions, new Set([...seen, name])));
    }
    return resolvedKind(name, definitions) === 'object';
  }
  if (schema.anyOf) {
    return schema.anyOf.every((member) => unionMembersAreObjects(member, definitions, seen));
  }
  return schema.type === 'object' || Boolean(schema.properties) || Boolean(schema.allOf);
}

export function publicSymbol(name, definitions) {
  const schema = definitions[name];
  const kind = rawKind(schema);
  if (resolvedKind(name, definitions) === 'object') return 'record';
  if (kind === 'union' && unionMembersAreObjects(schema, definitions, new Set([name]))) return 'contract';
  if (kind === 'enum') return 'value';
  return 'internal';
}

function mergeEffective(target, source) {
  for (const [name, schema] of Object.entries(source.properties)) {
    if (target.properties[name] === undefined) target.properties[name] = schema;
    else target.properties[name] = { allOf: [target.properties[name], schema] };
  }
  for (const name of source.required) target.required.add(name);
  if (source.additionalProperties !== undefined) target.additionalProperties = source.additionalProperties;
}

export function effectiveObject(name, definitions, seen = new Set()) {
  if (seen.has(name)) throw new Error(`Recursive object inheritance at ${name}`);
  const schema = definitions[name];
  if (!schema) throw new Error(`Unknown definition ${name}`);
  const nextSeen = new Set([...seen, name]);
  return effectiveInlineObject(schema, definitions, nextSeen);
}

function effectiveInlineObject(schema, definitions, seen) {
  const result = {
    properties: {},
    required: new Set(),
    additionalProperties: undefined,
  };
  if (schema.$ref) mergeEffective(result, effectiveObject(referenceName(schema.$ref), definitions, seen));
  for (const member of schema.allOf || []) {
    if (member.$ref) mergeEffective(result, effectiveObject(referenceName(member.$ref), definitions, seen));
    else mergeEffective(result, effectiveInlineObject(member, definitions, seen));
  }
  mergeEffective(result, {
    properties: { ...(schema.properties || {}) },
    required: new Set(schema.required || []),
    additionalProperties: schema.additionalProperties,
  });
  return result;
}

export function nominalAllOfRecordName(schema, definitions) {
  if (!Array.isArray(schema.allOf)) return null;
  const references = schema.allOf
    .map((member, index) => ({ index, member }))
    .filter(({ member }) => member.$ref && Object.keys(member).every((key) => key === '$ref' || key === 'description'));
  if (references.length !== 1) return null;

  const reference = references[0];
  const name = referenceName(reference.member.$ref);
  if (publicSymbol(name, definitions) !== 'record') return null;
  const base = effectiveObject(name, definitions);
  const siblings = Object.fromEntries(
    Object.entries(schema).filter(([key]) => key !== 'allOf' && key !== 'description'),
  );
  const refinements = schema.allOf.filter((_member, index) => index !== reference.index);
  if (Object.keys(siblings).length > 0) refinements.push(siblings);

  for (const refinement of refinements) {
    const shape = effectiveInlineObject(refinement, definitions, new Set());
    if (Object.keys(shape.properties).some((field) => !(field in base.properties))) return null;
    if ([...shape.required].some((field) => !(field in base.properties))) return null;
  }

  return name;
}

export function schemaTypeSignature(schema, definitions, seen = new Set()) {
  if (schema.$ref) {
    const name = referenceName(schema.$ref);
    if (seen.has(name)) return [`ref:${name}`];
    return [`ref:${name}:${resolvedKind(name, definitions)}`];
  }
  if (schema.anyOf) {
    return [...new Set(schema.anyOf.flatMap((member) => schemaTypeSignature(member, definitions, seen)))].sort();
  }
  if (schema.allOf) {
    return [...new Set(schema.allOf.flatMap((member) => schemaTypeSignature(member, definitions, seen)))].sort();
  }
  if (Array.isArray(schema.type)) return [...schema.type].sort();
  if (schema.type) return [schema.type];
  if (schema.enum) return [`enum:${schema.enum.map((value) => JSON.stringify(value)).join('|')}`];
  return ['any'];
}

function valueCategory(value) {
  if (value === null) return 'null';
  if (typeof value === 'string') return 'string';
  if (typeof value === 'number') return 'number';
  if (typeof value === 'boolean') return 'boolean';
  if (Array.isArray(value)) return 'array';
  if (value && typeof value === 'object') return 'object';
  return 'mixed';
}

function normalizeNativeCategories(categories) {
  const unique = [...new Set(categories)];
  if (unique.includes('mixed')) return ['mixed'];
  return unique.sort();
}

export function nativeSchemaCategories(schema, definitions, seen = new Set()) {
  if (schema.$ref) {
    const name = referenceName(schema.$ref);
    if (seen.has(name)) return ['mixed'];
    if (resolvedKind(name, definitions) === 'object') return ['object'];
    return nativeSchemaCategories(definitions[name], definitions, new Set([...seen, name]));
  }
  if (schema.anyOf) {
    return normalizeNativeCategories(
      schema.anyOf.flatMap((member) => nativeSchemaCategories(member, definitions, seen)),
    );
  }
  if (schema.properties || schema.type === 'object' || 'additionalProperties' in schema) return ['object'];
  if (schema.allOf) {
    return normalizeNativeCategories(
      schema.allOf.flatMap((member) => nativeSchemaCategories(member, definitions, seen)),
    );
  }
  if (schema.enum) return normalizeNativeCategories(schema.enum.map(valueCategory));
  if ('const' in schema) return [valueCategory(schema.const)];

  const types = Array.isArray(schema.type) ? schema.type : schema.type ? [schema.type] : [];
  if (types.length === 0) return ['mixed'];
  return normalizeNativeCategories(types.map((type) => {
    if (type === 'integer' || type === 'number') return 'number';
    if (type === 'boolean' || type === 'string' || type === 'null' || type === 'array' || type === 'object') {
      return type;
    }
    return 'mixed';
  }));
}

export function requiredCompatibilityDecisions(olderVersion, olderDefinitions, newerVersion, newerDefinitions) {
  const decisions = {};
  const sharedNames = Object.keys(olderDefinitions)
    .filter((name) => name in newerDefinitions)
    .sort();

  for (const name of sharedNames) {
    const olderKind = rawKind(olderDefinitions[name]);
    const newerKind = rawKind(newerDefinitions[name]);
    if (olderKind !== newerKind) {
      decisions[`kind:${name}:${olderKind}->${newerKind}`] = {
        classification: 'kind-specific-symbol',
        evidence: {
          [olderVersion]: olderKind,
          [newerVersion]: newerKind,
        },
      };
    }

    if (resolvedKind(name, olderDefinitions) !== 'object' || resolvedKind(name, newerDefinitions) !== 'object') {
      continue;
    }
    const olderObject = effectiveObject(name, olderDefinitions);
    const newerObject = effectiveObject(name, newerDefinitions);
    const sharedFields = Object.keys(olderObject.properties)
      .filter((field) => field in newerObject.properties)
      .sort();
    for (const field of sharedFields) {
      const olderCategories = nativeSchemaCategories(olderObject.properties[field], olderDefinitions);
      const newerCategories = nativeSchemaCategories(newerObject.properties[field], newerDefinitions);
      if (JSON.stringify(olderCategories) === JSON.stringify(newerCategories)) continue;
      decisions[`getter:${name}.${field}`] = {
        classification: 'shared-getter-native-category-change',
        evidence: {
          [olderVersion]: olderCategories,
          [newerVersion]: newerCategories,
        },
      };
    }
  }

  return Object.fromEntries(Object.entries(decisions).sort(([left], [right]) => left.localeCompare(right)));
}

function methodFromObject(schema) {
  const method = schema.properties && schema.properties.method;
  return method && typeof method.const === 'string' ? method.const : null;
}

function collectAggregateSchema(schema, definitions, output, seen, currentName = null) {
  if (schema.$ref) {
    const name = referenceName(schema.$ref);
    if (seen.has(name)) return;
    const target = definitions[name];
    if (!target) throw new Error(`Unknown definition ${name}`);
    const method = methodFromObject(target);
    if (method) output[method] = name;
    else collectAggregateSchema(target, definitions, output, new Set([...seen, name]), name);
    return;
  }
  for (const member of schema.anyOf || []) collectAggregateSchema(member, definitions, output, seen, null);
  for (const member of schema.allOf || []) collectAggregateSchema(member, definitions, output, seen, null);
  const method = methodFromObject(schema);
  if (method) output[method] = currentName;
}

export function aggregateMethods(name, definitions) {
  if (!definitions[name]) return {};
  const output = {};
  collectAggregateSchema(definitions[name], definitions, output, new Set([name]), name);
  return Object.fromEntries(Object.entries(output).sort(([a], [b]) => a.localeCompare(b)));
}

export function structuralDefinition(schema) {
  if (Array.isArray(schema)) return schema.map(structuralDefinition);
  if (!schema || typeof schema !== 'object') return schema;
  return Object.fromEntries(
    Object.entries(schema)
      .filter(([key]) => key !== 'description')
      .map(([key, value]) => [key, structuralDefinition(value)]),
  );
}

export function definitionInventory(definitions) {
  return Object.keys(definitions)
    .sort()
    .map((name) => ({
      name,
      rawKind: rawKind(definitions[name]),
      resolvedKind: resolvedKind(name, definitions),
      publicSymbol: publicSymbol(name, definitions),
      structuralSha256: sha256(JSON.stringify(stableValue(structuralDefinition(definitions[name])))),
    }));
}

import { createHash } from 'node:crypto';

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

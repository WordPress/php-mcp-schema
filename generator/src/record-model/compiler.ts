import { createHash } from 'node:crypto';
import { mkdir, readFile, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import {
  Node,
  Project,
  SyntaxKind,
  VariableDeclarationKind,
  type ExpressionWithTypeArguments,
  type PropertySignature,
  type SourceFile,
  type TypeNode,
} from 'ts-morph';
import type {
  CompiledRevision,
  Descriptor,
  DescriptorBundle,
  FieldDescriptor,
  LiteralValue,
  RecordDescriptor,
  RevisionManifest,
} from './model.js';

const ANY: Descriptor = { kind: 'any' };
const currentDirectory = dirname(fileURLToPath(import.meta.url));
const schemaCacheDirectory = resolve(currentDirectory, '../../.cache/schemas');

export const SHIPPING_REVISIONS = ['2025-11-25', '2026-07-28'] as const;

const SCHEMA_SHA256: Readonly<Record<(typeof SHIPPING_REVISIONS)[number], string>> = {
  '2025-11-25': 'e74b56e73b2e37bdb595f74ba22e428ad7f07aa3519355ba661d681298ed38ac',
  '2026-07-28': '742750af0bb8c716e7030c4977c992b55d1adc4407e9e66997db5846baedc2cd',
};

interface CuratedRecordConstraints {
  readonly atLeastOneOf: readonly (readonly string[])[];
}

/**
 * Cross-field rules that schema.ts states only in prose, keyed by revision
 * and type name. Every entry is validated against the compiled descriptors,
 * which come from sources pinned by SCHEMA_SHA256.
 *
 * Re-review is enforced, not assumed: every table key must be a shipping
 * revision, and once any revision curates a type, every shipping revision
 * that declares that type must carry its own entry for it. A revision that
 * genuinely has no such rule declares an explicit empty waiver
 * (`atLeastOneOf: []`) so the reviewed decision is visible in the table.
 */
const CURATED_RECORD_CONSTRAINTS: Readonly<
  Record<string, Readonly<Record<string, CuratedRecordConstraints>>>
> = {
  '2026-07-28': {
    // "At least one of `inputRequests` or `requestState` MUST be present."
    InputRequiredResult: { atLeastOneOf: [['inputRequests', 'requestState']] },
  },
};

export function assertCuratedConstraintCoverage(
  revisions: ReadonlyMap<string, Readonly<Record<string, Descriptor>>>
): void {
  for (const revision of Object.keys(CURATED_RECORD_CONSTRAINTS)) {
    if (!revisions.has(revision)) {
      throw new Error(`Curated constraint table keys unknown revision ${revision}`);
    }
  }

  const curatedTypeNames = new Set(
    Object.values(CURATED_RECORD_CONSTRAINTS).flatMap((types) => Object.keys(types))
  );
  for (const [revision, descriptors] of revisions) {
    for (const typeName of curatedTypeNames) {
      if (descriptors[typeName] && !CURATED_RECORD_CONSTRAINTS[revision]?.[typeName]) {
        throw new Error(
          `Revision ${revision} declares ${typeName}, which is curated elsewhere; ` +
            `add its entry (or an explicit empty waiver) to CURATED_RECORD_CONSTRAINTS`
        );
      }
    }
  }
}

export async function compileRevisions(revisions: readonly string[]): Promise<DescriptorBundle> {
  const compiled = await Promise.all(revisions.map(compileRevision));
  assertCuratedConstraintCoverage(
    new Map(compiled.map((revision) => [revision.revision, revision.descriptors]))
  );
  const pool: Record<string, Descriptor> = {};
  const manifests: Record<string, RevisionManifest> = {};
  const compiledByRevision: Record<string, CompiledRevision> = {};

  for (const revision of compiled) {
    const types: Record<string, string> = {};
    for (const name of Object.keys(revision.descriptors).sort()) {
      const descriptor = revision.descriptors[name];
      if (!descriptor) {
        continue;
      }

      const hash = fingerprint(descriptor);
      pool[hash] = descriptor;
      types[name] = hash;
    }

    const roots = [...revision.rootRecordTypes];
    manifests[revision.revision] = {
      fingerprint: fingerprint({ constants: revision.constants, roots, types }),
      roots,
      types,
    };
    compiledByRevision[revision.revision] = revision;
  }

  return {
    pool: sortRecord(pool),
    manifests: sortRecord(manifests),
    revisions: sortRecord(compiledByRevision),
  };
}

async function compileRevision(revision: string): Promise<CompiledRevision> {
  const schema = await fetchSchema(revision);
  const project = new Project({
    useInMemoryFileSystem: true,
    compilerOptions: {
      strict: true,
      skipLibCheck: true,
    },
  });
  const source = project.createSourceFile(`${revision}.ts`, schema);
  const constants = extractConstants(source);
  const descriptors: Record<string, Descriptor> = {};

  for (const declaration of source.getInterfaces()) {
    const fields: Record<string, FieldDescriptor> = {};
    for (const property of declaration.getProperties()) {
      const typeNode = property.getTypeNode();
      if (!typeNode) {
        throw new Error(
          `${revision}:${declaration.getName()}.${property.getName()} has no type node`
        );
      }
      fields[propertyName(property.getNameNode())] = {
        required: !property.hasQuestionToken(),
        type: applyNumericBounds(
          compileType(typeNode, constants),
          property,
          `${revision}:${declaration.getName()}.${property.getName()}`
        ),
      };
    }

    const indexSignature = declaration.getIndexSignatures()[0];
    const indexType = indexSignature?.getReturnTypeNode();
    const descriptor: RecordDescriptor = {
      kind: 'record',
      fields: sortRecord(fields),
      parents: declaration.getExtends().map((parent) => compileParent(parent, constants)),
      additional: indexType ? compileType(indexType, constants) : false,
    };
    descriptors[declaration.getName()] = descriptor;
  }

  for (const declaration of source.getTypeAliases()) {
    const typeNode = declaration.getTypeNode();
    if (!typeNode) {
      throw new Error(`${revision}:${declaration.getName()} has no type node`);
    }
    descriptors[declaration.getName()] = compileType(typeNode, constants);
  }

  applyCuratedConstraints(revision, descriptors);

  const sortedDescriptors = sortRecord(descriptors);
  const rootRecordTypes = Object.keys(sortedDescriptors)
    .filter((name) => isRecordRoot({ kind: 'ref', name }, sortedDescriptors, new Set()))
    .sort();

  return {
    revision,
    constants: sortRecord(Object.fromEntries(constants)),
    descriptors: sortedDescriptors,
    rootRecordTypes,
  };
}

async function fetchSchema(revision: string): Promise<string> {
  if (!isShippingRevision(revision)) {
    throw new Error(`No pinned schema digest for MCP revision ${revision}`);
  }
  const expectedHash = SCHEMA_SHA256[revision];
  const cachePath = resolve(schemaCacheDirectory, `${revision}.ts`);
  try {
    const cached = await readFile(cachePath, 'utf8');
    if (fingerprintText(cached) === expectedHash) {
      return cached;
    }
  } catch {
    // An immutable revision that has not been cached yet is fetched below.
  }

  const url = `https://raw.githubusercontent.com/modelcontextprotocol/modelcontextprotocol/main/schema/${revision}/schema.ts`;
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(
      `Failed to fetch MCP ${revision} schema: ${response.status} ${response.statusText}`
    );
  }
  const contents = await response.text();
  const actualHash = fingerprintText(contents);
  if (actualHash !== expectedHash) {
    throw new Error(
      `MCP ${revision} schema digest changed: expected ${expectedHash}, received ${actualHash}`
    );
  }
  await mkdir(schemaCacheDirectory, { recursive: true });
  await writeFile(cachePath, contents, 'utf8');
  return contents;
}

function isShippingRevision(revision: string): revision is (typeof SHIPPING_REVISIONS)[number] {
  return SHIPPING_REVISIONS.some((candidate) => candidate === revision);
}

function fingerprintText(value: string): string {
  return createHash('sha256').update(value).digest('hex');
}

function compileParent(
  parent: ExpressionWithTypeArguments,
  constants: ReadonlyMap<string, LiteralValue>
): Descriptor {
  const expression = parent.getExpression().getText();
  const typeArguments = parent.getTypeArguments();
  if (expression === 'Omit' && typeArguments.length === 2) {
    const source = typeArguments[0];
    const keys = typeArguments[1];
    if (!source || !keys) {
      throw new Error(`Malformed Omit parent: ${parent.getText()}`);
    }
    return {
      kind: 'omit',
      from: compileType(source, constants),
      keys: extractLiteralStrings(keys),
    };
  }

  if (typeArguments.length > 0) {
    throw new Error(`Unsupported generic parent: ${parent.getText()}`);
  }

  return { kind: 'ref', name: expression };
}

function compileType(node: TypeNode, constants: ReadonlyMap<string, LiteralValue>): Descriptor {
  if (Node.isParenthesizedTypeNode(node)) {
    return compileType(node.getTypeNode(), constants);
  }
  if (Node.isUnionTypeNode(node)) {
    return {
      kind: 'union',
      anyOf: node.getTypeNodes().map((part) => compileType(part, constants)),
    };
  }
  if (Node.isIntersectionTypeNode(node)) {
    return {
      kind: 'intersection',
      allOf: node.getTypeNodes().map((part) => compileType(part, constants)),
    };
  }
  if (Node.isArrayTypeNode(node)) {
    return { kind: 'list', items: compileType(node.getElementTypeNode(), constants) };
  }
  if (Node.isTupleTypeNode(node)) {
    return { kind: 'tuple', items: node.getElements().map((item) => compileType(item, constants)) };
  }
  if (Node.isLiteralTypeNode(node)) {
    return { kind: 'literal', value: literalValue(node.getLiteral().getText()) };
  }
  if (Node.isTypeQuery(node)) {
    const name = node.getExprName().getText();
    const value = constants.get(name);
    if (value === undefined) {
      throw new Error(`Unknown constant reference: typeof ${name}`);
    }
    return { kind: 'literal', value };
  }
  if (Node.isTypeReference(node)) {
    return compileReference(node.getTypeName().getText(), node.getTypeArguments(), constants);
  }
  if (Node.isTypeLiteral(node)) {
    const fields: Record<string, FieldDescriptor> = {};
    for (const property of node.getProperties()) {
      const typeNode = property.getTypeNode();
      if (!typeNode) {
        throw new Error(`Type-literal property ${property.getName()} has no type`);
      }
      fields[propertyName(property.getNameNode())] = {
        required: !property.hasQuestionToken(),
        type: applyNumericBounds(
          compileType(typeNode, constants),
          property,
          `type-literal property ${property.getName()}`
        ),
      };
    }
    const indexSignature = node.getIndexSignatures()[0];
    const indexType = indexSignature?.getReturnTypeNode();
    return {
      kind: 'record',
      fields: sortRecord(fields),
      parents: [],
      additional: indexType ? compileType(indexType, constants) : false,
    };
  }
  if (Node.isTypeOperatorTypeNode(node)) {
    return compileType(node.getTypeNode(), constants);
  }

  switch (node.getKind()) {
    case SyntaxKind.StringKeyword:
      return { kind: 'string' };
    case SyntaxKind.NumberKeyword:
      return { kind: 'number' };
    case SyntaxKind.BooleanKeyword:
      return { kind: 'boolean' };
    case SyntaxKind.NullKeyword:
      return { kind: 'null' };
    case SyntaxKind.UnknownKeyword:
    case SyntaxKind.AnyKeyword:
      return ANY;
    case SyntaxKind.ObjectKeyword:
      return { kind: 'map', values: ANY };
    default:
      throw new Error(`Unsupported TypeScript type ${node.getKindName()}: ${node.getText()}`);
  }
}

function compileReference(
  name: string,
  typeArguments: readonly TypeNode[],
  constants: ReadonlyMap<string, LiteralValue>
): Descriptor {
  if ((name === 'Array' || name === 'ReadonlyArray') && typeArguments.length === 1) {
    const item = typeArguments[0];
    if (!item) {
      throw new Error(`${name} is missing its item type`);
    }
    return { kind: 'list', items: compileType(item, constants) };
  }
  if (name === 'Record' && typeArguments.length === 2) {
    const values = typeArguments[1];
    if (!values) {
      throw new Error('Record is missing its value type');
    }
    return { kind: 'map', values: compileType(values, constants) };
  }
  if (name === 'Omit' && typeArguments.length === 2) {
    const source = typeArguments[0];
    const keys = typeArguments[1];
    if (!source || !keys) {
      throw new Error('Omit is missing type arguments');
    }
    return {
      kind: 'omit',
      from: compileType(source, constants),
      keys: extractLiteralStrings(keys),
    };
  }
  if (name === 'Readonly' && typeArguments.length === 1) {
    const source = typeArguments[0];
    if (!source) {
      throw new Error('Readonly is missing its type argument');
    }
    return compileType(source, constants);
  }
  if (typeArguments.length > 0) {
    throw new Error(
      `Unsupported generic type: ${name}<${typeArguments.map((item) => item.getText()).join(', ')}>`
    );
  }
  return { kind: 'ref', name };
}

function applyNumericBounds(
  descriptor: Descriptor,
  property: PropertySignature,
  context: string
): Descriptor {
  const minimum = numericBoundTag(property, 'minimum', context);
  const maximum = numericBoundTag(property, 'maximum', context);
  if (minimum === undefined && maximum === undefined) {
    return descriptor;
  }
  if (descriptor.kind !== 'number') {
    throw new Error(`${context} declares a numeric bound but compiles to '${descriptor.kind}'`);
  }
  return {
    kind: 'number',
    ...(minimum === undefined ? {} : { minimum }),
    ...(maximum === undefined ? {} : { maximum }),
  };
}

function numericBoundTag(
  property: PropertySignature,
  tagName: 'minimum' | 'maximum',
  context: string
): number | undefined {
  for (const doc of property.getJsDocs()) {
    for (const tag of doc.getTags()) {
      if (tag.getTagName() !== tagName) {
        continue;
      }
      const raw = (tag.getCommentText() ?? '').trim();
      if (!/^-?\d+(?:\.\d+)?$/.test(raw)) {
        throw new Error(`${context} @${tagName} is not a number: '${raw}'`);
      }
      return Number(raw);
    }
  }
  return undefined;
}

function applyCuratedConstraints(revision: string, descriptors: Record<string, Descriptor>): void {
  for (const [typeName, constraints] of Object.entries(
    CURATED_RECORD_CONSTRAINTS[revision] ?? {}
  )) {
    const descriptor = descriptors[typeName];
    if (!descriptor || descriptor.kind !== 'record') {
      throw new Error(`Curated constraint targets missing or non-record type ${revision}:${typeName}`);
    }
    for (const group of constraints.atLeastOneOf) {
      if (group.length < 2) {
        throw new Error(
          `Curated atLeastOneOf group for ${revision}:${typeName} needs at least two fields`
        );
      }
      for (const field of group) {
        if (!descriptor.fields[field]) {
          throw new Error(
            `Curated constraint references unknown field ${revision}:${typeName}.${field}`
          );
        }
      }
    }
    descriptors[typeName] = { ...descriptor, atLeastOneOf: constraints.atLeastOneOf };
  }
}

function extractLiteralStrings(node: TypeNode): string[] {
  if (Node.isUnionTypeNode(node)) {
    return node.getTypeNodes().flatMap(extractLiteralStrings);
  }
  if (!Node.isLiteralTypeNode(node)) {
    throw new Error(`Expected literal string keys, got ${node.getText()}`);
  }
  const value = literalValue(node.getLiteral().getText());
  if (typeof value !== 'string') {
    throw new Error(`Expected literal string key, got ${node.getText()}`);
  }
  return [value];
}

function literalValue(text: string): LiteralValue {
  if (
    (text.startsWith('"') && text.endsWith('"')) ||
    (text.startsWith("'") && text.endsWith("'"))
  ) {
    const quote = text[0];
    const inner = text.slice(1, -1);
    if (quote === '"') {
      return JSON.parse(text) as string;
    }
    return inner.replace(/\\'/g, "'").replace(/\\\\/g, '\\');
  }
  if (text === 'true') {
    return true;
  }
  if (text === 'false') {
    return false;
  }
  if (text === 'null') {
    return null;
  }
  if (/^-?\d+(?:\.\d+)?$/.test(text)) {
    return Number(text);
  }
  throw new Error(`Unsupported literal: ${text}`);
}

function extractConstants(source: SourceFile): ReadonlyMap<string, LiteralValue> {
  const constants = new Map<string, LiteralValue>();
  for (const statement of source.getVariableStatements()) {
    if (
      !statement.isExported() ||
      statement.getDeclarationKind() !== VariableDeclarationKind.Const
    ) {
      continue;
    }
    for (const declaration of statement.getDeclarations()) {
      const initializer = declaration.getInitializer();
      if (!initializer) {
        continue;
      }
      const text = initializer.getText();
      if (/^(?:["']|true$|false$|null$|-?\d)/.test(text)) {
        constants.set(declaration.getName(), literalValue(text));
      }
    }
  }
  return constants;
}

function propertyName(node: Node): string {
  if (Node.isStringLiteral(node) || Node.isNumericLiteral(node)) {
    return node.getLiteralText();
  }
  return node.getText();
}

function isRecordRoot(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>
): boolean {
  switch (descriptor.kind) {
    case 'record':
    case 'map':
    case 'omit':
    case 'intersection':
      return true;
    case 'union':
      return (
        descriptor.anyOf.length > 0 &&
        descriptor.anyOf.every((part) => isRecordRoot(part, registry, visiting))
      );
    case 'ref': {
      if (visiting.has(descriptor.name)) {
        return false;
      }
      const target = registry[descriptor.name];
      if (!target) {
        return false;
      }
      const next = new Set(visiting);
      next.add(descriptor.name);
      return isRecordRoot(target, registry, next);
    }
    default:
      return false;
  }
}

function fingerprint(value: unknown): string {
  return createHash('sha256').update(canonicalJson(value)).digest('hex');
}

function canonicalJson(value: unknown): string {
  return JSON.stringify(canonicalize(value));
}

function canonicalize(value: unknown): unknown {
  if (Array.isArray(value)) {
    return value.map(canonicalize);
  }
  if (value !== null && typeof value === 'object') {
    const output: Record<string, unknown> = {};
    for (const key of Object.keys(value).sort()) {
      output[key] = canonicalize((value as Record<string, unknown>)[key]);
    }
    return output;
  }
  return value;
}

function sortRecord<T>(value: Readonly<Record<string, T>>): Record<string, T> {
  return Object.fromEntries(
    Object.entries(value).sort(([left], [right]) => left.localeCompare(right))
  );
}

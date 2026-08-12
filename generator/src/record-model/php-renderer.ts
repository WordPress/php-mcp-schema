import { cp, mkdir, readdir, rm, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import type { CompiledRevision, Descriptor, DescriptorBundle, FieldDescriptor } from './model.js';

const currentDirectory = dirname(fileURLToPath(import.meta.url));
const repositoryRoot = resolve(currentDirectory, '../../..');
const templatesDirectory = resolve(currentDirectory, '../../templates');
const outputDirectory = resolve(repositoryRoot, 'src');

const PHP_RESERVED_METHODS = new Set([
  '__halt_compiler',
  'abstract',
  'and',
  'array',
  'as',
  'break',
  'callable',
  'case',
  'catch',
  'class',
  'clone',
  'const',
  'continue',
  'declare',
  'default',
  'die',
  'do',
  'echo',
  'else',
  'elseif',
  'empty',
  'enddeclare',
  'endfor',
  'endforeach',
  'endif',
  'endswitch',
  'endwhile',
  'eval',
  'exit',
  'extends',
  'final',
  'finally',
  'fn',
  'for',
  'foreach',
  'function',
  'global',
  'goto',
  'if',
  'implements',
  'include',
  'include_once',
  'instanceof',
  'insteadof',
  'interface',
  'isset',
  'list',
  'match',
  'namespace',
  'new',
  'or',
  'print',
  'private',
  'protected',
  'public',
  'readonly',
  'require',
  'require_once',
  'return',
  'static',
  'switch',
  'throw',
  'trait',
  'try',
  'unset',
  'use',
  'var',
  'while',
  'xor',
  'yield',
]);

export async function writePhpPackage(bundle: DescriptorBundle): Promise<void> {
  assertSafeOutputDirectory(outputDirectory);
  await rm(outputDirectory, { recursive: true, force: true });
  await mkdir(outputDirectory, { recursive: true });
  await copyTemplates(templatesDirectory, outputDirectory);

  await writeGeneratedFile('Generated/DescriptorPool.php', renderDescriptorPool(bundle));
  for (const revision of Object.values(bundle.revisions)) {
    await writeGeneratedFile(
      `Generated/${revisionClassName(revision.revision)}Schema.php`,
      renderCatalog(revision)
    );
  }
  await writeGeneratedFile('Schemas.php', renderSchemas(Object.values(bundle.revisions)));
}

async function copyTemplates(source: string, destination: string): Promise<void> {
  for (const entry of await readdir(source, { withFileTypes: true })) {
    const from = resolve(source, entry.name);
    const to = resolve(destination, entry.name);
    if (entry.isDirectory()) {
      await mkdir(to, { recursive: true });
      await copyTemplates(from, to);
      continue;
    }
    if (!entry.isFile() || !entry.name.endsWith('.php')) {
      continue;
    }
    await mkdir(dirname(to), { recursive: true });
    await cp(from, to);
  }
}

async function writeGeneratedFile(relativePath: string, contents: string): Promise<void> {
  const destination = resolve(outputDirectory, relativePath);
  if (!destination.startsWith(`${outputDirectory}/`)) {
    throw new Error(`Refusing to write outside generated src directory: ${destination}`);
  }
  await mkdir(dirname(destination), { recursive: true });
  await writeFile(destination, contents, 'utf8');
}

function assertSafeOutputDirectory(directory: string): void {
  const expected = resolve(repositoryRoot, 'src');
  if (directory !== expected || dirname(directory) !== repositoryRoot) {
    throw new Error(`Refusing to replace unexpected output directory: ${directory}`);
  }
}

function renderDescriptorPool(bundle: DescriptorBundle): string {
  return `<?php

declare(strict_types=1);

namespace WP\\McpSchema\\Generated;

/** Generated content-addressed descriptor data. Do not edit manually. */
final class DescriptorPool
{
    /** @var array<string, array<string, mixed>>|null */
    private static ?array $descriptorCache = null;

    /** @var array<string, array{fingerprint: string, roots: array<int, string>, types: array<string, string>}>|null */
    private static ?array $manifestCache = null;

    /** @return array<string, array<string, mixed>> */
    public static function descriptors(): array
    {
        if (self::$descriptorCache === null) {
            self::$descriptorCache = ${phpValue(bundle.pool, 3)};
        }
        return self::$descriptorCache;
    }

    /** @return array<string, array{fingerprint: string, roots: array<int, string>, types: array<string, string>}> */
    public static function manifests(): array
    {
        if (self::$manifestCache === null) {
            self::$manifestCache = ${phpValue(bundle.manifests, 3)};
        }
        return self::$manifestCache;
    }

    private function __construct()
    {
    }
}
`;
}

function renderCatalog(revision: CompiledRevision): string {
  const className = `${revisionClassName(revision.revision)}Schema`;
  const methods = revision.rootRecordTypes
    .map((name) => renderCatalogMethod(name, revision.descriptors))
    .join('\n');

  return `<?php

declare(strict_types=1);

namespace WP\\McpSchema\\Generated;

use WP\\McpSchema\\Contract\\Type;
use WP\\McpSchema\\Contract\\Record;
use WP\\McpSchema\\Runtime\\GenericRevisionSchema;

/** Generated discoverable catalog for MCP ${revision.revision}. */
final class ${className} extends GenericRevisionSchema
{
    public const REVISION = '${revision.revision}';

    public function __construct()
    {
        $manifest = DescriptorPool::manifests()[self::REVISION];
        parent::__construct(
            self::REVISION,
            DescriptorPool::descriptors(),
            $manifest['types'],
            $manifest['roots'],
            $manifest['fingerprint']
        );
    }

${methods}}
`;
}

function renderCatalogMethod(name: string, registry: Readonly<Record<string, Descriptor>>): string {
  const descriptor = registry[name];
  if (!descriptor) {
    throw new Error(`Cannot render missing catalog type ${name}`);
  }
  const wireShape = phpStanType(descriptor, registry, new Set([name]), 0);
  const fieldShape = phpStanAccessRootType(descriptor, registry, new Set([name]), 0);
  const method = methodName(name);
  return `    /** @return Type<${wireShape}, ${fieldShape}> */
    public function ${method}(): Type
    {
        /** @var Type<${wireShape}, ${fieldShape}> $type */
        $type = $this->type('${phpString(name)}');
        return $type;
    }

`;
}

function phpStanAccessRootType(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>,
  depth: number
): string {
  if (descriptor.kind === 'ref') {
    const target = registry[descriptor.name];
    if (!target || visiting.has(descriptor.name)) return 'array<string, mixed>';
    const next = new Set(visiting);
    next.add(descriptor.name);
    return phpStanAccessRootType(target, registry, next, depth);
  }
  if (descriptor.kind === 'union') {
    return phpStanUnion(
      descriptor.anyOf.map((part) => phpStanAccessRootType(part, registry, visiting, depth))
    );
  }
  if (descriptor.kind === 'map') {
    return `array<string, ${phpStanAccessValueType(descriptor.values, registry, visiting, depth + 1)}>`;
  }
  if (
    descriptor.kind === 'record' ||
    descriptor.kind === 'intersection' ||
    descriptor.kind === 'omit'
  ) {
    const shape = collectShape(descriptor, registry, new Set());
    const fields = Object.entries(shape.fields).map(([name, field]) => {
      const key = /^[A-Za-z_][A-Za-z0-9_]*$/.test(name) ? name : `'${phpString(name)}'`;
      const optional = field.required ? '' : '?';
      return `${key}${optional}: ${phpStanAccessValueType(field.type, registry, visiting, depth + 1)}`;
    });
    if (shape.additional !== false) {
      fields.push(
        `...<string, ${phpStanAccessValueType(shape.additional, registry, visiting, depth + 1)}>`
      );
    }
    return `array{${fields.join(', ')}}`;
  }
  return 'array<string, mixed>';
}

function phpStanAccessValueType(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>,
  depth: number
): string {
  switch (descriptor.kind) {
    case 'any':
      return 'mixed';
    case 'string':
      return 'string';
    case 'number':
      return 'int|float';
    case 'boolean':
      return 'bool';
    case 'null':
      return 'null';
    case 'literal':
      return phpStanLiteral(descriptor.value);
    case 'list':
      return `list<${phpStanAccessValueType(descriptor.items, registry, visiting, depth + 1)}>`;
    case 'tuple':
      return `array{${descriptor.items.map((item) => phpStanAccessValueType(item, registry, visiting, depth + 1)).join(', ')}}`;
    case 'union':
      return phpStanUnion(
        descriptor.anyOf.map((part) => phpStanAccessValueType(part, registry, visiting, depth))
      );
    case 'ref': {
      const target = registry[descriptor.name];
      if (!target || visiting.has(descriptor.name)) return 'mixed';
      if (isRecordDescriptor(target, registry, new Set())) {
        return 'Record<array<string, mixed>, array<string, mixed>>';
      }
      const next = new Set(visiting);
      next.add(descriptor.name);
      return phpStanAccessValueType(target, registry, next, depth);
    }
    case 'map':
    case 'record':
    case 'intersection':
    case 'omit':
      return 'Record<array<string, mixed>, array<string, mixed>>';
  }
}

function renderSchemas(revisions: readonly CompiledRevision[]): string {
  const imports = revisions
    .map(
      (revision) => `use WP\\McpSchema\\Generated\\${revisionClassName(revision.revision)}Schema;`
    )
    .join('\n');
  const cases = revisions
    .map((revision) => {
      const className = `${revisionClassName(revision.revision)}Schema`;
      const constant = `Revision::${revisionClassName(revision.revision)}`;
      return `            case ${constant}:
                $schema = new ${className}();
                break;`;
    })
    .join('\n');
  const typedMethods = revisions
    .map((revision) => {
      const segment = revisionClassName(revision.revision);
      const className = `${segment}Schema`;
      return `    public static function ${segment.toLowerCase()}(): ${className}
    {
        $schema = self::revision(Revision::${segment});
        if (!$schema instanceof ${className}) {
            throw new LogicException('Revision catalog mismatch for ${revision.revision}');
        }
        return $schema;
    }
`;
    })
    .join('\n');

  return `<?php

declare(strict_types=1);

namespace WP\\McpSchema;

use LogicException;
use WP\\McpSchema\\Contract\\RevisionSchema;
${imports}

/** Explicit entry point for immutable revision catalogs. */
final class Schemas
{
    /** @var array<string, RevisionSchema> */
    private static array $instances = [];

    public static function revision(string $revision): RevisionSchema
    {
        if (isset(self::$instances[$revision])) {
            return self::$instances[$revision];
        }

        switch ($revision) {
${cases}
            default:
                throw new LogicException('Unsupported MCP revision: ' . $revision);
        }

        self::$instances[$revision] = $schema;
        return $schema;
    }

${typedMethods}    private function __construct()
    {
    }
}
`;
}

function phpStanType(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>,
  depth: number
): string {
  switch (descriptor.kind) {
    case 'any':
      return 'mixed';
    case 'string':
      return 'string';
    case 'number':
      return 'int|float';
    case 'boolean':
      return 'bool';
    case 'null':
      return 'null';
    case 'literal':
      return phpStanLiteral(descriptor.value);
    case 'list':
      return `list<${phpStanType(descriptor.items, registry, visiting, depth + 1)}>`;
    case 'tuple':
      return `array{${descriptor.items.map((item) => phpStanType(item, registry, visiting, depth + 1)).join(', ')}}`;
    case 'map':
      return `array<string, ${phpStanType(descriptor.values, registry, visiting, depth + 1)}>`;
    case 'union':
      return phpStanUnion(
        descriptor.anyOf.map((part) => phpStanType(part, registry, visiting, depth))
      );
    case 'intersection':
    case 'omit':
    case 'record':
      return renderShapeType(descriptor, registry, visiting, depth);
    case 'ref': {
      const target = registry[descriptor.name];
      if (!target || visiting.has(descriptor.name)) {
        return 'mixed';
      }
      if (depth > 0 && isRecordDescriptor(target, registry, new Set())) {
        return 'array<string, mixed>';
      }
      const next = new Set(visiting);
      next.add(descriptor.name);
      return phpStanType(target, registry, next, depth);
    }
  }
}

function renderShapeType(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>,
  depth: number
): string {
  const shape = collectShape(descriptor, registry, new Set());
  const fields = Object.entries(shape.fields).map(([name, field]) => {
    const key = /^[A-Za-z_][A-Za-z0-9_]*$/.test(name) ? name : `'${phpString(name)}'`;
    const optional = field.required ? '' : '?';
    return `${key}${optional}: ${phpStanType(field.type, registry, visiting, depth + 1)}`;
  });
  if (shape.additional !== false) {
    fields.push(`...<string, ${phpStanType(shape.additional, registry, visiting, depth + 1)}>`);
  }
  return `array{${fields.join(', ')}}`;
}

function collectShape(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>
): { fields: Record<string, FieldDescriptor>; additional: Descriptor | false } {
  if (descriptor.kind === 'ref') {
    if (visiting.has(descriptor.name)) {
      throw new Error(`Cyclic catalog inheritance involving ${descriptor.name}`);
    }
    const target = registry[descriptor.name];
    if (!target) {
      throw new Error(`Missing descriptor reference ${descriptor.name}`);
    }
    const next = new Set(visiting);
    next.add(descriptor.name);
    return collectShape(target, registry, next);
  }
  if (descriptor.kind === 'map') {
    return { fields: {}, additional: descriptor.values };
  }
  if (descriptor.kind === 'omit') {
    const shape = collectShape(descriptor.from, registry, visiting);
    const fields = { ...shape.fields };
    for (const key of descriptor.keys) delete fields[key];
    return { fields, additional: shape.additional };
  }
  if (descriptor.kind === 'intersection') {
    return descriptor.allOf.reduce(
      (result, part) => mergeShape(result, collectShape(part, registry, visiting)),
      { fields: {}, additional: false } as {
        fields: Record<string, FieldDescriptor>;
        additional: Descriptor | false;
      }
    );
  }
  if (descriptor.kind !== 'record') {
    return { fields: {}, additional: false };
  }

  let shape: { fields: Record<string, FieldDescriptor>; additional: Descriptor | false } = {
    fields: {},
    additional: false,
  };
  for (const parent of descriptor.parents) {
    shape = mergeShape(shape, collectShape(parent, registry, visiting));
  }
  return {
    fields: { ...shape.fields, ...descriptor.fields },
    additional: descriptor.additional === false ? shape.additional : descriptor.additional,
  };
}

function mergeShape(
  left: { fields: Record<string, FieldDescriptor>; additional: Descriptor | false },
  right: { fields: Record<string, FieldDescriptor>; additional: Descriptor | false }
): { fields: Record<string, FieldDescriptor>; additional: Descriptor | false } {
  return {
    fields: { ...left.fields, ...right.fields },
    additional: right.additional === false ? left.additional : right.additional,
  };
}

function isRecordDescriptor(
  descriptor: Descriptor,
  registry: Readonly<Record<string, Descriptor>>,
  visiting: Set<string>
): boolean {
  if (['record', 'map', 'intersection', 'omit'].includes(descriptor.kind)) return true;
  if (descriptor.kind === 'union') {
    return descriptor.anyOf.every((part) => isRecordDescriptor(part, registry, visiting));
  }
  if (descriptor.kind !== 'ref' || visiting.has(descriptor.name)) return false;
  const target = registry[descriptor.name];
  if (!target) return false;
  const next = new Set(visiting);
  next.add(descriptor.name);
  return isRecordDescriptor(target, registry, next);
}

function phpStanLiteral(value: string | number | boolean | null): string {
  if (typeof value === 'string') return `'${phpString(value)}'`;
  if (value === null) return 'null';
  return String(value);
}

function phpStanUnion(types: readonly string[]): string {
  return [...new Set(types)].join('|');
}

function phpValue(value: unknown, depth: number): string {
  if (value === null) return 'null';
  if (typeof value === 'string') return `'${phpString(value)}'`;
  if (typeof value === 'number' || typeof value === 'boolean') return String(value);
  if (Array.isArray(value)) {
    if (value.length === 0) return '[]';
    const indentation = '    '.repeat(depth);
    const childIndentation = '    '.repeat(depth + 1);
    return `[\n${value.map((item) => `${childIndentation}${phpValue(item, depth + 1)},`).join('\n')}\n${indentation}]`;
  }
  if (typeof value === 'object') {
    const entries = Object.entries(value as Record<string, unknown>);
    if (entries.length === 0) return '[]';
    const indentation = '    '.repeat(depth);
    const childIndentation = '    '.repeat(depth + 1);
    return `[\n${entries.map(([key, item]) => `${childIndentation}'${phpString(key)}' => ${phpValue(item, depth + 1)},`).join('\n')}\n${indentation}]`;
  }
  throw new Error(`Cannot render PHP value of type ${typeof value}`);
}

function phpString(value: string): string {
  return value.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

function methodName(typeName: string): string {
  const candidate = typeName.charAt(0).toLowerCase() + typeName.slice(1);
  return PHP_RESERVED_METHODS.has(candidate.toLowerCase()) ? `${candidate}Type` : candidate;
}

function revisionClassName(revision: string): string {
  return `V${revision.replace(/-/g, '')}`;
}

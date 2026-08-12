/**
 * MCP PHP Schema Generator - Union Generator
 *
 * Generates PHP interfaces for TypeScript union types.
 */

import type { TsTypeAlias, GeneratorConfig, DomainClassification } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { formatPhpDocDescription, getDeprecatedPhpDocTag } from './index.js';

/**
 * Generates PHP interfaces for union types.
 */
export class UnionGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;
  private readonly typeAliases: readonly TsTypeAlias[];
  /** Map from union name to parent union names that contain it */
  private readonly parentUnionMap: Map<string, string[]>;

  constructor(config: GeneratorConfig, typeAliases: readonly TsTypeAlias[] = []) {
    this.config = config;
    this.classifier = new DomainClassifier();
    this.typeAliases = typeAliases;
    this.parentUnionMap = this.buildParentUnionMap();
  }

  /**
   * Builds a map from child union names to their parent union names.
   * Used to determine interface extension hierarchy.
   */
  private buildParentUnionMap(): Map<string, string[]> {
    const map = new Map<string, string[]>();

    // First, identify all unions
    const unions = this.typeAliases.filter((alias) => this.isUnion(alias));
    const unionNames = new Set(unions.map((u) => u.name));

    // For each union, check if its members are also unions
    for (const union of unions) {
      const members = this.extractMembers(union);
      for (const member of members) {
        if (unionNames.has(member)) {
          // This member is a union, so it's a child of the current union
          const parents = map.get(member) ?? [];
          parents.push(union.name);
          map.set(member, parents);
        }
      }
    }

    return map;
  }

  /**
   * Checks if a type alias represents a union of object types.
   * Returns false for primitive-only unions like `string | number`.
   */
  isUnion(typeAlias: TsTypeAlias): boolean {
    const type = typeAlias.type.trim();

    // Must contain | but not be a simple string literal union (enum)
    if (!type.includes('|')) {
      return false;
    }

    // Check if it's a union of object types (interface names)
    const parts = type.split('|').map((p) => p.trim());

    // Skip string literal unions (enums)
    if (parts.every((p) => p.startsWith('"') || p.startsWith("'"))) {
      return false;
    }

    // Skip primitive-only unions (can't implement interfaces)
    const primitives = new Set(['string', 'number', 'boolean', 'null', 'undefined', 'any', 'unknown', 'never']);
    const nonPrimitiveParts = parts.filter((p) => !primitives.has(p) && !p.startsWith('"') && !p.startsWith("'"));

    // Must have at least one non-primitive, non-literal type
    return nonPrimitiveParts.length > 0;
  }

  /**
   * Extracts the member type names from a union.
   * Filters out primitives and literals since they can't implement interfaces.
   */
  extractMembers(typeAlias: TsTypeAlias): string[] {
    const type = typeAlias.type.trim();
    const primitives = new Set(['string', 'number', 'boolean', 'null', 'undefined', 'any', 'unknown', 'never']);

    return type
      .split('|')
      .map((p) => p.trim())
      .filter((p) => {
        // Skip empty strings
        if (p === '') return false;
        // Skip string/number literals
        if (p.startsWith('"') || p.startsWith("'")) return false;
        // Skip primitives
        if (primitives.has(p)) return false;
        return true;
      });
  }

  /**
   * Generates PHP interface code for a union type.
   */
  generate(typeAlias: TsTypeAlias): string {
    const classification = this.classifier.classify(typeAlias.name, typeAlias.tags);
    const members = this.extractMembers(typeAlias);
    const indent = this.getIndent();

    // Get parent unions that this union should extend
    const parentUnions = this.parentUnionMap.get(typeAlias.name) ?? [];

    return this.renderInterface(typeAlias.name, members, classification, typeAlias.description, typeAlias.tags, indent, parentUnions);
  }

  /**
   * Gets the indentation string.
   */
  private getIndent(): string {
    if (this.config.output.indentation === 'tabs') {
      return '\t';
    }
    return ' '.repeat(this.config.output.indentSize);
  }

  /**
   * Renders the PHP interface.
   */
  private renderInterface(
    name: string,
    members: string[],
    classification: DomainClassification,
    description: string | undefined,
    tags: TsTypeAlias['tags'],
    indent: string,
    parentUnions: string[] = []
  ): string {
    const lines: string[] = [];
    const namespace = this.getNamespace(classification);
    const interfaceName = `${name}Interface`;

    // PHP opening tag
    lines.push('<?php');
    lines.push('');
    lines.push('declare(strict_types=1);');
    lines.push('');

    // Namespace
    lines.push(`namespace ${namespace};`);
    lines.push('');

    // Use statements for parent union interfaces
    for (const parent of parentUnions) {
      const parentAlias = this.typeAliases.find((a) => a.name === parent);
      if (parentAlias) {
        const parentClassification = this.classifier.classify(parent, parentAlias.tags);
        const parentNamespace = `${this.config.output.namespace}\\${parentClassification.domain}\\${parentClassification.subdomain}\\Union`;
        lines.push(`use ${parentNamespace}\\${parent}Interface;`);
      }
    }
    if (parentUnions.length > 0) {
      lines.push('');
    }

    // Class docblock
    lines.push('/**');
    if (description) {
      lines.push(...formatPhpDocDescription(description));
      lines.push(' *');
    }
    const deprecatedTag = getDeprecatedPhpDocTag(tags);
    if (deprecatedTag) {
      lines.push(` * ${deprecatedTag}`);
      lines.push(' *');
    }
    lines.push(' * Union type members:');
    for (const member of members) {
      lines.push(` * - ${member}`);
    }
    lines.push(' *');
    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Interface declaration with extends if there are parent unions
    if (parentUnions.length > 0) {
      const extendsList = parentUnions.map((p) => `${p}Interface`).join(', ');
      lines.push(`interface ${interfaceName} extends ${extendsList}`);
    } else {
      lines.push(`interface ${interfaceName}`);
    }
    lines.push('{');

    // Only add toArray method if not extending another interface (parent already has it)
    if (parentUnions.length === 0) {
      // toArray method - the core serialization contract
      // Note: Discriminator access is via toArray()['type'] or toArray()['method'] etc.
      // This keeps the interface simple and works with any discriminator field name.
      lines.push(`${indent}/**`);
      lines.push(`${indent} * Converts the instance to an array.`);
      lines.push(`${indent} *`);
      lines.push(`${indent} * @return array<string, mixed>`);
      lines.push(`${indent} */`);
      lines.push(`${indent}public function toArray(): array;`);
    }

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Gets the PHP namespace for a classification.
   * The configured namespace already contains a legal V-prefixed revision segment.
   */
  private getNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Union`;
  }
}

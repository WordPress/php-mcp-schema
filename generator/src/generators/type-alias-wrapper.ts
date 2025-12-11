/**
 * MCP PHP Schema Generator - Type Alias Wrapper Generator
 *
 * Generates PHP wrapper classes for TypeScript type aliases that are referenced
 * in union types but don't have their own DTOs generated.
 *
 * ## Problem This Solves
 *
 * In TypeScript, a type alias like `type EmptyResult = Result` creates an alias
 * that's interchangeable with `Result`. However, when `EmptyResult` appears in
 * a union type like:
 *
 * ```typescript
 * type ServerResult = EmptyResult | InitializeResult | ...
 * ```
 *
 * The PHP generator creates a `ServerResultInterface` that lists `EmptyResult`
 * as a member. But since `EmptyResult` is just a type alias (not an interface),
 * no PHP class is generated for it, causing:
 *
 * 1. Documentation listing non-existent types
 * 2. Factory methods that can't route to `EmptyResult`
 * 3. No way to type-hint `ServerResultInterface` for empty responses
 *
 * ## Solution
 *
 * This generator creates thin wrapper classes that:
 * 1. Extend the aliased base class (e.g., `Result`)
 * 2. Implement all union interfaces that reference the alias
 * 3. Provide semantic meaning while maintaining compatibility
 *
 * For example, `EmptyResult` becomes:
 *
 * ```php
 * class EmptyResult extends Result implements ServerResultInterface, ClientResultInterface
 * {
 *     // Inherits everything from Result
 * }
 * ```
 */

import type { TsTypeAlias, TsInterface, GeneratorConfig, DomainClassification, UnionMembershipInfo } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { formatPhpDocDescription } from './index.js';

/**
 * Information about a type alias that needs a wrapper class.
 */
export interface TypeAliasWrapperInfo {
  /** The type alias name (e.g., 'EmptyResult') */
  readonly aliasName: string;
  /** The base type it aliases (e.g., 'Result') */
  readonly baseType: string;
  /** Union interfaces this alias should implement */
  readonly unionInterfaces: readonly UnionMembershipInfo[];
  /** Original type alias for description/tags */
  readonly typeAlias: TsTypeAlias;
}

/**
 * Generates PHP wrapper classes for type aliases referenced in unions.
 */
export class TypeAliasWrapperGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;
  private readonly interfaces: readonly TsInterface[];
  private readonly typeAliases: readonly TsTypeAlias[];

  constructor(
    config: GeneratorConfig,
    interfaces: readonly TsInterface[],
    typeAliases: readonly TsTypeAlias[]
  ) {
    this.config = config;
    this.classifier = new DomainClassifier();
    this.interfaces = interfaces;
    this.typeAliases = typeAliases;
  }

  /**
   * Finds all type aliases that need wrapper classes.
   *
   * A type alias needs a wrapper when:
   * 1. It's a simple alias (not a union): `type Foo = Bar`
   * 2. The base type is an interface that has a generated DTO
   * 3. The alias is referenced as a member of at least one union type
   *
   * @param _unionMembershipMap - Map of type names to their union memberships (unused, kept for API consistency)
   * @returns Array of type aliases that need wrapper generation
   */
  findAliasesNeedingWrappers(
    _unionMembershipMap: Map<string, UnionMembershipInfo[]>
  ): TypeAliasWrapperInfo[] {
    const result: TypeAliasWrapperInfo[] = [];

    for (const alias of this.typeAliases) {
      // Skip unions (contain |) - they get their own interface generation
      if (alias.type.includes('|')) {
        continue;
      }

      // Skip string literals (enums)
      if (alias.type.startsWith('"') || alias.type.startsWith("'")) {
        continue;
      }

      const baseType = alias.type.trim();

      // Check if base type is an interface (has a generated DTO)
      const baseInterface = this.interfaces.find((i) => i.name === baseType);
      if (!baseInterface) {
        continue;
      }

      // Check if this alias is referenced in any union
      // The union generator extracts members by name, so if 'EmptyResult' appears
      // in a union like 'EmptyResult | Foo | Bar', it will be in the membership map
      // But wait - the membership map is built from union members, which ARE the alias names
      // So we need to check if the alias NAME (not base type) is in any union
      const unionMemberships = this.findUnionMembershipsForAlias(alias.name);

      if (unionMemberships.length > 0) {
        result.push({
          aliasName: alias.name,
          baseType,
          unionInterfaces: unionMemberships,
          typeAlias: alias,
        });
      }
    }

    return result;
  }

  /**
   * Finds all unions that reference a type alias by name.
   *
   * Scans all union type aliases to find which ones include the given name
   * as a member.
   */
  private findUnionMembershipsForAlias(aliasName: string): UnionMembershipInfo[] {
    const memberships: UnionMembershipInfo[] = [];

    for (const alias of this.typeAliases) {
      // Only look at unions
      if (!alias.type.includes('|')) {
        continue;
      }

      // Extract member names from the union
      const members = alias.type
        .split('|')
        .map((m) => m.trim())
        .filter((m) => m.length > 0);

      // Check if our alias is a member
      if (members.includes(aliasName)) {
        const classification = this.classifier.classify(alias.name, alias.tags);
        memberships.push({
          unionName: alias.name,
          namespace: `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Union`,
          // Type alias wrappers typically don't have discriminator values
          // since they represent "empty" or "base" responses
          discriminatorField: undefined,
          discriminatorValue: undefined,
        });
      }
    }

    return memberships;
  }

  /**
   * Generates PHP wrapper class code for a type alias.
   */
  generate(info: TypeAliasWrapperInfo): string {
    const classification = this.classifier.classify(info.aliasName, info.typeAlias.tags);
    const indent = this.getIndent();

    return this.renderWrapperClass(info, classification, indent);
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
   * Renders the PHP wrapper class.
   */
  private renderWrapperClass(
    info: TypeAliasWrapperInfo,
    classification: DomainClassification,
    indent: string
  ): string {
    const lines: string[] = [];
    const namespace = `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}`;

    // Find the base class's classification to build its namespace
    const baseInterface = this.interfaces.find((i) => i.name === info.baseType);
    const baseClassification = baseInterface
      ? this.classifier.classify(info.baseType, baseInterface.tags)
      : classification;
    const baseNamespace = `${this.config.output.namespace}\\${baseClassification.domain}\\${baseClassification.subdomain}`;

    // PHP opening tag
    lines.push('<?php');
    lines.push('');
    lines.push('declare(strict_types=1);');
    lines.push('');

    // Namespace
    lines.push(`namespace ${namespace};`);
    lines.push('');

    // Use statements
    // Import base class if in different namespace
    if (baseNamespace !== namespace) {
      lines.push(`use ${baseNamespace}\\${info.baseType};`);
    }

    // Import union interfaces
    for (const union of info.unionInterfaces) {
      lines.push(`use ${union.namespace}\\${union.unionName}Interface;`);
    }
    lines.push('');

    // Class docblock
    lines.push('/**');
    if (info.typeAlias.description) {
      lines.push(...formatPhpDocDescription(info.typeAlias.description));
      lines.push(' *');
    } else {
      // Default description explaining this is a type alias wrapper
      lines.push(` * A response that indicates success but carries no additional data.`);
      lines.push(' *');
    }

    // Add explanation of why this wrapper exists
    lines.push(` * This class is a wrapper for {@see ${info.baseType}} that implements union interfaces.`);
    lines.push(` * In TypeScript, this is defined as: \`type ${info.aliasName} = ${info.baseType}\``);
    lines.push(' *');
    lines.push(` * PHP requires actual classes for union interface implementation, so this wrapper`);
    lines.push(` * provides type-safe compatibility with:`);
    for (const union of info.unionInterfaces) {
      lines.push(` * - {@see ${union.unionName}Interface}`);
    }
    lines.push(' *');
    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration
    const implementsList = info.unionInterfaces.map((u) => `${u.unionName}Interface`).join(', ');
    lines.push(`class ${info.aliasName} extends ${info.baseType} implements ${implementsList}`);
    lines.push('{');

    // The wrapper class inherits everything from the base class.
    // We only need to add a comment explaining this is intentionally empty.
    lines.push(`${indent}// Inherits all functionality from ${info.baseType}.`);
    lines.push(`${indent}// This wrapper exists solely to implement union interfaces for type safety.`);

    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Gets the output path for a wrapper class.
   */
  getOutputPath(info: TypeAliasWrapperInfo): string {
    const classification = this.classifier.classify(info.aliasName, info.typeAlias.tags);
    return `${classification.domain}/${classification.subdomain}/${info.aliasName}.php`;
  }
}

/**
 * MCP PHP Schema Generator - Enum Generator
 *
 * Generates PHP 7.4 class-based enums from TypeScript string literal unions.
 */

import type { TsTypeAlias, GeneratorConfig, DomainClassification } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { formatPhpDocDescription, getDeprecatedPhpDocTag } from './index.js';

/**
 * Generates PHP enum classes (class-based for PHP 7.4 compatibility).
 */
export class EnumGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;

  constructor(config: GeneratorConfig) {
    this.config = config;
    this.classifier = new DomainClassifier();
  }

  /**
   * Checks if a type alias represents an enum (string literal union).
   */
  isEnum(typeAlias: TsTypeAlias): boolean {
    // Check if the type is a union of string literals
    const type = typeAlias.type.trim();
    // Filter out empty strings caused by leading | in multi-line unions
    const parts = type.split('|').map((p) => p.trim()).filter((p) => p !== '');

    // Strip trailing comments (// ...) for enum detection
    const strippedParts = parts.map((p) => p.replace(/\s*\/\/.*$/, '').trim());

    return strippedParts.length > 1 && strippedParts.every((p) => /^["'].*["']$/.test(p));
  }

  /**
   * Extracts enum values from a string literal union type.
   */
  extractValues(typeAlias: TsTypeAlias): string[] {
    const type = typeAlias.type.trim();
    // Filter out empty strings caused by leading | in multi-line unions
    const parts = type.split('|').map((p) => p.trim()).filter((p) => p !== '');

    // Strip trailing comments (// ...) before extracting values
    const strippedParts = parts.map((p) => p.replace(/\s*\/\/.*$/, '').trim());

    return strippedParts
      .filter((p) => /^["'].*["']$/.test(p))
      .map((p) => p.slice(1, -1));
  }

  /**
   * Generates PHP code for an enum.
   */
  generate(typeAlias: TsTypeAlias): string {
    const classification = this.classifier.classify(typeAlias.name, typeAlias.tags);
    const values = this.extractValues(typeAlias);
    const indent = this.getIndent();

    return this.renderEnum(typeAlias.name, values, classification, typeAlias.description, typeAlias.tags, indent);
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
   * Renders the complete PHP enum class.
   */
  private renderEnum(
    name: string,
    values: string[],
    classification: DomainClassification,
    description: string | undefined,
    tags: TsTypeAlias['tags'],
    indent: string
  ): string {
    const lines: string[] = [];
    const namespace = this.getNamespace(classification);

    // PHP opening tag
    lines.push('<?php');
    lines.push('');
    lines.push('declare(strict_types=1);');
    lines.push('');

    // Namespace
    lines.push(`namespace ${namespace};`);
    lines.push('');

    // Use statements (version is in directory structure but NOT in namespace)
    lines.push(`use ${this.config.output.namespace}\\Common\\AbstractEnum;`);
    lines.push('');

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
    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration
    lines.push(`final class ${name} extends AbstractEnum`);
    lines.push('{');

    // Constants for each value
    for (const value of values) {
      const constName = this.toConstantName(value);
      lines.push(`${indent}public const ${constName} = '${value}';`);
    }
    lines.push('');

    // Static factory methods for each value
    for (const value of values) {
      const methodName = this.toMethodName(value);
      lines.push(`${indent}public static function ${methodName}(): self`);
      lines.push(`${indent}{`);
      lines.push(`${indent}${indent}return self::from('${value}');`);
      lines.push(`${indent}}`);
      lines.push('');
    }

    // values() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Returns all valid values for this enum.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return string[]`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function values(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return [`);
    for (const value of values) {
      const constName = this.toConstantName(value);
      lines.push(`${indent}${indent}${indent}self::${constName},`);
    }
    lines.push(`${indent}${indent}];`);
    lines.push(`${indent}}`);

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Gets the PHP namespace for a classification.
   * Note: Version is used in directory structure but NOT in namespace (PHP namespaces can't start with digits)
   */
  private getNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Enum`;
  }

  /**
   * Converts a value to CONSTANT_NAME format.
   */
  private toConstantName(value: string): string {
    // Handle special characters
    return value
      .replace(/[^a-zA-Z0-9]/g, '_')
      .replace(/([a-z])([A-Z])/g, '$1_$2')
      .toUpperCase()
      .replace(/_+/g, '_')
      .replace(/^_|_$/g, '');
  }

  /**
   * Converts a value to a method name.
   */
  private toMethodName(value: string): string {
    // Convert to camelCase
    const parts = value.split(/[^a-zA-Z0-9]+/);
    return parts
      .map((p, i) => (i === 0 ? p.toLowerCase() : p.charAt(0).toUpperCase() + p.slice(1).toLowerCase()))
      .join('');
  }
}

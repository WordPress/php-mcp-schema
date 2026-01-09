/**
 * MCP PHP Schema Generator - Numeric Enum Generator
 *
 * Generates PHP classes with integer constants from TypeScript numeric enums.
 * These are simpler than string literal union enums - just constant definitions.
 */

import type { TsEnum, GeneratorConfig, DomainClassification } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { formatPhpDocDescription } from './index.js';

/**
 * Generates PHP constant classes from TypeScript numeric enums.
 */
export class NumericEnumGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;

  constructor(config: GeneratorConfig) {
    this.config = config;
    this.classifier = new DomainClassifier();
  }

  /**
   * Checks if all members have numeric values.
   */
  isNumericEnum(tsEnum: TsEnum): boolean {
    return tsEnum.members.every((m) => typeof m.value === 'number');
  }

  /**
   * Generates PHP code for a numeric enum.
   */
  generate(tsEnum: TsEnum): string {
    const classification = this.classifier.classify(tsEnum.name, tsEnum.tags);
    const indent = this.getIndent();

    return this.renderNumericEnum(tsEnum, classification, indent);
  }

  /**
   * Gets the domain classification for output path.
   */
  classify(tsEnum: TsEnum): DomainClassification {
    return this.classifier.classify(tsEnum.name, tsEnum.tags);
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
   * Renders the complete PHP constants class.
   */
  private renderNumericEnum(
    tsEnum: TsEnum,
    classification: DomainClassification,
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

    // Class docblock
    lines.push('/**');
    if (tsEnum.description) {
      lines.push(...formatPhpDocDescription(tsEnum.description));
      lines.push(' *');
    }
    lines.push(` * @since ${this.config.schema.version}`);
    lines.push(' *');
    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration - final class with only constants
    lines.push(`final class ${tsEnum.name}`);
    lines.push('{');

    // Constants for each member
    for (const member of tsEnum.members) {
      lines.push(`${indent}public const ${member.name} = ${member.value};`);
    }
    lines.push('');

    // values() static method returning all values
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Returns all valid error codes.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return int[]`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function values(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return [`);
    for (const member of tsEnum.members) {
      lines.push(`${indent}${indent}${indent}self::${member.name},`);
    }
    lines.push(`${indent}${indent}];`);
    lines.push(`${indent}}`);
    lines.push('');

    // names() static method returning constant names mapped to values
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Returns all error code names mapped to their values.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return array<string, int>`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function names(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return [`);
    for (const member of tsEnum.members) {
      lines.push(`${indent}${indent}${indent}'${member.name}' => self::${member.name},`);
    }
    lines.push(`${indent}${indent}];`);
    lines.push(`${indent}}`);
    lines.push('');

    // isValid() static method to check if a code is valid
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Checks if the given error code is valid.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param int $code`);
    lines.push(`${indent} * @return bool`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function isValid(int $code): bool`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return in_array($code, self::values(), true);`);
    lines.push(`${indent}}`);
    lines.push('');

    // nameFor() static method to get constant name for a code
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Gets the constant name for an error code.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param int $code`);
    lines.push(`${indent} * @return string|null The constant name, or null if not found`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function nameFor(int $code): ?string`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}$flipped = array_flip(self::names());`);
    lines.push(`${indent}${indent}return $flipped[$code] ?? null;`);
    lines.push(`${indent}}`);

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Gets the PHP namespace for a classification.
   */
  private getNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Enum`;
  }
}

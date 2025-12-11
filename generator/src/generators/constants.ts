/**
 * MCP PHP Schema Generator - Constants Generator
 *
 * Generates a PHP class containing all exported constants from the TypeScript schema.
 * This includes protocol version strings, JSON-RPC version, and error codes.
 */

import type { TsConstant, GeneratorConfig } from '../types/index.js';
import { formatPhpDocDescription } from './index.js';

/**
 * Escapes a string value for use in a PHP single-quoted string literal.
 *
 * In PHP single-quoted strings, only single quotes and backslashes need escaping:
 * - ' becomes \'
 * - \ becomes \\
 */
function escapePhpSingleQuotedString(value: string): string {
  return value.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

/**
 * Generates a PHP constants class from TypeScript exported constants.
 */
export class ConstantsGenerator {
  private readonly config: GeneratorConfig;

  constructor(config: GeneratorConfig) {
    this.config = config;
  }

  /**
   * Generates the PHP constants class.
   */
  generate(constants: readonly TsConstant[]): string {
    const indent = this.getIndent();
    const namespace = `${this.config.output.namespace}\\Common`;

    const lines: string[] = [];

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
    lines.push(' * MCP Protocol Constants.');
    lines.push(' *');
    lines.push(' * Contains all exported constants from the MCP TypeScript schema including:');
    lines.push(' * - Protocol version constants');
    lines.push(' * - JSON-RPC version');
    lines.push(' * - Standard JSON-RPC error codes');
    lines.push(' * - MCP-specific error codes');
    lines.push(' *');
    lines.push(` * @since ${this.config.schema.version}`);
    lines.push(' *');
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration
    lines.push('final class McpConstants');
    lines.push('{');

    // Group constants by type for organization
    const stringConstants = constants.filter((c) => c.valueType === 'string');
    const numericConstants = constants.filter((c) => c.valueType === 'number');

    // String constants (protocol versions, etc.)
    if (stringConstants.length > 0) {
      lines.push(`${indent}// Protocol constants`);
      for (const constant of stringConstants) {
        if (constant.description) {
          lines.push('');
          lines.push(`${indent}/**`);
          lines.push(...formatPhpDocDescription(constant.description, indent));
          lines.push(`${indent} */`);
        }
        lines.push(`${indent}public const ${constant.name} = '${escapePhpSingleQuotedString(String(constant.value))}';`);
      }
      lines.push('');
    }

    // Numeric constants (error codes)
    if (numericConstants.length > 0) {
      lines.push(`${indent}// Error codes`);
      for (const constant of numericConstants) {
        if (constant.description) {
          lines.push('');
          lines.push(`${indent}/**`);
          lines.push(...formatPhpDocDescription(constant.description, indent));
          lines.push(`${indent} */`);
        }
        lines.push(`${indent}public const ${constant.name} = ${constant.value};`);
      }
      lines.push('');
    }

    // Helper methods for error codes
    if (numericConstants.length > 0) {
      this.addErrorCodeHelpers(lines, numericConstants, indent);
    }

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Adds helper methods for error code handling.
   */
  private addErrorCodeHelpers(
    lines: string[],
    errorCodes: readonly TsConstant[],
    indent: string
  ): void {
    // getErrorCodes() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Returns all error codes defined in this class.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return int[]`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function getErrorCodes(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return [`);
    for (const constant of errorCodes) {
      lines.push(`${indent}${indent}${indent}self::${constant.name},`);
    }
    lines.push(`${indent}${indent}];`);
    lines.push(`${indent}}`);
    lines.push('');

    // getErrorCodeNames() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Returns error code names mapped to their values.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return array<string, int>`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function getErrorCodeNames(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return [`);
    for (const constant of errorCodes) {
      lines.push(`${indent}${indent}${indent}'${constant.name}' => self::${constant.name},`);
    }
    lines.push(`${indent}${indent}];`);
    lines.push(`${indent}}`);
    lines.push('');

    // isValidErrorCode() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Checks if the given error code is valid.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param int $code`);
    lines.push(`${indent} * @return bool`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function isValidErrorCode(int $code): bool`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return in_array($code, self::getErrorCodes(), true);`);
    lines.push(`${indent}}`);
    lines.push('');

    // getErrorCodeName() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Gets the constant name for an error code.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param int $code`);
    lines.push(`${indent} * @return string|null The constant name, or null if not found`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function getErrorCodeName(int $code): ?string`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}$flipped = array_flip(self::getErrorCodeNames());`);
    lines.push(`${indent}${indent}return $flipped[$code] ?? null;`);
    lines.push(`${indent}}`);
    lines.push('');

    // isStandardJsonRpcError() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Checks if an error code is a standard JSON-RPC error.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * Standard JSON-RPC errors are in the range -32700 to -32600.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param int $code`);
    lines.push(`${indent} * @return bool`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function isStandardJsonRpcError(int $code): bool`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return $code >= -32700 && $code <= -32600;`);
    lines.push(`${indent}}`);
  }

  /**
   * Gets the output path for the constants file.
   */
  getOutputPath(): string {
    return 'Common/McpConstants.php';
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
}

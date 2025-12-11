/**
 * MCP PHP Schema Generator - Type Mapper
 *
 * Maps TypeScript types to PHP types.
 */

import type { PhpType, TsConstant } from '../types/index.js';

/**
 * Map of constant names to their values, used for resolving typeof expressions.
 */
export type ConstantsMap = ReadonlyMap<string, string | number>;

/**
 * Creates a constants map from an array of TsConstant objects.
 */
export function createConstantsMap(constants: readonly TsConstant[] | undefined): ConstantsMap {
  const map = new Map<string, string | number>();
  if (constants) {
    for (const c of constants) {
      map.set(c.name, c.value);
    }
  }
  return map;
}

/**
 * Maps TypeScript types to PHP types.
 */
export class TypeMapper {
  /**
   * Primitive type mappings from TypeScript to PHP.
   * Note: 'mixed' is PHP 8.0+ only, so we use empty string for untyped.
   */
  private static readonly PRIMITIVE_MAP: Record<string, string> = {
    string: 'string',
    number: 'float',
    boolean: 'bool',
    null: 'null',
    undefined: '', // TypeScript undefined - untyped in PHP 7.4 (mixed is PHP 8.0+)
    any: '', // untyped in PHP 7.4
    unknown: '', // untyped in PHP 7.4
    void: 'void',
    never: 'void',
    object: 'object',
  };

  /**
   * Types that should be untyped in PHP 7.4 (no type hint, only PHPDoc).
   */
  private static readonly UNTYPED_PRIMITIVES = new Set(['undefined', 'any', 'unknown']);

  /**
   * Integer type patterns (TypeScript number that should be PHP int).
   *
   * TypeScript has only `number` type - no int/float distinction.
   * We use semantic property names to determine PHP type:
   * - Counts, lengths, sizes → int
   * - Priorities, temperatures, bounds → float
   */
  private static readonly INTEGER_PATTERNS = [
    /^-?\d+$/, // Literal integers
    /.Id$/i, // Compound IDs like requestId, sessionId (NOT standalone "id" - JSON-RPC id is string|number)
    /Length$/i, // minLength, maxLength - character counts
    /Items$/i, // minItems, maxItems - array item counts
    /^size$/i, // file size in bytes
    /^total$/i, // total count
    /^ttl$/i, // time-to-live in seconds
    /Interval$/i, // pollInterval, etc. - time intervals
    /Count$/i, // any count property
    /Index$/i, // array indices
    /Offset$/i, // byte/character offsets
    /Depth$/i, // nesting depth
    /Level$/i, // log level, etc.
    /Port$/i, // network port numbers
  ];

  /**
   * Strips TypeScript single-line comments from a type string.
   * Handles inline comments like: "working" // description | "cancelled"
   */
  private static stripComments(type: string): string {
    // Remove single-line comments (// ...) but be careful with URLs
    // Split on | first, strip comments from each part, then rejoin
    const parts = type.split('|');
    const cleaned = parts.map((part) => {
      // Remove // comment to end of this part
      const commentIndex = part.indexOf('//');
      if (commentIndex >= 0) {
        return part.slice(0, commentIndex).trim();
      }
      return part.trim();
    });
    return cleaned.filter((p) => p !== '').join(' | ');
  }

  /**
   * Maps a TypeScript type to a PHP type.
   *
   * @param tsType - The TypeScript type string to map
   * @param propertyName - Optional property name for semantic type inference
   * @param constants - Optional map of constant names to values for resolving typeof expressions
   */
  static mapType(tsType: string, propertyName?: string, constants?: ConstantsMap): PhpType {
    const trimmed = this.stripComments(tsType.trim());

    // Handle TypeScript 'typeof' expressions - map to the underlying type
    // e.g., 'typeof JSONRPC_VERSION' -> string with literal phpDocType
    if (trimmed.startsWith('typeof ')) {
      const constName = trimmed.slice(7).trim(); // Remove 'typeof ' prefix
      const constValue = constants?.get(constName);

      if (constValue !== undefined) {
        // We have the actual constant value from the schema
        return {
          type: 'string',
          nullable: false,
          isArray: false,
          phpDocType: `'${constValue}'`, // Use the actual constant value as literal type
        };
      }

      // Fallback: constant not found, use plain string type
      return {
        type: 'string',
        nullable: false,
        isArray: false,
      };
    }

    // Handle inline object types { ... } - map to array or object
    if (this.isInlineObjectType(trimmed)) {
      // Check if it's an index signature { [key: string]: T }
      if (/^\{\s*\[/.test(trimmed)) {
        return {
          type: 'array',
          nullable: false,
          isArray: true,
          phpDocType: 'array<string, mixed>',
        };
      }
      // Regular inline object
      return {
        type: 'object',
        nullable: false,
        isArray: false,
        phpDocType: 'object',
      };
    }

    // Handle import() types - treat as the imported type name
    if (trimmed.startsWith('import(')) {
      const match = trimmed.match(/import\([^)]+\)\.(\w+)/);
      if (match?.[1]) {
        return {
          type: match[1],
          nullable: false,
          isArray: false,
        };
      }
      return { type: '', nullable: false, isArray: false, isUntyped: true, phpDocType: 'mixed' };
    }

    // Handle nullable types (Type | null, Type | undefined)
    if (this.isNullableType(trimmed)) {
      const baseType = this.extractNonNullType(trimmed);
      const mapped = this.mapType(baseType, propertyName);
      return { ...mapped, nullable: true };
    }

    // Handle array types
    if (this.isArrayType(trimmed)) {
      const itemType = this.extractArrayItemType(trimmed);
      const mappedItem = this.mapType(itemType, propertyName);
      return {
        type: 'array',
        nullable: false,
        isArray: true,
        arrayItemType: mappedItem.type,
        phpDocType: `${mappedItem.type}[]`,
      };
    }

    // Handle literal string types ("value")
    if (this.isStringLiteral(trimmed)) {
      return {
        type: 'string',
        nullable: false,
        isArray: false,
        phpDocType: `'${this.extractStringLiteralValue(trimmed)}'`,
      };
    }

    // Handle literal number types
    if (this.isNumberLiteral(trimmed)) {
      const isInt = /^-?\d+$/.test(trimmed);
      return {
        type: isInt ? 'int' : 'float',
        nullable: false,
        isArray: false,
        phpDocType: trimmed,
      };
    }

    // Handle primitive types
    const primitive = this.PRIMITIVE_MAP[trimmed];
    if (primitive !== undefined) {
      // Check if number should be int based on property name
      if (primitive === 'float' && propertyName && this.shouldBeInteger(propertyName)) {
        return {
          type: 'int',
          nullable: false,
          isArray: false,
        };
      }
      // Handle untyped primitives (undefined, any, unknown)
      if (this.UNTYPED_PRIMITIVES.has(trimmed)) {
        return {
          type: '',
          nullable: false,
          isArray: false,
          isUntyped: true,
          phpDocType: 'mixed',
        };
      }
      return {
        type: primitive,
        nullable: false,
        isArray: false,
      };
    }

    // Handle union types (convert to mixed or first concrete type)
    if (this.isUnionType(trimmed)) {
      return this.mapUnionType(trimmed, propertyName);
    }

    // Handle intersection types (typically interfaces)
    if (this.isIntersectionType(trimmed)) {
      // For intersections, we typically use the first type or a combined interface
      const types = trimmed.split('&').map((t) => t.trim());
      return this.mapType(types[0] ?? 'mixed', propertyName);
    }

    // Handle generic types like Record<K, V>, Map<K, V>
    if (this.isGenericType(trimmed)) {
      return this.mapGenericType(trimmed);
    }

    // Default: treat as a class/interface reference
    return {
      type: this.toPhpClassName(trimmed),
      nullable: false,
      isArray: false,
    };
  }

  /**
   * Checks if a type is nullable (contains | null or | undefined).
   */
  private static isNullableType(type: string): boolean {
    return /\|\s*(null|undefined)/.test(type) || /(null|undefined)\s*\|/.test(type);
  }

  /**
   * Extracts the non-null type from a nullable type.
   */
  private static extractNonNullType(type: string): string {
    return type
      .split('|')
      .map((t) => t.trim())
      .filter((t) => t !== 'null' && t !== 'undefined')
      .join(' | ');
  }

  /**
   * Checks if a type is an array type.
   */
  private static isArrayType(type: string): boolean {
    return (
      type.endsWith('[]') ||
      type.startsWith('Array<') ||
      type.startsWith('ReadonlyArray<') ||
      type.startsWith('readonly ') && type.includes('[]')
    );
  }

  /**
   * Extracts the item type from an array type.
   */
  private static extractArrayItemType(type: string): string {
    if (type.endsWith('[]')) {
      return type.slice(0, -2).replace(/^readonly\s+/, '');
    }
    if (type.startsWith('Array<') || type.startsWith('ReadonlyArray<')) {
      const start = type.indexOf('<') + 1;
      const end = type.lastIndexOf('>');
      return type.slice(start, end);
    }
    return type;
  }

  /**
   * Checks if a type is a string literal.
   * Only matches single literals like "value" or 'value', not unions like "a" | "b".
   */
  private static isStringLiteral(type: string): boolean {
    // Must start and end with same quote type, and not contain unescaped quotes in middle
    if (type.startsWith('"') && type.endsWith('"')) {
      // Check for unescaped double quotes in the middle (excluding first/last)
      const middle = type.slice(1, -1);
      return !middle.includes('"') || /^[^"\\]*(?:\\.[^"\\]*)*$/.test(middle);
    }
    if (type.startsWith("'") && type.endsWith("'")) {
      // Check for unescaped single quotes in the middle
      const middle = type.slice(1, -1);
      return !middle.includes("'") || /^[^'\\]*(?:\\.[^'\\]*)*$/.test(middle);
    }
    return false;
  }

  /**
   * Extracts the value from a string literal type.
   */
  private static extractStringLiteralValue(type: string): string {
    return type.slice(1, -1);
  }

  /**
   * Checks if a type is a number literal.
   */
  private static isNumberLiteral(type: string): boolean {
    return /^-?\d+(\.\d+)?$/.test(type);
  }

  /**
   * Checks if a type is an inline object type { ... }.
   */
  private static isInlineObjectType(type: string): boolean {
    if (!type.startsWith('{')) {
      return false;
    }
    // Match balanced braces
    let depth = 0;
    for (const char of type) {
      if (char === '{') depth++;
      if (char === '}') depth--;
    }
    return depth === 0 && type.endsWith('}');
  }

  /**
   * Checks if a property name suggests an integer type.
   */
  private static shouldBeInteger(propertyName: string): boolean {
    return this.INTEGER_PATTERNS.some((pattern) => pattern.test(propertyName));
  }

  /**
   * Checks if a type is a union type.
   */
  private static isUnionType(type: string): boolean {
    // Check for | outside of generic brackets
    let depth = 0;
    for (const char of type) {
      if (char === '<' || char === '(') depth++;
      if (char === '>' || char === ')') depth--;
      if (char === '|' && depth === 0) return true;
    }
    return false;
  }

  /**
   * Maps a union type to PHP.
   */
  private static mapUnionType(type: string, propertyName?: string): PhpType {
    const types = this.splitUnionType(type);
    const nonNullTypes = types.filter((t) => t !== 'null' && t !== 'undefined');

    if (nonNullTypes.length === 0) {
      return { type: 'null', nullable: true, isArray: false };
    }

    if (nonNullTypes.length === 1) {
      const mapped = this.mapType(nonNullTypes[0] ?? 'mixed', propertyName);
      return {
        ...mapped,
        nullable: types.length !== nonNullTypes.length,
      };
    }

    // Multiple non-null types - check if they share a common base
    const allStrings = nonNullTypes.every((t) => this.isStringLiteral(t));
    if (allStrings) {
      return {
        type: 'string',
        nullable: types.length !== nonNullTypes.length,
        isArray: false,
        phpDocType: nonNullTypes.map((t) => `'${this.extractStringLiteralValue(t)}'`).join('|'),
      };
    }

    // Default to untyped for complex unions (PHP 7.4 doesn't support union type hints)
    return {
      type: '',
      nullable: types.length !== nonNullTypes.length,
      isArray: false,
      isUntyped: true,
      phpDocType: nonNullTypes.join('|'),
    };
  }

  /**
   * Splits a union type into its constituent types.
   */
  private static splitUnionType(type: string): string[] {
    const types: string[] = [];
    let current = '';
    let depth = 0;

    for (const char of type) {
      if (char === '<' || char === '(' || char === '[') depth++;
      if (char === '>' || char === ')' || char === ']') depth--;

      if (char === '|' && depth === 0) {
        types.push(current.trim());
        current = '';
      } else {
        current += char;
      }
    }

    if (current.trim()) {
      types.push(current.trim());
    }

    return types;
  }

  /**
   * Checks if a type is an intersection type.
   */
  private static isIntersectionType(type: string): boolean {
    let depth = 0;
    for (const char of type) {
      if (char === '<' || char === '(') depth++;
      if (char === '>' || char === ')') depth--;
      if (char === '&' && depth === 0) return true;
    }
    return false;
  }

  /**
   * Checks if a type is a generic type.
   */
  private static isGenericType(type: string): boolean {
    return type.includes('<') && type.includes('>');
  }

  /**
   * Maps a generic type to PHP.
   */
  private static mapGenericType(type: string): PhpType {
    const genericName = type.slice(0, type.indexOf('<'));

    if (genericName === 'Record' || genericName === 'Map') {
      return {
        type: 'array',
        nullable: false,
        isArray: true,
        phpDocType: 'array<string, mixed>',
      };
    }

    if (genericName === 'Set') {
      return {
        type: 'array',
        nullable: false,
        isArray: true,
        phpDocType: 'array<mixed>',
      };
    }

    if (genericName === 'Promise') {
      // Extract the resolved type
      const innerType = type.slice(type.indexOf('<') + 1, type.lastIndexOf('>'));
      return this.mapType(innerType);
    }

    // Default: treat as array
    return {
      type: 'array',
      nullable: false,
      isArray: true,
    };
  }

  /**
   * Converts a TypeScript type name to a PHP class name.
   */
  private static toPhpClassName(typeName: string): string {
    // Remove generic parameters
    const baseName = typeName.includes('<') ? typeName.slice(0, typeName.indexOf('<')) : typeName;

    // Handle namespaced types
    return baseName.replace(/\./g, '\\');
  }

  /**
   * Gets the PHP type hint string for use in method signatures.
   * Returns empty string for untyped (PHP 7.4 doesn't support mixed).
   */
  static getTypeHint(phpType: PhpType): string {
    // Untyped properties have no PHP type hint
    if (phpType.isUntyped || phpType.type === '') {
      return '';
    }
    if (phpType.nullable) {
      return `?${phpType.type}`;
    }
    return phpType.type;
  }

  /**
   * Gets the PHPDoc type string.
   */
  static getPhpDocType(phpType: PhpType): string {
    // Prefer explicit phpDocType (may contain FQN), fallback to type
    let docType = phpType.phpDocType ?? phpType.type;

    // Only use arrayItemType[] format if no phpDocType was explicitly set
    if (phpType.isArray && phpType.arrayItemType && !phpType.phpDocType) {
      docType = `${phpType.arrayItemType}[]`;
    }

    if (phpType.nullable) {
      docType = `${docType}|null`;
    }

    return docType;
  }
}

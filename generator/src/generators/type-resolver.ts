/**
 * MCP PHP Schema Generator - Type Resolver
 *
 * Resolves TypeScript type references to their actual definitions.
 * Handles type aliases, union types, and interface references.
 */

import type { TsTypeAlias, TsInterface, PhpType, DomainClassification } from '../types/index.js';
import { TypeMapper } from './type-mapper.js';
import { DomainClassifier } from './domain-classifier.js';

/**
 * Information about a resolved type for PHP code generation.
 */
export interface ResolvedType {
  /** The PHP type information */
  readonly phpType: PhpType;
  /** If this is a class reference, the fully qualified namespace */
  readonly namespace?: string;
  /** The simple class name (without namespace) */
  readonly className?: string;
  /** Whether this needs an import statement */
  readonly needsImport: boolean;
}

/**
 * Resolves TypeScript types to PHP types with namespace information.
 * Handles type alias resolution and cross-domain references.
 */
export class TypeResolver {
  private readonly typeAliasMap: Map<string, TsTypeAlias>;
  private readonly interfaceMap: Map<string, TsInterface>;
  private readonly classifier: DomainClassifier;
  private readonly baseNamespace: string;

  constructor(
    typeAliases: readonly TsTypeAlias[],
    interfaces: readonly TsInterface[],
    baseNamespace: string,
    classifier?: DomainClassifier
  ) {
    this.typeAliasMap = new Map(typeAliases.map((a) => [a.name, a]));
    this.interfaceMap = new Map(interfaces.map((i) => [i.name, i]));
    this.classifier = classifier ?? new DomainClassifier();
    this.baseNamespace = baseNamespace;
  }

  /**
   * Resolves a type name to its PHP type with namespace information.
   */
  resolve(typeName: string, propertyName?: string, contextTags?: readonly { tagName: string; text?: string }[]): ResolvedType {
    const trimmed = typeName.trim();

    // Handle union types with undefined - strip undefined and make nullable
    if (trimmed.includes('|') && trimmed.includes('undefined')) {
      const nonUndefinedParts = trimmed
        .split('|')
        .map((p) => p.trim())
        .filter((p) => p !== 'undefined' && p !== '');

      if (nonUndefinedParts.length === 0) {
        return {
          phpType: { type: '', nullable: true, isArray: false, isUntyped: true, phpDocType: 'mixed' },
          needsImport: false,
        };
      }

      // Resolve the non-undefined part
      const resolvedPart = this.resolve(nonUndefinedParts.join(' | '), propertyName, contextTags);
      return {
        ...resolvedPart,
        phpType: { ...resolvedPart.phpType, nullable: true },
      };
    }

    // Check if it's a type alias
    const typeAlias = this.typeAliasMap.get(trimmed);
    if (typeAlias) {
      return this.resolveTypeAlias(typeAlias, propertyName);
    }

    // Check if it's an interface reference
    const iface = this.interfaceMap.get(trimmed);
    if (iface) {
      return this.resolveInterface(iface, contextTags);
    }

    // Handle array types BEFORE complex union check - this ensures (A | B | C)[]
    // is correctly identified as an array of union type, not a malformed union
    if (this.isArrayType(trimmed)) {
      const itemType = this.extractArrayItemType(trimmed);
      const resolvedItem = this.resolve(itemType, propertyName, contextTags);

      // Use phpDocType for array item if available (contains FQN for complex types)
      const itemDocType = resolvedItem.phpType.phpDocType ?? resolvedItem.phpType.type;

      return {
        phpType: {
          type: 'array',
          nullable: false,
          isArray: true,
          arrayItemType: resolvedItem.phpType.type,
          phpDocType: `array<${itemDocType}>`,
        },
        namespace: resolvedItem.namespace,
        className: resolvedItem.className,
        needsImport: resolvedItem.needsImport,
      };
    }

    // Check if it's a union that contains interfaces or type aliases
    if (this.isComplexUnion(trimmed)) {
      // Resolve each member to get FQN for PHPDoc
      const members = trimmed
        .split('|')
        .map((p) => p.trim())
        .filter((p) => p !== '' && p !== 'null' && p !== 'undefined');

      const primitives = new Set(['string', 'number', 'boolean', 'int', 'float']);

      // Build FQN PHPDoc type for each member
      const fqnMembers = members.map((memberName) => {
        // Handle array types within union (e.g., SamplingMessageContentBlock[])
        if (memberName.endsWith('[]')) {
          const itemType = memberName.slice(0, -2);
          const resolvedItem = this.resolveSingleType(itemType);
          return `array<${resolvedItem}>`;
        }

        // Check for primitives
        if (primitives.has(memberName)) {
          return memberName === 'number' ? 'int|float' : memberName;
        }

        return this.resolveSingleType(memberName);
      });

      return {
        phpType: {
          type: '',
          nullable: trimmed.includes('null'),
          isArray: false,
          isUntyped: true,
          phpDocType: fqnMembers.join('|'),
        },
        needsImport: false,
      };
    }

    // Check if it's an intersection type (A & B) - use untyped since PHP/PHPStan
    // can't resolve intersection types of unrelated classes
    if (trimmed.includes('&')) {
      return {
        phpType: {
          type: '',
          nullable: false,
          isArray: false,
          isUntyped: true,
          phpDocType: 'mixed',
        },
        needsImport: false,
      };
    }

    // Fall back to TypeMapper for primitives and other types
    const phpType = TypeMapper.mapType(typeName, propertyName);

    // If TypeMapper returned a class-like type, check if we need an import
    if (this.isClassType(phpType.type)) {
      // This is an unknown type reference - use untyped to be safe
      return {
        phpType: { ...phpType, type: '', isUntyped: true, phpDocType: trimmed },
        needsImport: false,
      };
    }

    return {
      phpType,
      needsImport: false,
    };
  }

  /**
   * Resolves a type alias to its PHP type.
   * For union type aliases, returns the generated interface type with proper namespace.
   */
  private resolveTypeAlias(alias: TsTypeAlias, propertyName?: string): ResolvedType {
    const type = alias.type.trim();

    // Check if it's a primitive union (like string | number)
    if (this.isPrimitiveUnion(type)) {
      const phpType = TypeMapper.mapType(type, propertyName);
      return {
        phpType,
        needsImport: false,
      };
    }

    // Check if it's a union of interface references
    // For these, we use the generated interface (e.g., SamplingMessageContentBlockInterface)
    if (this.isInterfaceUnion(type)) {
      // Use the generated interface for this union type alias
      const interfaceName = `${alias.name}Interface`;
      const classification = this.classifier.classify(alias.name, alias.tags);
      const namespace = `${this.baseNamespace}\\${classification.domain}\\${classification.subdomain}\\Union`;

      return {
        phpType: {
          type: interfaceName,
          nullable: type.includes('null'),
          isArray: false,
        },
        namespace,
        className: interfaceName,
        needsImport: true,
      };
    }

    // Single interface reference in type alias
    const singleRef = this.interfaceMap.get(type);
    if (singleRef) {
      return this.resolveInterface(singleRef, alias.tags);
    }

    // Handle intersection types (A & B) - use untyped since PHP/PHPStan
    // can't resolve intersection types of unrelated classes
    if (type.includes('&')) {
      return {
        phpType: {
          type: '',
          nullable: false,
          isArray: false,
          isUntyped: true,
          phpDocType: 'mixed',
        },
        needsImport: false,
      };
    }

    // Fall back to TypeMapper
    const phpType = TypeMapper.mapType(type, propertyName);
    return {
      phpType,
      needsImport: false,
    };
  }

  /**
   * Resolves an interface reference to its PHP type with namespace.
   * DTOs are placed directly in the subdomain namespace (no Dto subfolder).
   */
  private resolveInterface(
    iface: TsInterface,
    _contextTags?: readonly { tagName: string; text?: string }[]
  ): ResolvedType {
    const classification = this.classifier.classify(iface.name, iface.tags, iface.syntheticParent);
    const namespace = `${this.baseNamespace}\\${classification.domain}\\${classification.subdomain}`;

    return {
      phpType: {
        type: iface.name,
        nullable: false,
        isArray: false,
      },
      namespace,
      className: iface.name,
      needsImport: true,
    };
  }

  /**
   * Checks if a type is an array type.
   */
  private isArrayType(type: string): boolean {
    return (
      type.endsWith('[]') ||
      type.startsWith('Array<') ||
      type.startsWith('ReadonlyArray<') ||
      (type.startsWith('readonly ') && type.includes('[]'))
    );
  }

  /**
   * Extracts the item type from an array type.
   */
  private extractArrayItemType(type: string): string {
    if (type.endsWith('[]')) {
      let itemType = type.slice(0, -2).replace(/^readonly\s+/, '');
      // Handle parenthesized types like (A | B)[]
      if (itemType.startsWith('(') && itemType.endsWith(')')) {
        itemType = itemType.slice(1, -1);
      }
      return itemType;
    }
    if (type.startsWith('Array<') || type.startsWith('ReadonlyArray<')) {
      const start = type.indexOf('<') + 1;
      const end = type.lastIndexOf('>');
      return type.slice(start, end);
    }
    return type;
  }

  /**
   * Checks if a type string represents a primitive union.
   */
  private isPrimitiveUnion(type: string): boolean {
    const parts = type
      .split('|')
      .map((p) => p.trim())
      .filter((p) => p !== 'null' && p !== 'undefined');

    const primitives = new Set(['string', 'number', 'boolean', 'any', 'unknown']);

    return parts.every(
      (p) =>
        primitives.has(p) ||
        /^["']/.test(p) || // string literal
        /^-?\d+(\.\d+)?$/.test(p) // number literal
    );
  }

  /**
   * Checks if a type string represents a union of interface references.
   */
  private isInterfaceUnion(type: string): boolean {
    const parts = type
      .split('|')
      .map((p) => p.trim())
      .filter((p) => p !== '' && p !== 'null' && p !== 'undefined');

    // At least one part must be a known interface
    return parts.some((p) => this.interfaceMap.has(p));
  }

  /**
   * Checks if a type string is a union that contains interfaces or type aliases.
   * This is broader than isInterfaceUnion - also handles type aliases that are unions.
   */
  private isComplexUnion(type: string): boolean {
    if (!type.includes('|')) {
      return false;
    }

    const parts = type
      .split('|')
      .map((p) => p.trim())
      .filter((p) => p !== '' && p !== 'null' && p !== 'undefined');

    // Check if any part is an interface, type alias, or array of either
    return parts.some((p) => {
      const baseName = p.endsWith('[]') ? p.slice(0, -2) : p;
      return this.interfaceMap.has(baseName) || this.typeAliasMap.has(baseName);
    });
  }

  /**
   * Resolves a single type name (not an array) to its FQN string.
   * Used for building PHPDoc types in complex unions.
   */
  private resolveSingleType(typeName: string): string {
    // Check if it's an interface
    const iface = this.interfaceMap.get(typeName);
    if (iface) {
      const classification = this.classifier.classify(typeName, iface.tags, iface.syntheticParent);
      return `\\${this.baseNamespace}\\${classification.domain}\\${classification.subdomain}\\${typeName}`;
    }

    // Check if it's a type alias that is a union (has generated interface)
    const alias = this.typeAliasMap.get(typeName);
    if (alias && this.isInterfaceUnion(alias.type)) {
      const classification = this.classifier.classify(typeName, alias.tags);
      return `\\${this.baseNamespace}\\${classification.domain}\\${classification.subdomain}\\Union\\${typeName}Interface`;
    }

    // Unknown type - return as-is
    return typeName;
  }

  /**
   * Checks if a type string looks like a class/interface name.
   */
  private isClassType(type: string): boolean {
    const primitives = new Set([
      'string',
      'int',
      'float',
      'bool',
      'array',
      'object',
      'null',
      'void',
      '', // Empty string for untyped
    ]);

    return !primitives.has(type) && /^[A-Z]/.test(type);
  }

  /**
   * Gets the domain classification for a type name.
   */
  getClassification(typeName: string): DomainClassification | undefined {
    const iface = this.interfaceMap.get(typeName);
    if (iface) {
      return this.classifier.classify(iface.name, iface.tags, iface.syntheticParent);
    }

    const alias = this.typeAliasMap.get(typeName);
    if (alias) {
      return this.classifier.classify(alias.name, alias.tags);
    }

    return undefined;
  }
}

/**
 * MCP PHP Schema Generator - Intersection Type Wrapper Generator
 *
 * Generates PHP wrapper classes for TypeScript intersection types (A & B)
 * that are referenced in union types.
 *
 * ## Problem This Solves
 *
 * In TypeScript, an intersection type like `type GetTaskResult = Result & Task`
 * creates a type that has ALL properties from both Result and Task. When such
 * types appear in union types like:
 *
 * ```typescript
 * type ClientResult = EmptyResult | GetTaskResult | CancelTaskResult | ...
 * type ServerResult = EmptyResult | GetTaskResult | CancelTaskResult | ...
 * ```
 *
 * The PHP generator creates interfaces (e.g., `ClientResultInterface`) that
 * list `GetTaskResult` as a member. But since `GetTaskResult` is an intersection
 * type (not an interface), no PHP class is generated for it, causing:
 *
 * 1. Documentation listing non-existent types
 * 2. No way to type-hint results from `tasks/get` or `tasks/cancel` requests
 * 3. Union type implementations being incomplete
 *
 * ## Solution
 *
 * This generator creates concrete classes that:
 * 1. Inherit from one parent (the "base" type, typically Result)
 * 2. Include all properties from the other parent(s) as class properties
 * 3. Implement all union interfaces that reference the intersection type
 *
 * For example, `GetTaskResult = Result & Task` becomes:
 *
 * ```php
 * class GetTaskResult extends Result implements ClientResultInterface, ServerResultInterface
 * {
 *     // Includes all Task properties: taskId, status, createdAt, etc.
 *     // Inherits _meta from Result
 * }
 * ```
 *
 * ## PHP Limitation: Single Inheritance
 *
 * PHP only supports single inheritance, so we can't do `extends Result, Task`.
 * Instead, we:
 * - Extend the most "generic" type (Result in this case)
 * - Copy all properties from the other types into the generated class
 * - Merge the fromArray/toArray logic to handle all properties
 */

import type { TsTypeAlias, TsInterface, TsProperty, GeneratorConfig, DomainClassification, UnionMembershipInfo } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { TypeMapper } from './type-mapper.js';
import { formatPhpDocDescription } from './index.js';

// Note: TypeMapper.mapType is a static method, so we call it directly as TypeMapper.mapType()

/**
 * Information about an intersection type that needs a wrapper class.
 */
export interface IntersectionTypeWrapperInfo {
  /** The intersection type name (e.g., 'GetTaskResult') */
  readonly typeName: string;
  /** The types being intersected (e.g., ['Result', 'Task']) */
  readonly intersectedTypes: readonly string[];
  /** The primary/base type to extend (e.g., 'Result') */
  readonly baseType: string;
  /** Additional types whose properties are merged in */
  readonly mergedTypes: readonly string[];
  /** Union interfaces this type should implement */
  readonly unionInterfaces: readonly UnionMembershipInfo[];
  /** Original type alias for description/tags */
  readonly typeAlias: TsTypeAlias;
  /** All properties combined from intersected types */
  readonly allProperties: readonly TsProperty[];
  /** Properties from base type (inherited) */
  readonly baseProperties: readonly TsProperty[];
  /** Properties from merged types (declared in class) */
  readonly ownProperties: readonly TsProperty[];
}

/**
 * Generates PHP wrapper classes for intersection types referenced in unions.
 */
export class IntersectionTypeWrapperGenerator {
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
   * Finds all intersection types that need wrapper classes.
   *
   * An intersection type needs a wrapper when:
   * 1. It contains `&` (intersection operator)
   * 2. All intersected types are interfaces with generated DTOs
   * 3. The intersection type is referenced in at least one union type
   *
   * @param _unionMembershipMap - Map of type names to their union memberships (unused, kept for API consistency)
   * @returns Array of intersection types that need wrapper generation
   */
  findIntersectionsNeedingWrappers(
    _unionMembershipMap: Map<string, UnionMembershipInfo[]>
  ): IntersectionTypeWrapperInfo[] {
    const result: IntersectionTypeWrapperInfo[] = [];

    for (const alias of this.typeAliases) {
      // Only look at intersection types (contain &)
      if (!alias.type.includes('&')) {
        continue;
      }

      // Skip if it also contains | (mixed union/intersection - too complex)
      if (alias.type.includes('|')) {
        continue;
      }

      // Extract the intersected type names
      const intersectedTypes = alias.type
        .split('&')
        .map((t) => t.trim())
        .filter((t) => t.length > 0);

      if (intersectedTypes.length < 2) {
        continue;
      }

      // Verify all intersected types are interfaces
      const allInterfacesExist = intersectedTypes.every((typeName) =>
        this.interfaces.some((i) => i.name === typeName)
      );
      if (!allInterfacesExist) {
        continue;
      }

      // Check if this intersection is referenced in any union
      const unionMemberships = this.findUnionMembershipsForType(alias.name);
      if (unionMemberships.length === 0) {
        continue;
      }

      // Determine base type and merged types
      // Heuristic: Result is always the base type if present (most generic)
      // Otherwise, use the first type
      const baseType = intersectedTypes.includes('Result')
        ? 'Result'
        : intersectedTypes[0]!;
      const mergedTypes = intersectedTypes.filter((t) => t !== baseType);

      // Collect all properties from all intersected types
      const allProperties = this.collectProperties(intersectedTypes);
      const baseProperties = this.collectProperties([baseType]);
      const ownProperties = this.collectProperties(mergedTypes);

      result.push({
        typeName: alias.name,
        intersectedTypes,
        baseType,
        mergedTypes,
        unionInterfaces: unionMemberships,
        typeAlias: alias,
        allProperties,
        baseProperties,
        ownProperties,
      });
    }

    return result;
  }

  /**
   * Collects properties from a list of interface names.
   * Resolves type aliases (e.g., TaskStatus -> string literal union) to their underlying types.
   */
  private collectProperties(typeNames: readonly string[]): TsProperty[] {
    const properties: TsProperty[] = [];
    const seenNames = new Set<string>();

    for (const typeName of typeNames) {
      const iface = this.interfaces.find((i) => i.name === typeName);
      if (iface) {
        for (const prop of iface.properties) {
          // Avoid duplicate properties
          if (!seenNames.has(prop.name)) {
            seenNames.add(prop.name);
            // Resolve type alias if the property type references one
            // This handles cases like `status: TaskStatus` where TaskStatus is a string literal union
            const resolvedType = this.resolveTypeAlias(prop.type);
            if (resolvedType !== prop.type) {
              properties.push({
                ...prop,
                type: resolvedType,
              });
            } else {
              properties.push(prop);
            }
          }
        }
      }
    }

    return properties;
  }

  /**
   * Resolves a type alias to its underlying type if it's a simple alias (not an intersection/union).
   * This is needed because TypeMapper doesn't know about type aliases.
   *
   * For example: TaskStatus -> '"working" | "input_required" | "completed" | "failed" | "cancelled"'
   *
   * @param typeName - The type name to resolve
   * @returns The resolved type (underlying type if alias, otherwise the original)
   */
  private resolveTypeAlias(typeName: string): string {
    const trimmed = typeName.trim();

    // Check if this is a known type alias
    const typeAlias = this.typeAliases.find((a) => a.name === trimmed);
    if (!typeAlias) {
      return typeName;
    }

    // Only resolve string literal unions (enums in TypeScript)
    // These are type aliases like: type TaskStatus = "working" | "cancelled" | ...
    // Don't resolve intersection types or complex unions (those are handled elsewhere)
    if (typeAlias.type.includes('&')) {
      return typeName; // Don't resolve intersection types
    }

    // Clean the type by removing inline comments (// ...) from each line
    // This handles the MCP schema format: "working" // description | "cancelled" // description
    const cleanedType = typeAlias.type
      .split('\n')
      .map((line) => {
        const commentIndex = line.indexOf('//');
        return commentIndex >= 0 ? line.slice(0, commentIndex).trim() : line.trim();
      })
      .join(' ')
      .trim();

    // Check if all members of the union are string literals
    const members = cleanedType.split('|').map((m) => m.trim()).filter((m) => m.length > 0);
    const allStringLiterals = members.every(
      (m) => (m.startsWith('"') && m.endsWith('"')) || (m.startsWith("'") && m.endsWith("'"))
    );

    if (allStringLiterals && members.length > 0) {
      // Return a cleaned-up string literal union type
      return members.join(' | ');
    }

    return typeName;
  }

  /**
   * Finds all unions that reference a type by name.
   */
  private findUnionMembershipsForType(typeName: string): UnionMembershipInfo[] {
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

      // Check if our type is a member
      if (members.includes(typeName)) {
        const classification = this.classifier.classify(alias.name, alias.tags);
        memberships.push({
          unionName: alias.name,
          namespace: `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Union`,
          // Intersection types typically don't have discriminator values
          // since they're result types without a `method` field
          discriminatorField: undefined,
          discriminatorValue: undefined,
        });
      }
    }

    return memberships;
  }

  /**
   * Generates PHP wrapper class code for an intersection type.
   */
  generate(info: IntersectionTypeWrapperInfo): string {
    const classification = this.classifier.classify(info.typeName, info.typeAlias.tags);
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
    info: IntersectionTypeWrapperInfo,
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
      // Default description explaining this is an intersection type
      lines.push(` * The result of a ${this.getMethodNameFromTypeName(info.typeName)} operation.`);
      lines.push(' *');
    }

    // Add explanation of why this wrapper exists
    lines.push(` * This class represents an intersection type combining properties from:`);
    for (const typeName of info.intersectedTypes) {
      lines.push(` * - {@see ${typeName}}`);
    }
    lines.push(' *');
    lines.push(` * In TypeScript, this is defined as: \`type ${info.typeName} = ${info.intersectedTypes.join(' & ')}\``);
    lines.push(' *');
    lines.push(` * PHP requires actual classes for union interface implementation, so this class`);
    lines.push(` * extends ${info.baseType} and merges properties from: ${info.mergedTypes.join(', ')}`);
    lines.push(' *');
    lines.push(` * Implements union interfaces:`);
    for (const union of info.unionInterfaces) {
      lines.push(` * - {@see ${union.unionName}Interface}`);
    }
    lines.push(' *');
    lines.push(` * @since ${this.config.schema.version}`);
    lines.push(' *');
    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration
    const implementsList = info.unionInterfaces.map((u) => `${u.unionName}Interface`).join(', ');
    lines.push(`class ${info.typeName} extends ${info.baseType} implements ${implementsList}`);
    lines.push('{');

    // Properties from merged types
    if (info.ownProperties.length > 0) {
      for (const prop of info.ownProperties) {
        const phpType = TypeMapper.mapType(prop.type, prop.name);
        const nullable = prop.isOptional || phpType.nullable;

        // Property docblock
        lines.push(`${indent}/**`);
        if (prop.description) {
          lines.push(...formatPhpDocDescription(prop.description, indent));
          lines.push(`${indent} *`);
        }
        lines.push(`${indent} * @since ${this.config.schema.version}`);
        lines.push(`${indent} *`);
        lines.push(`${indent} * @var ${phpType.phpDocType || phpType.type}${nullable ? '|null' : ''}`);
        lines.push(`${indent} */`);

        // Property declaration
        const typeHint = phpType.isUntyped ? '' : (nullable ? `?${phpType.type} ` : `${phpType.type} `);
        lines.push(`${indent}protected ${typeHint}$${prop.name}${nullable ? ' = null' : ''};`);
        lines.push('');
      }
    }

    // Constructor
    lines.push(`${indent}/**`);
    // Document all properties in order: base required, merged required, base optional, merged optional
    const allRequired = info.allProperties.filter((p) => !p.isOptional);
    const allOptional = info.allProperties.filter((p) => p.isOptional);

    for (const prop of allRequired) {
      const phpType = TypeMapper.mapType(prop.type, prop.name);
      lines.push(`${indent} * @param ${phpType.phpDocType || phpType.type} $${prop.name} @since ${this.config.schema.version}`);
    }
    for (const prop of allOptional) {
      const phpType = TypeMapper.mapType(prop.type, prop.name);
      lines.push(`${indent} * @param ${phpType.phpDocType || phpType.type}|null $${prop.name} @since ${this.config.schema.version}`);
    }
    lines.push(`${indent} */`);

    // Constructor signature
    const constructorParams: string[] = [];
    for (const prop of allRequired) {
      const phpType = TypeMapper.mapType(prop.type, prop.name);
      const typeHint = phpType.isUntyped ? '' : `${phpType.type} `;
      constructorParams.push(`${typeHint}$${prop.name}`);
    }
    for (const prop of allOptional) {
      const phpType = TypeMapper.mapType(prop.type, prop.name);
      const typeHint = phpType.isUntyped ? '' : `?${phpType.type} `;
      constructorParams.push(`${typeHint}$${prop.name} = null`);
    }

    lines.push(`${indent}public function __construct(`);
    for (let i = 0; i < constructorParams.length; i++) {
      const isLast = i === constructorParams.length - 1;
      lines.push(`${indent}${indent}${constructorParams[i]}${isLast ? '' : ','}`);
    }
    lines.push(`${indent}) {`);

    // Call parent constructor with base properties
    const baseRequired = info.baseProperties.filter((p) => !p.isOptional);
    const baseOptional = info.baseProperties.filter((p) => p.isOptional);
    const parentArgs = [...baseRequired, ...baseOptional].map((p) => `$${p.name}`).join(', ');
    if (parentArgs) {
      lines.push(`${indent}${indent}parent::__construct(${parentArgs});`);
    } else {
      lines.push(`${indent}${indent}parent::__construct();`);
    }

    // Assign own properties
    for (const prop of info.ownProperties) {
      lines.push(`${indent}${indent}$this->${prop.name} = $${prop.name};`);
    }

    lines.push(`${indent}}`);
    lines.push('');

    // fromArray static factory
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Creates an instance from an array.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param array<string, mixed> $data`);
    lines.push(`${indent} * @return self`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function fromArray(array $data): self`);
    lines.push(`${indent}{`);

    // Required fields assertion
    const requiredFields = allRequired.map((p) => `'${p.name}'`).join(', ');
    if (requiredFields) {
      lines.push(`${indent}${indent}self::assertRequired($data, [${requiredFields}]);`);
      lines.push('');
    }

    // For string literal union types, we need to extract variables with @var annotations
    // This is required for PHPStan to understand the type narrowing
    const varsWithAnnotations: string[] = [];
    const fromArrayArgs: string[] = [];
    const allProps = [...allRequired, ...allOptional];
    const allPropsOptionalFlag = [...allRequired.map(() => false), ...allOptional.map(() => true)];

    for (let i = 0; i < allProps.length; i++) {
      const prop = allProps[i]!;
      const isOptional = allPropsOptionalFlag[i] ?? false;
      const phpType = TypeMapper.mapType(prop.type, prop.name);

      // Check if this is a string literal union type (PHPDoc type contains quotes)
      const isStringLiteralUnion = phpType.phpDocType && phpType.phpDocType.includes("'");

      if (isStringLiteralUnion && !isOptional) {
        // Need a variable with @var annotation
        const varName = `$${prop.name}`;
        varsWithAnnotations.push(
          `${indent}${indent}/** @var ${phpType.phpDocType} ${varName} */`,
          `${indent}${indent}${varName} = self::asString($data['${prop.name}']);`,
          ''
        );
        fromArrayArgs.push(varName);
      } else {
        // Normal inline argument
        fromArrayArgs.push(this.renderFromArrayArg(prop, isOptional));
      }
    }

    // Add variable extractions if any
    if (varsWithAnnotations.length > 0) {
      lines.push(...varsWithAnnotations);
    }

    // Create instance
    lines.push(`${indent}${indent}return new self(`);
    for (let i = 0; i < fromArrayArgs.length; i++) {
      const isLast = i === fromArrayArgs.length - 1;
      lines.push(`${indent}${indent}${indent}${fromArrayArgs[i]}${isLast ? '' : ','}`);
    }
    lines.push(`${indent}${indent});`);
    lines.push(`${indent}}`);
    lines.push('');

    // toArray method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Converts the instance to an array.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return array<string, mixed>`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public function toArray(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}$result = parent::toArray();`);
    lines.push('');

    // Add own properties to result
    for (const prop of info.ownProperties) {
      if (prop.isOptional) {
        lines.push(`${indent}${indent}if ($this->${prop.name} !== null) {`);
        lines.push(`${indent}${indent}${indent}$result['${prop.name}'] = $this->${prop.name};`);
        lines.push(`${indent}${indent}}`);
      } else {
        lines.push(`${indent}${indent}$result['${prop.name}'] = $this->${prop.name};`);
      }
    }
    lines.push('');
    lines.push(`${indent}${indent}return $result;`);
    lines.push(`${indent}}`);

    // Getters for own properties
    for (const prop of info.ownProperties) {
      lines.push('');
      const phpType = TypeMapper.mapType(prop.type, prop.name);
      const nullable = prop.isOptional || phpType.nullable;
      const returnType = phpType.isUntyped ? '' : `: ${nullable ? '?' : ''}${phpType.type}`;

      lines.push(`${indent}/**`);
      lines.push(`${indent} * @return ${phpType.phpDocType || phpType.type}${nullable ? '|null' : ''}`);
      lines.push(`${indent} */`);
      lines.push(`${indent}public function get${this.capitalize(prop.name)}()${returnType}`);
      lines.push(`${indent}{`);
      lines.push(`${indent}${indent}return $this->${prop.name};`);
      lines.push(`${indent}}`);
    }

    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Renders an argument for the fromArray factory method.
   */
  private renderFromArrayArg(prop: TsProperty, isOptional = false): string {
    const phpType = TypeMapper.mapType(prop.type, prop.name);
    const key = isOptional ? `$data['${prop.name}'] ?? null` : `$data['${prop.name}']`;

    // Map the conversion helper
    if (phpType.type === 'string') {
      return isOptional ? `self::asStringOrNull(${key})` : `self::asString(${key})`;
    }
    if (phpType.type === 'int') {
      return isOptional ? `self::asIntOrNull(${key})` : `self::asInt(${key})`;
    }
    if (phpType.type === 'bool') {
      return isOptional ? `self::asBoolOrNull(${key})` : `self::asBool(${key})`;
    }
    if (phpType.type === 'float') {
      return isOptional ? `self::asFloatOrNull(${key})` : `self::asFloat(${key})`;
    }
    if (phpType.type === 'array') {
      return isOptional ? `self::asArrayOrNull(${key})` : `self::asArray(${key})`;
    }

    // For custom types, we'd need more sophisticated handling
    // For now, just pass through
    return key;
  }

  /**
   * Capitalizes the first letter of a string.
   */
  private capitalize(str: string): string {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  /**
   * Gets a human-readable method name from a type name.
   * E.g., 'GetTaskResult' -> 'get task'
   */
  private getMethodNameFromTypeName(typeName: string): string {
    // Remove 'Result' suffix
    const withoutResult = typeName.replace(/Result$/, '');
    // Split on capital letters
    const words = withoutResult.split(/(?=[A-Z])/);
    return words.join(' ').toLowerCase();
  }

  /**
   * Gets the output path for a wrapper class.
   */
  getOutputPath(info: IntersectionTypeWrapperInfo): string {
    const classification = this.classifier.classify(info.typeName, info.typeAlias.tags);
    return `${classification.domain}/${classification.subdomain}/${info.typeName}.php`;
  }
}

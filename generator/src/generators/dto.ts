/**
 * MCP PHP Schema Generator - DTO Generator
 *
 * Generates PHP 7.4 Data Transfer Object classes from TypeScript interfaces.
 */

import type { TsInterface, TsTypeAlias, PhpProperty, PhpClassMeta, GeneratorConfig, DomainClassification, UnionMembershipMap, PhpType, VersionTracker } from '../types/index.js';
import { TypeMapper, ConstantsMap } from './type-mapper.js';
import { DomainClassifier } from './domain-classifier.js';
import { TypeResolver } from './type-resolver.js';
import {
  buildInheritanceGraph,
  buildInterfaceMap,
  classifyProperties,
  getDirectParent,
  isRoot,
  type InheritanceGraph,
  type NarrowedProperty,
} from './inheritance-graph.js';
import { formatPhpDocDescription } from './index.js';

/**
 * DTO generation options.
 */
export interface DtoGeneratorOptions {
  readonly generateGetters?: boolean;
  readonly generateWithMethods?: boolean;
}

/**
 * Generates PHP DTO classes from TypeScript interfaces.
 */
export class DtoGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;
  private readonly options: DtoGeneratorOptions;
  private readonly typeResolver: TypeResolver;
  private readonly unionMembershipMap: UnionMembershipMap;
  private readonly inheritanceGraph: InheritanceGraph;
  private readonly interfaceMap: ReadonlyMap<string, TsInterface>;
  private readonly versionTracker: VersionTracker | undefined;

  constructor(
    interfaces: readonly TsInterface[],
    config: GeneratorConfig,
    options: DtoGeneratorOptions = {},
    typeAliases: readonly TsTypeAlias[] = [],
    classifier?: DomainClassifier,
    unionMembershipMap?: UnionMembershipMap,
    versionTracker?: VersionTracker,
    constants?: ConstantsMap
  ) {
    this.config = config;
    this.classifier = classifier ?? new DomainClassifier();
    this.typeResolver = new TypeResolver(typeAliases, interfaces, config.output.namespace, this.classifier, constants);
    this.unionMembershipMap = unionMembershipMap ?? new Map();
    this.inheritanceGraph = buildInheritanceGraph(interfaces);
    this.interfaceMap = buildInterfaceMap(interfaces);
    this.versionTracker = versionTracker;
    this.options = {
      generateGetters: true,
      generateWithMethods: false,
      ...options,
    };
  }

  /**
   * Generates PHP code for an interface.
   */
  generate(iface: TsInterface): string {
    const classification = this.classifier.classify(iface.name, iface.tags, iface.syntheticParent);
    const currentNamespace = this.getNamespace(classification);

    // Classify properties into own, inherited, and narrowed
    const propClassification = classifyProperties(iface, this.inheritanceGraph, this.interfaceMap);

    // Determine parent class
    const parentTypeName = getDirectParent(iface.name, this.inheritanceGraph);
    const isRootType = isRoot(iface.name, this.inheritanceGraph);
    let extendsClass = 'AbstractDataTransferObject';
    let parentNamespace: string | undefined;
    let parentIface: TsInterface | undefined;

    if (parentTypeName && !isRootType) {
      parentIface = this.interfaceMap.get(parentTypeName);
      if (parentIface) {
        extendsClass = parentTypeName;
        const parentClassification = this.classifier.classify(parentTypeName, parentIface.tags, parentIface.syntheticParent);
        parentNamespace = this.getNamespace(parentClassification);
      }
    }

    // Track imports for cross-domain type references
    const imports = new Map<string, string>(); // FQN -> simple name

    // Add parent class import if in different namespace
    if (parentNamespace && parentNamespace !== currentNamespace && extendsClass !== 'AbstractDataTransferObject') {
      imports.set(`${parentNamespace}\\${extendsClass}`, extendsClass);
    }

    // Check if this DTO is a member of any unions
    const unionMemberships = this.unionMembershipMap.get(iface.name) ?? [];
    const implementsList: string[] = [];

    // Add union interface imports and implements
    for (const membership of unionMemberships) {
      const interfaceName = `${membership.unionName}Interface`;
      const fqn = `${membership.namespace}\\${interfaceName}`;
      // Don't import from same namespace
      if (membership.namespace !== currentNamespace) {
        imports.set(fqn, interfaceName);
      }
      implementsList.push(interfaceName);
    }

    // Classify properties: own (new in this type), inherited (from parents), narrowed (same name, specific type)
    const allProperties = propClassification.allProperties;
    const ownProperties = propClassification.ownProperties;
    const narrowedProperties = propClassification.narrowedProperties;

    // Resolve all property types and collect imports (for constructor/fromArray/toArray)
    const allPhpProperties = allProperties.map((p) => this.resolveProperty(p, currentNamespace, imports, iface.name));

    // Resolve own property types (for property declarations)
    const ownPhpProperties = ownProperties.map((p) => this.resolveProperty(p, currentNamespace, imports, iface.name));

    // Resolve narrowed property types (for property declarations and special handling)
    const narrowedPhpProperties = narrowedProperties.map((n) => ({
      narrowed: n,
      phpProperty: this.resolveProperty(n.property, currentNamespace, imports, iface.name),
    }));

    const indent = this.getIndent();

    const classMeta: PhpClassMeta = {
      className: iface.name,
      namespace: currentNamespace,
      domain: classification.domain,
      subdomain: classification.subdomain,
      description: iface.description,
      properties: allPhpProperties, // All for constructor/fromArray/toArray
      extends: extendsClass,
      implements: implementsList.length > 0 ? implementsList : undefined,
    };

    // Pass ownPhpProperties and narrowedPhpProperties separately for property declarations,
    // and parent interface for constructor
    return this.renderClass(classMeta, indent, imports, ownPhpProperties, narrowedPhpProperties, isRootType, parentIface, unionMemberships, extendsClass !== 'AbstractDataTransferObject' ? extendsClass : undefined, iface.name);
  }

  /**
   * Resolves a TypeScript property to a PHP property.
   */
  private resolveProperty(
    p: { name: string; type: string; isOptional: boolean; description?: string },
    currentNamespace: string,
    imports: Map<string, string>,
    interfaceName?: string
  ): PhpProperty {
    const resolved = this.typeResolver.resolve(p.type, p.name);
    let phpType = resolved.phpType;

    // Track cross-domain imports
    if (resolved.needsImport && resolved.namespace && resolved.className) {
      const fqn = `${resolved.namespace}\\${resolved.className}`;
      // Don't import from same namespace
      if (resolved.namespace !== currentNamespace) {
        imports.set(fqn, resolved.className);
      }

      // For union interfaces, also import the corresponding Factory class for fromArray() hydration
      // Union interfaces are in Union/ subfolder, Factories are in Factory/ subfolder
      if (resolved.className.endsWith('Interface')) {
        const factoryClassName = resolved.className.replace(/Interface$/, 'Factory');
        const factoryNamespace = resolved.namespace.replace(/\\Union$/, '\\Factory');
        const factoryFqn = `${factoryNamespace}\\${factoryClassName}`;
        if (factoryNamespace !== currentNamespace) {
          imports.set(factoryFqn, factoryClassName);
        }
      }
    }

    // For array types with union interface items, also import the Factory
    if (phpType.isArray && phpType.arrayItemType && phpType.arrayItemType.endsWith('Interface')) {
      const factoryClassName = phpType.arrayItemType.replace(/Interface$/, 'Factory');
      // Need to get the namespace - it should be in resolved.namespace but for array item type
      if (resolved.namespace) {
        const factoryNamespace = resolved.namespace.replace(/\\Union$/, '\\Factory');
        const factoryFqn = `${factoryNamespace}\\${factoryClassName}`;
        if (factoryNamespace !== currentNamespace) {
          imports.set(factoryFqn, factoryClassName);
        }
      }
    }

    // For array types with union interfaces in phpDocType (arrayItemType may be empty)
    // e.g., phpDocType: array<\WP\McpSchema\...\Union\SomeInterface> or
    //       array<\WP\McpSchema\...\Union\SomeInterface|\WP\McpSchema\...\Union\SomeInterface>
    if (phpType.isArray && phpType.phpDocType) {
      // Extract the first interface from phpDocType - handles both single and union types
      // Match: array<\Namespace\Union\Interface> or array<\Namespace\Union\Interface|\Namespace\...>
      const phpDocMatch = phpType.phpDocType.match(/^array<\\([^|>]+\\Union\\(\w+Interface))/);
      if (phpDocMatch?.[1] && phpDocMatch[2]) {
        const interfaceFqn = phpDocMatch[1]; // Full namespace path to interface
        const interfaceName = phpDocMatch[2]; // Just the interface name
        const factoryClassName = interfaceName.replace(/Interface$/, 'Factory');
        const factoryNamespace = interfaceFqn.replace(/\\Union\\\w+Interface$/, '\\Factory');
        const factoryFqn = `${factoryNamespace}\\${factoryClassName}`;
        if (!currentNamespace.endsWith('\\Factory') && !imports.has(factoryFqn)) {
          imports.set(factoryFqn, factoryClassName);
        }
      }
    }

    // Set FQN in phpDocType for array shape generation (all class references, including same namespace)
    if (resolved.namespace && resolved.className) {
      const fqnWithPrefix = `\\${resolved.namespace}\\${resolved.className}`;
      if (phpType.isArray) {
        phpType = { ...phpType, phpDocType: `array<${fqnWithPrefix}>` };
      } else {
        phpType = { ...phpType, phpDocType: fqnWithPrefix };
      }
    }

    // Check for const values (discriminator fields)
    const constValue = this.extractConstValue(p.type);

    // Extract maxItems constraint from JSDoc description (e.g., "Must not exceed 100 items")
    const maxItems = this.extractMaxItems(p.description);

    return {
      name: p.name,
      type: phpType,
      description: p.description,
      isRequired: !p.isOptional,
      constValue,
      maxItems,
      serializeEmptyAsObject: this.shouldSerializeEmptyAsObject(interfaceName, p.name),
    } as PhpProperty;
  }

  /**
   * Tool input/output JSON Schemas must keep an object-typed properties key on
   * the wire, even when no parameters are declared.
   */
  private shouldSerializeEmptyAsObject(
    interfaceName: string | undefined,
    propertyName: string
  ): boolean {
    return (
      propertyName === 'properties' &&
      (interfaceName === 'ToolInputSchema' || interfaceName === 'ToolOutputSchema')
    );
  }

  /**
   * Extracts a const value from a literal type.
   * Only extracts single literals, not union types like "a" | "b".
   */
  private extractConstValue(type: string): string | undefined {
    const trimmed = type.trim();

    // Check if it's a single string literal (not a union)
    // Must start and end with same quote type, and not contain unescaped quotes in middle
    if (trimmed.startsWith('"') && trimmed.endsWith('"')) {
      const middle = trimmed.slice(1, -1);
      // Only match if no unescaped double quotes in middle (excludes union types)
      if (!middle.includes('"')) {
        return middle;
      }
    }
    if (trimmed.startsWith("'") && trimmed.endsWith("'")) {
      const middle = trimmed.slice(1, -1);
      if (!middle.includes("'")) {
        return middle;
      }
    }

    // Match number literals
    if (/^-?\d+(\.\d+)?$/.test(trimmed)) {
      return trimmed;
    }

    return undefined;
  }

  /**
   * Extracts maxItems constraint from JSDoc description.
   * Looks for patterns like "Must not exceed N items" in the description.
   *
   * @param description - The property's JSDoc description
   * @returns The max items number if found, undefined otherwise
   */
  private extractMaxItems(description?: string): number | undefined {
    if (!description) {
      return undefined;
    }

    // Match patterns like "Must not exceed 100 items" (case-insensitive)
    const match = description.match(/must not exceed (\d+) items/i);
    if (match?.[1]) {
      return parseInt(match[1], 10);
    }

    return undefined;
  }

  /**
   * Gets the PHP namespace for a classification.
   * DTOs are placed in the DTO subfolder namespace.
   */
  private getNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\DTO`;
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
   * Renders the complete PHP class.
   *
   * @param meta - Class metadata
   * @param indent - Indentation string
   * @param imports - Import map
   * @param ownProperties - Properties to declare in this class (own only, not inherited)
   * @param narrowedProperties - Properties with narrower types than parent
   * @param isRootType - Whether this is a root type (extends AbstractDataTransferObject)
   * @param parentIface - Parent interface (for parent constructor order)
   * @param unionMemberships - Union interfaces this class implements (for discriminator constants)
   * @param parentClassName - Name of the parent class for @see references
   * @param interfaceName - Original interface name for version tracking
   */
  private renderClass(
    meta: PhpClassMeta,
    indent: string,
    imports: Map<string, string> = new Map(),
    ownProperties?: readonly PhpProperty[],
    narrowedProperties: readonly { narrowed: NarrowedProperty; phpProperty: PhpProperty }[] = [],
    isRootType: boolean = true,
    parentIface?: TsInterface,
    unionMemberships: readonly import('../types/index.js').UnionMembershipInfo[] = [],
    parentClassName?: string,
    interfaceName?: string
  ): string {
    const lines: string[] = [];

    // Extract PHP properties from narrowed, with renamed property names to avoid PHP type conflict
    // Parent declares `protected ?array $params;` - child can't redeclare with different type
    // Solution: Use `$typedParams` in child, but keep JSON key as `params`
    //
    // EXCEPTION: Narrowed properties with const values (like method: 'initialize') don't need
    // separate typed properties - they're handled via class constants and parent constructor
    const narrowedPhpProperties = narrowedProperties
      .filter((n) => n.phpProperty.constValue === undefined) // Exclude const narrowed properties
      .map((n) => ({
        ...n.phpProperty,
        originalName: n.phpProperty.name, // Keep original for JSON serialization
        name: `typed${this.toPascalCase(n.phpProperty.name)}`, // Renamed PHP property
      }));

    // Properties to declare in this class: own + narrowed (both need declarations)
    const propertiesToDeclare = [...(ownProperties ?? meta.properties), ...narrowedPhpProperties];

    // Determine if this DTO needs type assertion helpers (any non-const properties)
    // The trait provides asString(), asInt(), assertRequired(), etc. for PHPStan max level compliance
    const nonConstProps = meta.properties.filter((p) => p.constValue === undefined);
    const needsTypeAssertions = nonConstProps.length > 0;

    // Detect "structural alias" - a subclass with no own properties (exists for semantic distinction)
    // These are TypeScript type aliases that became separate PHP classes for type safety
    const isStructuralAlias = !isRootType &&
      parentClassName !== undefined &&
      (ownProperties?.length ?? 0) === 0 &&
      narrowedPhpProperties.length === 0;

    // PHP opening tag
    lines.push('<?php');
    lines.push('');
    lines.push('declare(strict_types=1);');
    lines.push('');

    // Namespace
    lines.push(`namespace ${meta.namespace};`);
    lines.push('');

    // Use statements
    lines.push(...this.renderUseStatements(meta, imports, isRootType, needsTypeAssertions));
    lines.push('');

    // Class docblock
    lines.push(...this.renderClassDocblock(meta, isStructuralAlias, parentClassName, interfaceName));

    // Class declaration
    const classDecl = meta.isFinal ? 'final class' : 'class';
    const extendsClause = meta.extends ? ` extends ${meta.extends}` : '';
    const implementsClause = meta.implements?.length ? ` implements ${meta.implements.join(', ')}` : '';
    lines.push(`${classDecl} ${meta.className}${extendsClause}${implementsClause}`);
    lines.push('{');

    // Use trait for type assertions and required field validation (must be first in class body)
    // Trait provides asString(), asInt(), etc. for PHPStan max level compliance
    if (needsTypeAssertions) {
      lines.push(`${indent}use ValidatesRequiredFields;`);
      lines.push('');
    }

    // Constants
    if (meta.constants?.length) {
      lines.push(...this.renderConstants(meta.constants, indent));
      lines.push('');
    }

    // Const property values (for ALL properties including inherited that are narrowed to const)
    // This is needed because child classes may narrow inherited properties to const values
    // and we need the constant for parent::__construct() calls
    const constProperties = meta.properties.filter((p) => p.constValue !== undefined);
    if (constProperties.length > 0) {
      lines.push(...this.renderConstProperties(constProperties, indent));
      lines.push('');
    }

    // Discriminator constants for union members
    // These provide a standardized way to introspect discriminator field and value
    const discriminatorConstants = this.renderDiscriminatorConstants(unionMemberships, indent);
    if (discriminatorConstants.length > 0) {
      lines.push(...discriminatorConstants);
      lines.push('');
    }

    // MaxItems constants for array properties with length limits
    // Generated from JSDoc patterns like "Must not exceed N items"
    const maxItemsConstants = this.renderMaxItemsConstants(meta.properties, indent);
    if (maxItemsConstants.length > 0) {
      lines.push(...maxItemsConstants);
      lines.push('');
    }

    // Properties (only own properties, not inherited)
    if (propertiesToDeclare.length > 0) {
      lines.push(...this.renderProperties(propertiesToDeclare, indent, interfaceName));
      lines.push('');
    }

    // Constructor with proper inheritance delegation
    // Pass narrowed property ORIGINAL names so we pass null to parent for them
    // But we'll use renamed properties (typedParams) for assignment in child
    const narrowedOriginalNames = new Set(narrowedPhpProperties.map((p) => (p as any).originalName ?? p.name));
    lines.push(...this.renderConstructor(meta.properties, indent, ownProperties ?? [], isRootType, parentIface, narrowedOriginalNames, narrowedPhpProperties, interfaceName));
    lines.push('');

    // fromArray method (uses all properties)
    lines.push(...this.renderFromArray(meta.properties, indent));
    lines.push('');

    // toArray method - for child classes, call parent::toArray() and merge own + narrowed properties
    // Both own and narrowed need to be serialized by the child class
    const propsToSerialize = [...(ownProperties ?? []), ...narrowedPhpProperties];
    lines.push(...this.renderToArray(meta.properties, indent, propsToSerialize, isRootType));

    // Getters (only for own properties)
    if (this.options.generateGetters && propertiesToDeclare.length > 0) {
      lines.push('');
      lines.push(...this.renderGetters(propertiesToDeclare, indent));
    }

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Renders use statements.
   *
   * @param _meta - Class metadata
   * @param imports - Import map (includes parent class if cross-namespace)
   * @param isRootType - Whether this is a root type (needs AbstractDataTransferObject import)
   * @param needsTypeAssertions - Whether this DTO needs type assertion helpers (ValidatesRequiredFields trait)
   */
  private renderUseStatements(
    _meta: PhpClassMeta,
    imports: Map<string, string> = new Map(),
    isRootType: boolean = true,
    needsTypeAssertions: boolean = false
  ): string[] {
    const uses: string[] = [];

    // Base class - only for root types that extend AbstractDataTransferObject directly
    if (isRootType) {
      uses.push(`use ${this.config.output.namespace}\\Common\\AbstractDataTransferObject;`);
    }

    // ValidatesRequiredFields trait - provides type assertion helpers for PHPStan max level compliance
    if (needsTypeAssertions) {
      uses.push(`use ${this.config.output.namespace}\\Common\\Traits\\ValidatesRequiredFields;`);
    }

    // Cross-domain type imports (includes parent class if in different namespace)
    for (const [fqn] of imports) {
      uses.push(`use ${fqn};`);
    }

    // Sort and deduplicate
    return [...new Set(uses)].sort();
  }

  /**
   * Renders the class docblock.
   *
   * For structural aliases (empty subclasses that exist for semantic distinction),
   * adds explanatory comments explaining why the class exists.
   *
   * @param meta - Class metadata
   * @param isStructuralAlias - Whether this class is a semantic alias (no own properties)
   * @param parentClassName - Name of parent class for @see reference
   * @param interfaceName - Original interface name for version tracking
   */
  private renderClassDocblock(
    meta: PhpClassMeta,
    isStructuralAlias: boolean = false,
    parentClassName?: string,
    interfaceName?: string
  ): string[] {
    const lines: string[] = ['/**'];

    if (meta.description) {
      lines.push(...formatPhpDocDescription(meta.description));
      lines.push(' *');
    }

    // For structural aliases, add explanatory note
    if (isStructuralAlias && parentClassName) {
      lines.push(` * Note: This class is structurally identical to ${parentClassName}.`);
      lines.push(' * It exists as a separate type for semantic distinction per MCP specification.');
      lines.push(' *');
    }

    // Version tracking annotations
    const versionInfo = interfaceName ? this.versionTracker?.getDefinitionVersion(interfaceName) : undefined;
    if (versionInfo) {
      lines.push(` * @since ${versionInfo.introducedIn}`);
      if (versionInfo.lastModified && versionInfo.changeSummary) {
        lines.push(` * @last-updated ${versionInfo.lastModified} (${versionInfo.changeSummary})`);
      }
      lines.push(' *');
    }

    lines.push(` * @mcp-domain ${meta.domain}`);
    lines.push(` * @mcp-subdomain ${meta.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);

    // Add @see reference to parent for structural aliases
    if (isStructuralAlias && parentClassName) {
      lines.push(` * @see ${parentClassName}`);
    }

    lines.push(' */');

    return lines;
  }

  /**
   * Generates the PHPStan array shape entries for properties.
   * Returns an array of "key: type" or "key?: type" strings.
   *
   * For DTO properties (non-primitives), the type includes `array|ClassName`
   * since fromArray() accepts either raw arrays (from JSON) or pre-instantiated objects.
   */
  private generateArrayShape(properties: readonly PhpProperty[]): string[] {
    return properties.map((prop) => {
      const { jsonKey } = this.getPropertyNames(prop.name);
      let phpDocType = TypeMapper.getPhpDocType(prop.type);

      // For DTO types (non-primitives), fromArray() accepts either array or object
      // Add array<string, mixed>| prefix to indicate both are valid inputs
      // Using array<string, mixed> instead of plain array to satisfy PHPStan max level
      if (this.isHydratableType(prop.type)) {
        // For array of DTOs: array<ClassName> becomes array<array<string, mixed>|ClassName>
        // For single DTO: ClassName becomes array<string, mixed>|ClassName
        if (prop.type.isArray && prop.type.arrayItemType && !DtoGenerator.PRIMITIVE_TYPES.has(prop.type.arrayItemType)) {
          // Array of DTOs - the FQN is already in phpDocType like array<\Namespace\Class>
          // We need to modify it to array<array<string, mixed>|\Namespace\Class>
          phpDocType = phpDocType.replace(/array<([^>]+)>/, 'array<array<string, mixed>|$1>');
        } else if (!prop.type.isArray && !DtoGenerator.PRIMITIVE_TYPES.has(prop.type.type)) {
          // Single DTO - add array<string, mixed>| prefix
          phpDocType = `array<string, mixed>|${phpDocType}`;
        }
      }

      // For optional properties, also add |null to the type (not just the ? marker)
      // This indicates the value can be explicitly null, not just absent
      const isNullable = !prop.isRequired || prop.type.nullable;
      if (isNullable && !phpDocType.includes('null')) {
        phpDocType = `${phpDocType}|null`;
      }

      // Optional properties use the "key?: type" syntax
      const optionalMarker = !prop.isRequired ? '?' : '';

      // Keys with special characters like $ need to be quoted in PHPStan array shapes
      const formattedKey = this.needsQuotedKey(jsonKey) ? `'${jsonKey}'` : jsonKey;

      return `${formattedKey}${optionalMarker}: ${phpDocType}`;
    });
  }

  /**
   * Checks if a property type is a DTO that will be auto-hydrated in fromArray().
   */
  private isHydratableType(phpType: PhpType): boolean {
    // Array of non-primitive items
    if (phpType.isArray && phpType.arrayItemType) {
      return !DtoGenerator.PRIMITIVE_TYPES.has(phpType.arrayItemType);
    }
    // Single non-primitive type
    if (!phpType.isArray) {
      return !DtoGenerator.PRIMITIVE_TYPES.has(phpType.type);
    }
    return false;
  }

  /**
   * Checks if a key needs to be quoted in PHPStan array shapes.
   */
  private needsQuotedKey(key: string): boolean {
    if (key.startsWith('$')) {
      return true;
    }
    if (/[^a-zA-Z0-9_]/.test(key)) {
      return true;
    }
    if (/^[0-9]/.test(key)) {
      return true;
    }
    return false;
  }

  /**
   * Renders class constants.
   */
  private renderConstants(constants: readonly { name: string; value: string }[], indent: string): string[] {
    return constants.map((c) => `${indent}public const ${c.name} = ${this.formatPhpValue(c.value)};`);
  }

  /**
   * Renders const property values as class constants.
   */
  private renderConstProperties(properties: readonly PhpProperty[], indent: string): string[] {
    return properties
      .filter((p) => p.constValue !== undefined)
      .map((p) => {
        const constName = this.toConstantName(p.name);
        return `${indent}public const ${constName} = ${this.formatPhpValue(p.constValue!)};`;
      });
  }

  /**
   * Renders discriminator constants for union members.
   *
   * Generates DISCRIMINATOR_FIELD and DISCRIMINATOR_VALUE constants that provide
   * a standardized way to introspect how this class participates in unions.
   *
   * @param unionMemberships - Union interfaces this class implements
   * @param indent - Indentation string
   * @returns Array of constant declaration lines
   */
  private renderDiscriminatorConstants(
    unionMemberships: readonly import('../types/index.js').UnionMembershipInfo[],
    indent: string
  ): string[] {
    if (unionMemberships.length === 0) {
      return [];
    }

    // Find the first membership with discriminator info
    // All unions for a given type should use the same discriminator field/value
    const membershipWithDiscriminator = unionMemberships.find(
      (m) => m.discriminatorField !== undefined && m.discriminatorValue !== undefined
    );

    if (!membershipWithDiscriminator) {
      return [];
    }

    const lines: string[] = [];
    lines.push(`${indent}public const DISCRIMINATOR_FIELD = ${this.formatPhpValue(membershipWithDiscriminator.discriminatorField!)};`);
    lines.push(`${indent}public const DISCRIMINATOR_VALUE = ${this.formatPhpValue(membershipWithDiscriminator.discriminatorValue!)};`);
    return lines;
  }

  /**
   * Renders maxItems constants for array properties with length limits.
   *
   * Generates constants like MAX_VALUES = 100 for properties that have
   * maxItems constraints extracted from JSDoc comments.
   *
   * @param properties - Properties to check for maxItems
   * @param indent - Indentation string
   * @returns Array of constant declaration lines
   */
  private renderMaxItemsConstants(properties: readonly PhpProperty[], indent: string): string[] {
    const propsWithMaxItems = properties.filter((p) => p.maxItems !== undefined);

    if (propsWithMaxItems.length === 0) {
      return [];
    }

    const lines: string[] = [];
    for (const prop of propsWithMaxItems) {
      // Generate constant name: values -> MAX_VALUES, items -> MAX_ITEMS
      const constName = `MAX_${this.toConstantName(prop.name)}`;
      lines.push(`${indent}/** Maximum number of items allowed in ${prop.name} per MCP spec */`);
      lines.push(`${indent}public const ${constName} = ${prop.maxItems};`);
    }
    return lines;
  }

  /**
   * Renders maxItems validation code for fromArray().
   *
   * Generates validation that throws InvalidArgumentException when an array
   * property exceeds its maxItems limit.
   *
   * @param properties - Properties with maxItems constraints
   * @param indent - Indentation string
   * @returns Array of validation code lines
   */
  private renderMaxItemsValidation(properties: readonly PhpProperty[], indent: string): string[] {
    const lines: string[] = [];

    for (const prop of properties) {
      if (prop.maxItems === undefined) continue;

      const { jsonKey } = this.getPropertyNames(prop.name);
      const constName = `MAX_${this.toConstantName(prop.name)}`;

      // For required properties, we know the field exists (assertRequired already passed)
      // For optional properties, only validate if the field is present
      // Use is_array() check to satisfy PHPStan (data values are mixed)
      if (prop.isRequired) {
        lines.push(`${indent}${indent}if (is_array($data['${jsonKey}']) && count($data['${jsonKey}']) > self::${constName}) {`);
        lines.push(`${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(`);
        lines.push(`${indent}${indent}${indent}${indent}'%s::${prop.name} must not exceed %d items, got %d',`);
        lines.push(`${indent}${indent}${indent}${indent}static::class,`);
        lines.push(`${indent}${indent}${indent}${indent}self::${constName},`);
        lines.push(`${indent}${indent}${indent}${indent}count($data['${jsonKey}'])`);
        lines.push(`${indent}${indent}${indent}));`);
        lines.push(`${indent}${indent}}`);
      } else {
        lines.push(`${indent}${indent}if (isset($data['${jsonKey}']) && is_array($data['${jsonKey}']) && count($data['${jsonKey}']) > self::${constName}) {`);
        lines.push(`${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(`);
        lines.push(`${indent}${indent}${indent}${indent}'%s::${prop.name} must not exceed %d items, got %d',`);
        lines.push(`${indent}${indent}${indent}${indent}static::class,`);
        lines.push(`${indent}${indent}${indent}${indent}self::${constName},`);
        lines.push(`${indent}${indent}${indent}${indent}count($data['${jsonKey}'])`);
        lines.push(`${indent}${indent}${indent}));`);
        lines.push(`${indent}${indent}}`);
      }
    }

    return lines;
  }

  /**
   * Renders property declarations.
   *
   * @param properties - Properties to render
   * @param indent - Indentation string
   * @param interfaceName - Original interface name for version tracking
   */
  private renderProperties(properties: readonly PhpProperty[], indent: string, interfaceName?: string): string[] {
    const lines: string[] = [];

    for (const prop of properties) {
      const { phpName } = this.getPropertyNames(prop.name);

      // Determine if property should be nullable (either explicitly nullable type or optional field)
      const isNullable = prop.type.nullable || !prop.isRequired;
      const effectiveType = { ...prop.type, nullable: isNullable };

      // Get version info for this property
      const propVersionInfo = interfaceName ? this.versionTracker?.getPropertyVersion(interfaceName, prop.name) : undefined;

      // Property docblock - use effectiveType to include nullability for optional properties
      const phpDocType = TypeMapper.getPhpDocType(effectiveType);
      lines.push(`${indent}/**`);
      if (prop.description) {
        lines.push(...formatPhpDocDescription(prop.description, indent));
        lines.push(`${indent} *`);
      }
      // Add @since for this property if version info is available
      if (propVersionInfo) {
        lines.push(`${indent} * @since ${propVersionInfo.introducedIn}`);
        lines.push(`${indent} *`);
      }
      lines.push(`${indent} * @var ${phpDocType}`);
      lines.push(`${indent} */`);

      // Property declaration (omit type hint for untyped properties in PHP 7.4)
      // Use protected for inheritance support
      const typeHint = TypeMapper.getTypeHint(effectiveType);
      if (typeHint) {
        lines.push(`${indent}protected ${typeHint} $${phpName};`);
      } else {
        lines.push(`${indent}protected $${phpName};`);
      }
      lines.push('');
    }

    // Remove trailing empty line
    if (lines[lines.length - 1] === '') {
      lines.pop();
    }

    return lines;
  }

  /**
   * Renders the constructor with individual typed parameters.
   * For child classes, generates parent::__construct() call with inherited properties.
   *
   * @param properties - All properties (own + inherited)
   * @param indent - Indentation string
   * @param ownProperties - Properties defined in this class only (not inherited)
   * @param isRootType - Whether this is a root type (extends AbstractDataTransferObject)
   * @param parentIface - Parent interface (for determining parent constructor argument order)
   * @param narrowedPropertyNames - ORIGINAL names of properties with narrowed types (pass null to parent)
   * @param narrowedPhpProperties - The narrowed properties with renamed PHP names
   * @param interfaceName - Original interface name for version tracking
   */
  private renderConstructor(
    properties: readonly PhpProperty[],
    indent: string,
    ownProperties: readonly PhpProperty[] = [],
    isRootType: boolean = true,
    parentIface?: TsInterface,
    narrowedPropertyNames: ReadonlySet<string> = new Set(),
    narrowedPhpProperties: readonly (PhpProperty & { originalName?: string })[] = [],
    interfaceName?: string
  ): string[] {
    const lines: string[] = [];

    // Separate properties: const properties don't need constructor params
    const constProps = properties.filter((p) => p.constValue !== undefined);
    const nonConstProps = properties.filter((p) => p.constValue === undefined);

    // Sort: required parameters first, optional parameters after
    const requiredProps = nonConstProps.filter((p) => p.isRequired);
    const optionalProps = nonConstProps.filter((p) => !p.isRequired);
    const sortedProps = [...requiredProps, ...optionalProps];

    // Identify own vs inherited properties (using property names for comparison)
    const ownPropertyNames = new Set(ownProperties.map((p) => p.name));
    const ownConstProps = constProps.filter((p) => ownPropertyNames.has(p.name));

    // For parent constructor call, use the PARENT's property order (not the child's)
    // This ensures we match the parent's constructor signature exactly
    let parentConstructorArgs: string[] = [];
    if (!isRootType && parentIface) {
      // Get ALL properties from the parent (including its inherited properties)
      const parentClassification = classifyProperties(parentIface, this.inheritanceGraph, this.interfaceMap);
      const parentAllProps = parentClassification.allProperties;

      // Apply the same sorting logic as the parent's constructor uses
      const parentNonConstProps = parentAllProps.filter((p) => {
        const constValue = this.extractConstValue(p.type);
        return constValue === undefined;
      });
      const parentRequiredProps = parentNonConstProps.filter((p) => !p.isOptional);
      const parentOptionalProps = parentNonConstProps.filter((p) => p.isOptional);
      const parentSortedProps = [...parentRequiredProps, ...parentOptionalProps];

      // Build a set of const property names from the CHILD's properties
      // These are properties the child overrides with const values
      const childConstPropNames = new Set(constProps.map((p) => p.name));

      // Generate parent constructor arguments in the parent's order
      // For properties that are const in the child, use self::CONSTANT_NAME
      // For narrowed properties, pass null (child will store typed value separately)
      // For other properties, use $variableName
      parentConstructorArgs = parentSortedProps.map((p) => {
        const { phpName } = this.getPropertyNames(p.name);
        if (childConstPropNames.has(p.name)) {
          // This property is a const in the child - use the constant value
          const constName = this.toConstantName(p.name);
          return `self::${constName}`;
        }
        if (narrowedPropertyNames.has(p.name)) {
          // This property has a narrowed type - pass null to parent
          // The child will assign its typed value directly
          return 'null';
        }
        return `$${phpName}`;
      });
    }

    // Generate PHPDoc
    lines.push(`${indent}/**`);
    for (const prop of sortedProps) {
      const { phpName } = this.getPropertyNames(prop.name);
      const phpDocType = TypeMapper.getPhpDocType(prop.type);
      // For optional properties, ensure |null is in the type
      const effectiveType = !prop.isRequired && !phpDocType.includes('null')
        ? `${phpDocType}|null`
        : phpDocType;
      // Add @since for this parameter if version info is available
      const propVersionInfo = interfaceName ? this.versionTracker?.getPropertyVersion(interfaceName, prop.name) : undefined;
      const sinceTag = propVersionInfo ? ` @since ${propVersionInfo.introducedIn}` : '';
      lines.push(`${indent} * @param ${effectiveType} $${phpName}${sinceTag}`);
    }
    lines.push(`${indent} */`);

    // Generate constructor signature
    if (sortedProps.length === 0) {
      // No parameters needed (all properties are const)
      lines.push(`${indent}public function __construct()`);
      lines.push(`${indent}{`);
    } else {
      lines.push(`${indent}public function __construct(`);

      for (let i = 0; i < sortedProps.length; i++) {
        const prop = sortedProps[i]!;
        const { phpName } = this.getPropertyNames(prop.name);
        const isLast = i === sortedProps.length - 1;
        const comma = isLast ? '' : ',';

        // Determine type hint (omit for untyped properties in PHP 7.4)
        const isNullable = prop.type.nullable || !prop.isRequired;
        const effectiveType = { ...prop.type, nullable: isNullable };
        const typeHint = TypeMapper.getTypeHint(effectiveType);
        const typePrefix = typeHint ? `${typeHint} ` : '';

        // Add default value for optional parameters
        if (!prop.isRequired) {
          const defaultValue = this.getDefaultValue(prop);
          lines.push(`${indent}${indent}${typePrefix}$${phpName} = ${defaultValue}${comma}`);
        } else {
          lines.push(`${indent}${indent}${typePrefix}$${phpName}${comma}`);
        }
      }

      lines.push(`${indent}) {`);
    }

    // For child classes, call parent constructor with inherited properties (in parent's order)
    if (!isRootType && parentConstructorArgs.length > 0) {
      lines.push(`${indent}${indent}parent::__construct(${parentConstructorArgs.join(', ')});`);
    }

    // Assign own const properties from class constants (only for this class, not inherited)
    for (const prop of ownConstProps) {
      const { phpName } = this.getPropertyNames(prop.name);
      const constName = this.toConstantName(prop.name);
      lines.push(`${indent}${indent}$this->${phpName} = self::${constName};`);
    }

    // Assign only own non-const properties from parameters (inherited are handled by parent)
    const ownNonConstProps = sortedProps.filter((p) => ownPropertyNames.has(p.name));
    for (const prop of ownNonConstProps) {
      const { phpName } = this.getPropertyNames(prop.name);
      lines.push(`${indent}${indent}$this->${phpName} = $${phpName};`);
    }

    // Assign narrowed properties from parameters (we passed null to parent, child stores typed value)
    // Use the RENAMED property name (e.g., $this->typedParams) but parameter has original name ($params)
    for (const narrowedProp of narrowedPhpProperties) {
      const originalName = narrowedProp.originalName ?? narrowedProp.name;
      const { phpName: originalPhpName } = this.getPropertyNames(originalName);
      const renamedPhpName = narrowedProp.name; // Already renamed (e.g., typedParams)
      // Parameter uses original name, property uses renamed name
      lines.push(`${indent}${indent}$this->${renamedPhpName} = $${originalPhpName};`);
    }

    lines.push(`${indent}}`);

    return lines;
  }

  /**
   * Renders the fromArray static factory method.
   */
  private renderFromArray(properties: readonly PhpProperty[], indent: string): string[] {
    const lines: string[] = [];

    // Separate properties: const properties don't need constructor params
    const nonConstProps = properties.filter((p) => p.constValue === undefined);

    // Sort: required parameters first, optional parameters after (same order as constructor)
    const requiredProps = nonConstProps.filter((p) => p.isRequired);
    const optionalProps = nonConstProps.filter((p) => !p.isRequired);
    const sortedProps = [...requiredProps, ...optionalProps];

    // Generate multi-line array shape for @param (provides IDE autocomplete)
    // Note: @phpstan-param allows loose array type for internal nested hydration,
    // while @param array shape provides IDE documentation and autocomplete.
    // Runtime validation is done via assertRequired().
    const arrayShape = this.generateArrayShape(properties);
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Creates an instance from an array.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param array{`);
    for (let i = 0; i < arrayShape.length; i++) {
      const isLast = i === arrayShape.length - 1;
      lines.push(`${indent} *     ${arrayShape[i]}${isLast ? '' : ','}`);
    }
    lines.push(`${indent} * } $data`);
    lines.push(`${indent} * @phpstan-param array<string, mixed> $data`);
    lines.push(`${indent} * @return self`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function fromArray(array $data): self`);
    lines.push(`${indent}{`);

    // Validate required fields using the ValidatesRequiredFields trait
    // Note: Const fields (like `type: 'object'`) are NOT validated here because
    // they are always set from class constants in the constructor, ignoring input.
    if (requiredProps.length > 0) {
      lines.push(`${indent}${indent}self::assertRequired($data, [${requiredProps.map((p) => `'${p.name}'`).join(', ')}]);`);
      lines.push('');
    }

    // Validate maxItems constraints for array properties (from JSDoc "Must not exceed N items")
    const propsWithMaxItems = sortedProps.filter((p) => p.maxItems !== undefined);
    if (propsWithMaxItems.length > 0) {
      lines.push(...this.renderMaxItemsValidation(propsWithMaxItems, indent));
      lines.push('');
    }

    // Generate constructor call with individual parameters
    // Each class overrides fromArray(), so `new self()` is correct for each class
    if (sortedProps.length === 0) {
      lines.push(`${indent}${indent}return new self();`);
    } else {
      // For non-primitive DTO types, we need to extract variables with @var annotations
      // to help PHPStan understand the types when the else branch of a ternary returns mixed
      const variableAssignments: string[] = [];
      const constructorArgs: string[] = [];

      for (let i = 0; i < sortedProps.length; i++) {
        const prop = sortedProps[i]!;
        const { phpName, jsonKey } = this.getPropertyNames(prop.name);

        // Get deserialization expression for nested DTO types
        const rawExpr = `$data['${jsonKey}']`;
        const isOptional = !prop.isRequired;
        const deserExpr = this.getDeserializationExpression(prop.type, rawExpr, indent, isOptional, prop.name);

        // Check if this is a DTO type (non-primitive) that needs a @var annotation
        const isPrimitiveType = DtoGenerator.PRIMITIVE_TYPES.has(prop.type.type) &&
          (!prop.type.isArray || !prop.type.arrayItemType || DtoGenerator.PRIMITIVE_TYPES.has(prop.type.arrayItemType));

        // Check if the type needs a @var annotation for narrowing:
        // - Non-primitive types (DTOs, interfaces)
        // - Primitive types with narrower phpDocType (e.g., string with literal union like 'a'|'b'|'c')
        const needsVarAnnotation = !isPrimitiveType || this.hasNarrowerPhpDocType(prop.type);

        if (prop.isRequired) {
          if (needsVarAnnotation) {
            // Extract to variable with @var annotation for PHPStan type narrowing
            const varName = `$${phpName}`;
            const phpDocType = TypeMapper.getPhpDocType(prop.type);
            variableAssignments.push(`${indent}${indent}/** @var ${phpDocType} ${varName} */`);

            // Handle multiline DTO hydration for readability
            if (deserExpr.expression === 'MULTILINE_REQUIRED_DTO' && deserExpr.dtoHydrator) {
              const dataExpr = deserExpr.varExpr ?? rawExpr;
              variableAssignments.push(`${indent}${indent}${varName} = is_array(${dataExpr})`);
              variableAssignments.push(`${indent}${indent}${indent}? ${deserExpr.dtoHydrator}::fromArray(self::asArray(${dataExpr}))`);
              variableAssignments.push(`${indent}${indent}${indent}: ${dataExpr};`);
            } else if (deserExpr.expression === 'MULTILINE_REQUIRED_DTO_ARRAY' && deserExpr.dtoHydrator) {
              const dataExpr = deserExpr.varExpr ?? rawExpr;
              variableAssignments.push(`${indent}${indent}${varName} = array_map(`);
              variableAssignments.push(`${indent}${indent}${indent}static fn($item) => is_array($item)`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}? ${deserExpr.dtoHydrator}::fromArray(self::asArray($item))`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}: $item,`);
              variableAssignments.push(`${indent}${indent}${indent}self::asArray(${dataExpr})`);
              variableAssignments.push(`${indent}${indent});`);
            } else {
              variableAssignments.push(`${indent}${indent}${varName} = ${deserExpr.expression};`);
            }
            variableAssignments.push('');
            constructorArgs.push(varName);
          } else {
            // Simple primitive type - use inline expression
            constructorArgs.push(deserExpr.expression);
          }
        } else {
          // Optional property
          if (!needsVarAnnotation) {
            // Simple primitive type - use the OrNull helper which handles missing/null values
            // Use null coalescing to handle missing keys before the helper
            constructorArgs.push(deserExpr.expression.replace(`$data['${jsonKey}']`, `$data['${jsonKey}'] ?? null`));
          } else {
            // Complex type - extract to variable with @var annotation
            const varName = `$${phpName}`;
            const phpDocType = TypeMapper.getPhpDocType(prop.type);
            const nullableType = phpDocType.includes('null') ? phpDocType : `${phpDocType}|null`;
            const defaultValue = this.getDefaultValue(prop);
            variableAssignments.push(`${indent}${indent}/** @var ${nullableType} ${varName} */`);
            variableAssignments.push(`${indent}${indent}${varName} = isset(${rawExpr})`);

            // Handle multiline DTO hydration for readability
            if (deserExpr.expression === 'MULTILINE_OPTIONAL_DTO' && deserExpr.dtoHydrator) {
              const dataExpr = deserExpr.varExpr ?? rawExpr;
              variableAssignments.push(`${indent}${indent}${indent}? (is_array(${dataExpr})`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}? ${deserExpr.dtoHydrator}::fromArray(self::asArray(${dataExpr}))`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}: ${dataExpr})`);
            } else if (deserExpr.expression === 'MULTILINE_OPTIONAL_DTO_ARRAY' && deserExpr.dtoHydrator) {
              const dataExpr = deserExpr.varExpr ?? rawExpr;
              variableAssignments.push(`${indent}${indent}${indent}? array_map(`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}static fn($item) => is_array($item)`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}${indent}? ${deserExpr.dtoHydrator}::fromArray(self::asArray($item))`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}${indent}: $item,`);
              variableAssignments.push(`${indent}${indent}${indent}${indent}self::asArray(${dataExpr})`);
              variableAssignments.push(`${indent}${indent}${indent})`);
            } else {
              variableAssignments.push(`${indent}${indent}${indent}? ${deserExpr.expression}`);
            }
            variableAssignments.push(`${indent}${indent}${indent}: ${defaultValue};`);
            variableAssignments.push('');
            constructorArgs.push(varName);
          }
        }
      }

      // Output variable assignments
      for (const line of variableAssignments) {
        lines.push(line);
      }

      // Output constructor call
      lines.push(`${indent}${indent}return new self(`);
      for (let i = 0; i < constructorArgs.length; i++) {
        const isLast = i === constructorArgs.length - 1;
        const comma = isLast ? '' : ',';
        const arg = constructorArgs[i]!;
        // If arg is a variable reference (starts with $), just output it
        // Otherwise it's an inline expression
        if (arg.startsWith('$')) {
          lines.push(`${indent}${indent}${indent}${arg}${comma}`);
        } else {
          lines.push(`${indent}${indent}${indent}${arg}${comma}`);
        }
      }
      lines.push(`${indent}${indent});`);
    }

    lines.push(`${indent}}`);

    return lines;
  }

  /**
   * Renders the toArray method.
   *
   * For root types: builds result from scratch with all properties.
   * For child types: calls parent::toArray() and merges only own properties.
   *
   * @param properties - All properties (for PHPDoc array shape)
   * @param indent - Indentation string
   * @param ownProperties - Properties defined in this class only (not inherited)
   * @param isRootType - Whether this is a root type (extends AbstractDataTransferObject)
   */
  private renderToArray(
    properties: readonly PhpProperty[],
    indent: string,
    ownProperties: readonly PhpProperty[] = [],
    isRootType: boolean = true
  ): string[] {
    const lines: string[] = [];

    lines.push(`${indent}/**`);
    lines.push(`${indent} * Converts the instance to an array.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return array<string, mixed>`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public function toArray(): array`);
    lines.push(`${indent}{`);

    // For root types: start with empty array
    // For child types: start with parent's array (includes all inherited properties)
    if (isRootType) {
      lines.push(`${indent}${indent}$result = [];`);
    } else {
      lines.push(`${indent}${indent}$result = parent::toArray();`);
    }
    lines.push('');

    // For root types, serialize ALL properties
    // For child types, only serialize OWN properties (parent handles inherited ones)
    const propsToSerialize = isRootType ? properties : ownProperties;

    for (const prop of propsToSerialize) {
      // For narrowed properties, use originalName for JSON key but renamed name for PHP property
      const propAny = prop as PhpProperty & { originalName?: string };
      const originalName = propAny.originalName ?? prop.name;
      const { jsonKey } = this.getPropertyNames(originalName); // JSON key from original name
      // For PHP property access, use the sanitized name (e.g., 'schema' not '$schema')
      // But for narrowed properties, the name is already renamed (e.g., 'typedParams')
      const isNarrowedProp = propAny.originalName !== undefined;
      const { phpName: sanitizedPhpName } = this.getPropertyNames(prop.name);
      const phpName = isNarrowedProp ? prop.name : sanitizedPhpName;

      // Determine serialization method based on type
      const serializationExpr = this.getSerializationExpression(prop.type, `$this->${phpName}`);

      if (prop.serializeEmptyAsObject) {
        lines.push(
          `${indent}${indent}$result['${jsonKey}'] = !empty($this->${phpName})`
        );
        lines.push(`${indent}${indent}${indent}? ${serializationExpr}`);
        lines.push(`${indent}${indent}${indent}: new \\stdClass();`);
      } else if (prop.type.nullable || !prop.isRequired) {
        lines.push(`${indent}${indent}if ($this->${phpName} !== null) {`);
        lines.push(`${indent}${indent}${indent}$result['${jsonKey}'] = ${serializationExpr};`);
        lines.push(`${indent}${indent}}`);
      } else {
        lines.push(`${indent}${indent}$result['${jsonKey}'] = ${serializationExpr};`);
      }
    }

    // Only add blank line if we serialized properties
    if (propsToSerialize.length > 0) {
      lines.push('');
    }
    lines.push(`${indent}${indent}return $result;`);
    lines.push(`${indent}}`);

    return lines;
  }

  /**
   * Primitive PHP types that don't have a toArray()/fromArray() method.
   */
  private static readonly PRIMITIVE_TYPES = new Set([
    'string',
    'int',
    'float',
    'bool',
    'array',
    'object',
    'null',
    '',
  ]);

  /**
   * Gets the PHP expression for deserializing a property value from an array.
   *
   * For DTO objects: checks if value is array and calls ::fromArray()
   * For arrays of DTOs: maps over array and calls ::fromArray() on each item
   * For union interfaces: uses the corresponding Factory class
   * For primitives: uses type assertion helpers for PHPStan max level compliance
   *
   * Note: Uses @phpstan-ignore for DTO ternary expressions where the else branch
   * returns mixed but is known to be the correct type at runtime.
   *
   * @param phpType - The PHP type information
   * @param varExpr - The PHP variable expression (e.g., "$data['inputSchema']")
   * @param indent - The base indentation for multi-line expressions
   * @param isOptional - Whether the property is optional (affects which helper to use)
   * @param _propertyName - The property name (reserved for future use)
   * @returns Object with expression and whether it needs pre-assignment
   */
  private getDeserializationExpression(
    phpType: PhpType,
    varExpr: string,
    _indent: string,
    isOptional: boolean = false,
    _propertyName?: string
  ): { expression: string; needsVariable: boolean; variableCode?: string; dtoHydrator?: string; varExpr?: string } {
    const suffix = isOptional ? 'OrNull' : '';

    // Handle object type
    if (phpType.type === 'object' && !phpType.isArray) {
      return {
        expression: `self::asObject${suffix}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Handle array<string> or string[] (array of primitive strings)
    if (phpType.isArray && phpType.arrayItemType === 'string') {
      return {
        expression: `self::asStringArray${suffix}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Handle index signatures with string values: { [key: string]: string }
    // These use asStringMap() for runtime validation that all values are strings
    if (phpType.isIndexSignature && phpType.indexSignatureValueType === 'string') {
      return {
        expression: `self::asStringMap${suffix}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Handle index signatures with object values: { [key: string]: object }
    // These use asObjectMap() for runtime validation that all values are objects
    if (phpType.isIndexSignature && phpType.indexSignatureValueType === 'object') {
      return {
        expression: `self::asObjectMap${suffix}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Check if it's an array of DTO objects
    if (phpType.isArray) {
      const itemType = phpType.arrayItemType ?? '';

      // Check if item type is a DTO class (not a primitive)
      // For union interfaces, arrayItemType might be empty but phpDocType contains the interface name
      const isDtoArray = !DtoGenerator.PRIMITIVE_TYPES.has(itemType) && itemType !== '';

      // Also check phpDocType for array of union interfaces: array<FQN\Interface>
      const phpDocMatch = phpType.phpDocType?.match(/^array<(.+)>$/);
      const phpDocItemType = phpDocMatch?.[1] ?? '';
      const isUnionInterfaceArray = phpDocItemType.includes('Interface') && phpDocItemType.includes('\\');

      if (isDtoArray) {
        // For union interfaces (ending with Interface), use the Factory class
        const hydratorClass = this.getHydratorClass(itemType);
        // For arrays, we need to map over and hydrate each item
        // But we need to handle the case where the value might already be an object
        // Use self::asArray() to narrow the outer array type for PHPStan
        // Return multiline format for readability
        return {
          expression: isOptional ? 'MULTILINE_OPTIONAL_DTO_ARRAY' : 'MULTILINE_REQUIRED_DTO_ARRAY',
          needsVariable: false,
          dtoHydrator: hydratorClass,
          varExpr,
        };
      }

      if (isUnionInterfaceArray) {
        // Array of union interface types - extract factory name from phpDocType
        // phpDocType is like: array<\WP\McpSchema\...\Union\SomeInterface>
        const interfaceNameMatch = phpDocItemType.match(/\\(\w+Interface)(?:\|.*)?$/);
        const interfaceName = interfaceNameMatch?.[1] ?? '';
        const factoryName = interfaceName.replace(/Interface$/, 'Factory');

        // Return multiline format for readability
        return {
          expression: isOptional ? 'MULTILINE_OPTIONAL_DTO_ARRAY' : 'MULTILINE_REQUIRED_DTO_ARRAY',
          needsVariable: false,
          dtoHydrator: factoryName,
          varExpr,
        };
      }

      // Array of primitives - use asArray() helper
      return {
        expression: `self::asArray${suffix}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Check if it's a single DTO object (not a primitive)
    if (!phpType.isArray && !DtoGenerator.PRIMITIVE_TYPES.has(phpType.type)) {
      // For union interfaces (ending with Interface), use the Factory class
      const hydratorClass = this.getHydratorClass(phpType.type);
      // Need to check if value is array and hydrate, or use as-is if already object
      // Use self::asArray() when calling fromArray to narrow the type
      // Return multiline format for readability and to stay under 120 char line limit
      return {
        expression: isOptional ? 'MULTILINE_OPTIONAL_DTO' : 'MULTILINE_REQUIRED_DTO',
        needsVariable: false,
        // Store parts for multiline rendering
        dtoHydrator: hydratorClass,
        varExpr,
      };
    }

    // Primitive types - use type assertion helpers for PHPStan max level
    const helperMethod = this.getPrimitiveHelper(phpType.type, isOptional);
    if (helperMethod) {
      return {
        expression: `self::${helperMethod}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Handle string|number union type (ProgressToken pattern)
    // This is an untyped PHP 7.4 field with phpDocType 'string|number'
    if (phpType.isUntyped && phpType.phpDocType === 'string|number') {
      return {
        expression: `self::asStringOrNumber${suffix}(${varExpr})`,
        needsVariable: false,
      };
    }

    // Unknown or mixed type - return as-is
    return {
      expression: varExpr,
      needsVariable: false,
    };
  }

  /**
   * Gets the helper method name for a primitive type.
   *
   * @param typeName - The PHP type name (string, int, float, bool, etc.)
   * @param isOptional - Whether the property is optional (uses OrNull variant)
   * @returns The helper method name (e.g., "asString", "asStringOrNull") or undefined
   */
  private getPrimitiveHelper(typeName: string, isOptional: boolean): string | undefined {
    const suffix = isOptional ? 'OrNull' : '';
    switch (typeName) {
      case 'string':
        return `asString${suffix}`;
      case 'int':
        return `asInt${suffix}`;
      case 'float':
        return `asFloat${suffix}`;
      case 'bool':
        return `asBool${suffix}`;
      case 'array':
        return `asArray${suffix}`;
      default:
        return undefined;
    }
  }

  /**
   * Gets the class name to use for hydration (fromArray).
   *
   * For union interfaces (ending with "Interface"), returns the corresponding Factory class.
   * For regular DTOs, returns the class name as-is.
   *
   * @param typeName - The PHP type name (e.g., "ContentBlockInterface" or "Tool")
   * @returns The class name to call fromArray() on
   */
  private getHydratorClass(typeName: string): string {
    // Union interfaces end with "Interface" and need Factory for hydration
    if (typeName.endsWith('Interface')) {
      // Replace "Interface" with "Factory" and adjust namespace reference
      // The factory is in the Factory/ subfolder, interface is in Union/ subfolder
      // Since imports handle namespaces, we just need to use the right class name
      return typeName.replace(/Interface$/, 'Factory');
    }
    return typeName;
  }

  /**
   * Checks if a PhpType has a phpDocType that's narrower than its runtime type.
   *
   * This detects cases where PHPStan needs a @var annotation to understand
   * the actual type, such as:
   * - String literal unions: 'accept'|'decline'|'cancel' (narrower than string)
   * - Specific numeric literals: 1|2|3 (narrower than int)
   * - Arrays with narrower item types: array<'user'|'assistant'> vs array<string>
   * - Arrays of objects: array<object> (runtime helper returns array<string, mixed>)
   *
   * @param phpType - The PHP type information
   * @returns true if the phpDocType is narrower and needs @var annotation
   */
  private hasNarrowerPhpDocType(phpType: PhpType): boolean {
    if (!phpType.phpDocType) {
      return false;
    }

    // String with literal union phpDocType (contains quoted strings separated by |)
    // e.g., "'accept'|'decline'|'cancel'" or "'2.0'"
    if (phpType.type === 'string' && /^'[^']*'(\|'[^']*')*$/.test(phpType.phpDocType)) {
      return true;
    }

    // Untyped (mixed in PHP 7.4) with complex union phpDocType
    // e.g., "string|number" for JSON-RPC id
    if (phpType.isUntyped && phpType.phpDocType.includes('|')) {
      return true;
    }

    // Array types with narrower phpDocType than what runtime helpers can provide:
    // - array<'user'|'assistant'> (runtime: array<int, string>)
    // - array<object> (runtime: array<string, mixed>)
    // - array<SomeInterface> (runtime: array<string, mixed>)
    // - array<\FQN\SomeInterface> (union interface arrays - need @var for PHPStan)
    if (phpType.isArray) {
      // Array of literal strings - needs @var for PHPStan
      // e.g., phpDocType like "array<'user'|'assistant'>" or "'user'|'assistant'[]"
      if (phpType.phpDocType.includes("'") && phpType.phpDocType.includes('|')) {
        return true;
      }

      // Array of objects - asArray() returns array<string, mixed>, not array<object>
      if (phpType.arrayItemType === 'object') {
        return true;
      }

      // Array of union interfaces - need @var for PHPStan to understand the type
      // e.g., array<\WP\McpSchema\...\Union\SomeInterface>
      if (phpType.phpDocType.includes('Interface') && phpType.phpDocType.includes('\\Union\\')) {
        return true;
      }

      // Array of union types (DTO1|DTO2) that don't have an arrayItemType
      // e.g., array<\WP\McpSchema\...\BlobResourceContents|\WP\McpSchema\...\TextResourceContents>
      if (!phpType.arrayItemType && phpType.phpDocType.includes('|') && phpType.phpDocType.includes('\\')) {
        return true;
      }
    }

    return false;
  }

  /**
   * Gets the PHP expression for serializing a property value.
   *
   * For DTO objects: calls ->toArray()
   * For arrays of DTOs: maps over array and calls ->toArray() on each item
   * For primitives: returns value directly
   *
   * @param phpType - The PHP type information
   * @param varExpr - The PHP variable expression (e.g., '$this->inputSchema')
   */
  private getSerializationExpression(phpType: PhpType, varExpr: string): string {
    // Check if it's an array of DTO objects
    if (phpType.isArray && phpType.arrayItemType) {
      const itemType = phpType.arrayItemType;
      // If the array item is a DTO class (not a primitive), serialize each item
      if (!DtoGenerator.PRIMITIVE_TYPES.has(itemType)) {
        return `array_map(static fn($item) => $item->toArray(), ${varExpr})`;
      }
    }

    // Array types without a single arrayItemType can still be arrays of DTO unions, e.g.:
    // array<Foo|Bar> where Foo and Bar are DTOs. At runtime, allow both DTO objects and already-serialized arrays.
    if (phpType.isArray && !phpType.arrayItemType && phpType.phpDocType?.includes('\\WP\\McpSchema\\')) {
      return `array_map(static fn($item) => (is_object($item) && method_exists($item, 'toArray')) ? $item->toArray() : $item, ${varExpr})`;
    }

    // Check if it's a single DTO object (not a primitive)
    if (!phpType.isArray && !DtoGenerator.PRIMITIVE_TYPES.has(phpType.type)) {
      return `${varExpr}->toArray()`;
    }

    // Untyped (PHP 7.4) properties can represent DTO unions via PHPDoc. Serialize to arrays when runtime value is a DTO.
    if (phpType.isUntyped && phpType.phpDocType?.includes('\\WP\\McpSchema\\')) {
      return `(is_object(${varExpr}) && method_exists(${varExpr}, 'toArray')) ? ${varExpr}->toArray() : ${varExpr}`;
    }

    // Primitive type or array of primitives - return as-is
    return varExpr;
  }

  /**
   * Renders getter methods.
   */
  private renderGetters(properties: readonly PhpProperty[], indent: string): string[] {
    const lines: string[] = [];

    for (const prop of properties) {
      // For narrowed properties, use originalName for getter method name but renamed name for property
      const propAny = prop as PhpProperty & { originalName?: string };
      const originalName = propAny.originalName ?? prop.name;
      const isNarrowedProp = propAny.originalName !== undefined;
      const { phpName: originalPhpName } = this.getPropertyNames(originalName);
      const phpName = prop.name; // PHP property name (may be renamed like 'typedParams')

      // For PHP property access, use the sanitized name (e.g., 'schema' not '$schema')
      // But for narrowed properties, the name is already renamed (e.g., 'typedParams')
      const { phpName: sanitizedPhpName } = this.getPropertyNames(phpName);
      const actualPhpName = isNarrowedProp ? phpName : sanitizedPhpName;

      // For narrowed properties, generate a NEW getter (getTypedParams) instead of overriding parent's getter
      // This avoids Liskov Substitution violations (parent returns ?array, child can't override with SomeClass)
      // User can call getTypedParams() for the specific type, or getParams() for parent's array version
      const methodName = isNarrowedProp
        ? `get${this.toPascalCase(actualPhpName)}` // e.g., getTypedParams (new method)
        : `get${this.toPascalCase(originalPhpName)}`; // e.g., getParams (normal method)

      // Determine effective nullability for return type
      const isNullable = prop.type.nullable || !prop.isRequired;
      const effectiveType = { ...prop.type, nullable: isNullable };
      const typeHint = TypeMapper.getTypeHint(effectiveType);
      // Now we can use proper return type since we're not overriding parent
      const returnType = typeHint ? `: ${typeHint}` : '';

      lines.push(`${indent}/**`);
      lines.push(`${indent} * @return ${TypeMapper.getPhpDocType(effectiveType)}`);
      lines.push(`${indent} */`);
      lines.push(`${indent}public function ${methodName}()${returnType}`);
      lines.push(`${indent}{`);
      lines.push(`${indent}${indent}return $this->${actualPhpName};`);
      lines.push(`${indent}}`);
      lines.push('');
    }

    // Remove trailing empty line
    if (lines[lines.length - 1] === '') {
      lines.pop();
    }

    return lines;
  }

  /**
   * Gets the default value for an optional property.
   * Always returns null for consistency with nullable type declarations.
   */
  private getDefaultValue(prop: PhpProperty): string {
    if (prop.defaultValue !== undefined) {
      return this.formatPhpValue(prop.defaultValue);
    }

    // Always use null for optional properties to match the nullable type declaration
    return 'null';
  }

  /**
   * Formats a value as PHP code.
   */
  private formatPhpValue(value: string): string {
    // Already a number
    if (/^-?\d+(\.\d+)?$/.test(value)) {
      return value;
    }

    // Boolean
    if (value === 'true' || value === 'false') {
      return value;
    }

    // Null
    if (value === 'null') {
      return 'null';
    }

    // String - add quotes
    return `'${value.replace(/'/g, "\\'")}'`;
  }

  /**
   * Converts a property name to CONSTANT_NAME.
   */
  private toConstantName(name: string): string {
    return name.replace(/([a-z])([A-Z])/g, '$1_$2').toUpperCase();
  }

  /**
   * Converts a string to PascalCase.
   */
  private toPascalCase(str: string): string {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  /**
   * Sanitizes a property name for PHP (strips leading $).
   * Returns both the PHP property name and the original JSON key.
   */
  private getPropertyNames(originalName: string): { phpName: string; jsonKey: string } {
    // If name starts with $, strip it for PHP but keep it for JSON serialization
    if (originalName.startsWith('$')) {
      return {
        phpName: originalName.slice(1),
        jsonKey: originalName,
      };
    }
    return {
      phpName: originalName,
      jsonKey: originalName,
    };
  }
}

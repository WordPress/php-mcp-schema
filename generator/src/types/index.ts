/**
 * MCP PHP Schema Generator - Type Definitions
 *
 * Core types used throughout the generator for representing TypeScript AST
 * structures and PHP code generation targets.
 */

// ============================================================================
// TypeScript AST Types (parsed from schema.ts)
// ============================================================================

/**
 * Represents a JSDoc tag extracted from TypeScript source.
 * Used primarily for @category and @internal tags.
 */
export interface JsDocTag {
  readonly tagName: string;
  readonly text?: string;
}

/**
 * Represents a property extracted from a TypeScript interface.
 */
export interface TsProperty {
  readonly name: string;
  readonly type: string;
  readonly isOptional: boolean;
  readonly description?: string;
  readonly isReadonly?: boolean;
  /** Original inline type before synthetic extraction */
  readonly originalInlineType?: string;
}

/**
 * Represents a TypeScript interface definition.
 */
export interface TsInterface {
  readonly name: string;
  readonly description?: string;
  readonly extends: readonly string[];
  readonly properties: readonly TsProperty[];
  readonly tags: readonly JsDocTag[];
  /** True if this interface was generated from an inline object type */
  readonly isSynthetic?: boolean;
  /** Parent interface name if this is a synthetic type */
  readonly syntheticParent?: string;
}

/**
 * Represents a TypeScript type alias (union types, mapped types, etc.).
 */
export interface TsTypeAlias {
  readonly name: string;
  readonly type: string;
  readonly description?: string;
  readonly tags: readonly JsDocTag[];
}

/**
 * Represents a TypeScript constant (export const NAME = value).
 */
export interface TsConstant {
  readonly name: string;
  readonly value: string | number;
  readonly valueType: 'string' | 'number';
  readonly description?: string;
}

/**
 * Combined AST output from ts-morph extraction.
 */
export interface AstOutput {
  readonly interfaces: readonly TsInterface[];
  readonly typeAliases: readonly TsTypeAlias[];
  readonly enums?: readonly TsEnum[];
  readonly constants?: readonly TsConstant[];
}

/**
 * Represents a TypeScript enum definition.
 */
export interface TsEnum {
  readonly name: string;
  readonly members: readonly TsEnumMember[];
  readonly description?: string;
  readonly tags: readonly JsDocTag[];
}

/**
 * Represents an enum member.
 */
export interface TsEnumMember {
  readonly name: string;
  readonly value: string | number;
}

// ============================================================================
// Domain Classification Types
// ============================================================================

/**
 * MCP Protocol domains.
 */
export type McpDomain = 'Server' | 'Client' | 'Common';

/**
 * MCP Protocol subdomains organized by domain.
 */
export type McpSubdomain =
  // Server subdomains
  | 'Tools'
  | 'Resources'
  | 'Prompts'
  | 'Logging'
  | 'Lifecycle'
  | 'Core'
  // Client subdomains
  | 'Sampling'
  | 'Elicitation'
  | 'Roots'
  | 'Tasks'
  // Common subdomains
  | 'Content'
  | 'Protocol'
  | 'JsonRpc';

/**
 * Domain classification result from @category tag or classifier.
 */
export interface DomainClassification {
  readonly domain: McpDomain;
  readonly subdomain: McpSubdomain;
}

/**
 * Maps @category tag values to domain/subdomain.
 */
export type CategoryMapping = Record<string, DomainClassification>;

// ============================================================================
// PHP Generation Types
// ============================================================================

/**
 * PHP type representation.
 */
export interface PhpType {
  readonly type: string;
  readonly nullable: boolean;
  readonly isArray: boolean;
  readonly arrayItemType?: string;
  readonly phpDocType?: string;
  /**
   * When true, omit PHP type hint but keep PHPDoc annotation.
   * Used for PHP 7.4 compatibility (e.g., `mixed` is PHP 8.0+).
   */
  readonly isUntyped?: boolean;
  /**
   * When true, this type represents an index signature like { [key: string]: T }.
   * Used to select the appropriate validation helper in fromArray().
   */
  readonly isIndexSignature?: boolean;
  /**
   * The value type of an index signature.
   * - 'string': { [key: string]: string } -> use asStringMap() helper
   * - 'object': { [key: string]: object } -> use asArray() helper
   * - 'mixed': { [key: string]: unknown } -> use asArray() helper
   */
  readonly indexSignatureValueType?: 'string' | 'object' | 'mixed';
}

/**
 * PHP property for DTO generation.
 */
export interface PhpProperty {
  readonly name: string;
  readonly type: PhpType;
  readonly description?: string;
  readonly isRequired: boolean;
  readonly defaultValue?: string;
  readonly constValue?: string;
  /**
   * Maximum number of items allowed in an array property.
   * Extracted from JSDoc comments like "Must not exceed N items".
   * Used to generate validation in fromArray().
   */
  readonly maxItems?: number;
  /**
   * When true, empty values serialize as stdClass so json_encode() emits
   * an object ({}) instead of an array ([]).
   */
  readonly serializeEmptyAsObject?: boolean;
}

/**
 * PHP class metadata for generation.
 */
export interface PhpClassMeta {
  readonly className: string;
  readonly namespace: string;
  readonly domain: McpDomain;
  readonly subdomain: McpSubdomain;
  readonly description?: string;
  readonly properties: readonly PhpProperty[];
  readonly extends?: string;
  readonly implements?: readonly string[];
  readonly traits?: readonly string[];
  readonly constants?: readonly PhpConstant[];
  readonly isAbstract?: boolean;
  readonly isFinal?: boolean;
}

/**
 * PHP class constant.
 */
export interface PhpConstant {
  readonly name: string;
  readonly value: string;
  readonly visibility?: 'public' | 'protected' | 'private';
}

/**
 * PHP method parameter.
 */
export interface PhpParameter {
  readonly name: string;
  readonly type: PhpType;
  readonly defaultValue?: string;
  readonly isVariadic?: boolean;
}

/**
 * PHP method metadata.
 */
export interface PhpMethod {
  readonly name: string;
  readonly visibility: 'public' | 'protected' | 'private';
  readonly returnType: PhpType;
  readonly parameters: readonly PhpParameter[];
  readonly isStatic?: boolean;
  readonly isAbstract?: boolean;
  readonly isFinal?: boolean;
  readonly body?: string;
  readonly description?: string;
}

// ============================================================================
// Generator Output Types
// ============================================================================

/**
 * Represents a generated PHP file.
 */
export interface GeneratedFile {
  readonly path: string;
  readonly content: string;
  readonly type: 'dto' | 'enum' | 'union' | 'factory' | 'builder' | 'interface';
}

/**
 * Generation result containing all generated files.
 */
export interface GenerationResult {
  readonly files: readonly GeneratedFile[];
  readonly stats: GenerationStats;
  readonly errors: readonly GenerationError[];
}

/**
 * Statistics about the generation process.
 */
export interface GenerationStats {
  readonly totalTypes: number;
  readonly dtos: number;
  readonly enums: number;
  readonly unions: number;
  readonly factories: number;
  readonly builders: number;
  readonly interfaces: number;
  readonly duration: number;
}

/**
 * Error encountered during generation.
 */
export interface GenerationError {
  readonly type: string;
  readonly message: string;
  readonly source?: string;
}

// ============================================================================
// Union Membership Types
// ============================================================================

/**
 * Information about a union interface that a DTO implements.
 */
export interface UnionMembershipInfo {
  /** The union interface name (without 'Interface' suffix) */
  readonly unionName: string;
  /** The full PHP namespace for the union interface */
  readonly namespace: string;
  /** The discriminator field name (e.g., 'type', 'method') */
  readonly discriminatorField?: string;
  /** The discriminator value for this specific member (e.g., 'text', 'tools/call') */
  readonly discriminatorValue?: string;
}

/**
 * Map from DTO name to the union interfaces it should implement.
 */
export type UnionMembershipMap = Map<string, UnionMembershipInfo[]>;

// ============================================================================
// Configuration Types
// ============================================================================

/**
 * Schema source configuration.
 * Source is always GitHub (modelcontextprotocol/modelcontextprotocol).
 */
export interface SchemaSource {
  readonly repository: string;
  readonly branch: string;
  readonly path: string;
  readonly version: string;
}

/**
 * PHP output configuration.
 */
export interface PhpOutputConfig {
  readonly outputDir: string;
  readonly namespace: string;
  readonly indentation: 'spaces' | 'tabs';
  readonly indentSize: number;
}

/**
 * Main generator configuration.
 */
export interface GeneratorConfig {
  readonly schema: SchemaSource;
  readonly output: PhpOutputConfig;
  readonly verbose: boolean;
  readonly dryRun: boolean;
}

// ============================================================================
// Version Tracking Types
// ============================================================================

/**
 * Version information for a definition (interface/type).
 */
export interface VersionInfo {
  /** Version when this definition was first introduced (e.g., "2024-11-05") */
  readonly introducedIn: string;
  /** Version when this definition was last modified (only if different from introducedIn) */
  readonly lastModified?: string;
  /** Human-readable summary of changes in the last modification */
  readonly changeSummary?: string;
}

/**
 * Version information for a property within a definition.
 */
export interface PropertyVersionInfo {
  /** Version when this property was first introduced */
  readonly introducedIn: string;
}

/**
 * Version tracker providing version information for definitions and properties.
 */
export interface VersionTracker {
  /** Get version info for a definition */
  getDefinitionVersion(name: string): VersionInfo | undefined;
  /** Get version info for a property within a definition */
  getPropertyVersion(definitionName: string, propertyName: string): PropertyVersionInfo | undefined;
  /** Get all property versions for a definition */
  getPropertyVersions(definitionName: string): ReadonlyMap<string, PropertyVersionInfo>;
  /** Check if a definition was modified after introduction */
  wasModified(definitionName: string): boolean;
}

// ============================================================================
// Skill Types (Re-exported)
// ============================================================================

export type {
  SkillConfig,
  SkillSchemaIndex,
  SkillSchemaSummary,
  SkillRpcMethod,
  SkillTypeInfo,
  SkillFactoryInfo,
  SkillDomainData,
  SkillTypeTableEntry,
  SkillSubdomainSection,
  SkillRpcEntry,
  SkillFactoryEntry,
  SkillGeneratedFile,
  SkillGenerationResult,
  SkillGenerationStats,
} from './skill-types.js';

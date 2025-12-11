/**
 * MCP PHP Schema Generator - Schema Map Generator
 *
 * Generates a JSON map of all types, their relationships, and connections.
 * Designed to help LLMs understand and navigate the generated schema.
 */

import type {
  TsInterface,
  TsTypeAlias,
  TsEnum,
  GeneratorConfig,
  DomainClassification,
  UnionMembershipMap,
} from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import type { IntersectionTypeWrapperInfo } from './intersection-type-wrapper.js';

// ============================================================================
// Schema Map Types
// ============================================================================

/**
 * Property information for the schema map.
 */
export interface SchemaMapProperty {
  readonly name: string;
  readonly type: string;
  readonly optional: boolean;
  readonly description?: string;
}

/**
 * Type information for the schema map.
 */
export interface SchemaMapType {
  readonly kind: 'class' | 'enum' | 'union' | 'factory' | 'constant';
  readonly domain: string;
  readonly subdomain: string;
  readonly namespace: string;
  readonly purpose: string;
  readonly extends?: string;
  readonly implements: string[];
  readonly properties: Record<string, string>;
  readonly usedBy: string[];
  readonly uses: string[];
  readonly discriminator?: {
    readonly field: string;
    readonly value?: string;
  };
}

/**
 * Factory information for the schema map.
 */
export interface SchemaMapFactory {
  readonly interface: string;
  readonly discriminator: string;
  readonly mappings: Record<string, string>;
  readonly domain: string;
  readonly subdomain: string;
}

/**
 * RPC endpoint information.
 */
export interface SchemaMapRpc {
  readonly direction: 'client→server' | 'server→client' | 'bidirectional';
  readonly request: string;
  readonly params?: string;
  readonly result: string;
}

/**
 * Domain summary information.
 */
export interface SchemaMapDomain {
  readonly description: string;
  readonly types: string[];
  readonly entryPoints: string[];
}

/**
 * Index for quick lookups.
 */
export interface SchemaMapIndex {
  readonly byDomain: Record<string, string[]>;
  readonly byKind: Record<string, string[]>;
  readonly byMethod: Record<string, { request: string; result: string }>;
}

/**
 * Complete schema map structure.
 */
export interface SchemaMap {
  readonly version: string;
  readonly schemaUrl: string;
  readonly generated: string;
  readonly namespace: string;
  readonly index: SchemaMapIndex;
  readonly types: Record<string, SchemaMapType>;
  readonly factories: Record<string, SchemaMapFactory>;
  readonly rpc: Record<string, SchemaMapRpc>;
  readonly domains: Record<string, SchemaMapDomain>;
}

// ============================================================================
// Schema Map Generator
// ============================================================================

/**
 * Generates a comprehensive JSON map of the schema for LLM consumption.
 */
export class SchemaMapGenerator {
  private readonly config: GeneratorConfig;
  private readonly classifier: DomainClassifier;
  private readonly interfaces: readonly TsInterface[];
  private readonly typeAliases: readonly TsTypeAlias[];
  private readonly enums: readonly TsEnum[];
  private readonly unionMembershipMap: UnionMembershipMap;
  private readonly intersectionTypes: readonly IntersectionTypeWrapperInfo[];

  // Tracking maps built during generation
  private readonly typeUsedBy = new Map<string, Set<string>>();
  private readonly typeUses = new Map<string, Set<string>>();

  constructor(
    config: GeneratorConfig,
    interfaces: readonly TsInterface[],
    typeAliases: readonly TsTypeAlias[],
    enums: readonly TsEnum[],
    unionMembershipMap: UnionMembershipMap,
    classifier?: DomainClassifier,
    intersectionTypes?: readonly IntersectionTypeWrapperInfo[]
  ) {
    this.config = config;
    this.classifier = classifier ?? new DomainClassifier();
    this.interfaces = interfaces;
    this.typeAliases = typeAliases;
    this.enums = enums;
    this.unionMembershipMap = unionMembershipMap;
    this.intersectionTypes = intersectionTypes ?? [];
  }

  /**
   * Generates the complete schema map.
   */
  generate(): SchemaMap {
    // First pass: build all types and track relationships
    const types = this.buildTypes();

    // Second pass: build indexes
    const index = this.buildIndex(types);

    // Build factories from union type aliases
    const factories = this.buildFactories();

    // Build RPC mappings
    const rpc = this.buildRpcMappings();

    // Build domain summaries
    const domains = this.buildDomains(types);

    // Build schema URL - full path to the schema.ts file for the specific version
    const schemaUrl = `https://github.com/${this.config.schema.repository}/blob/${this.config.schema.branch}/${this.config.schema.path}/${this.config.schema.version}/schema.ts`;

    return {
      version: this.config.schema.version,
      schemaUrl,
      generated: new Date().toISOString(),
      namespace: this.config.output.namespace,
      index,
      types,
      factories,
      rpc,
      domains,
    };
  }

  /**
   * Generates the schema map as a JSON string.
   */
  generateJson(pretty = true): string {
    const map = this.generate();
    return JSON.stringify(map, null, pretty ? 2 : undefined);
  }

  /**
   * Gets the output path for the schema map file.
   */
  getOutputPath(): string {
    return 'schema-map.json';
  }

  // ============================================================================
  // Private Methods - Type Building
  // ============================================================================

  private buildTypes(): Record<string, SchemaMapType> {
    const types: Record<string, SchemaMapType> = {};

    // Process interfaces (DTOs)
    for (const iface of this.interfaces) {
      const classification = this.classifier.classify(iface.name, iface.tags, iface.syntheticParent);
      const uses = this.extractTypesFromInterface(iface);

      // Track reverse relationships
      for (const usedType of uses) {
        this.addUsedBy(usedType, iface.name);
      }
      this.setUses(iface.name, uses);

      // Get union implementations
      const unionMemberships = this.unionMembershipMap.get(iface.name) ?? [];
      const implements_ = unionMemberships.map((m) => `${m.unionName}Interface`);

      // Extract discriminator info if available
      let discriminator: SchemaMapType['discriminator'];
      if (unionMemberships.length > 0) {
        const membership = unionMemberships[0];
        if (membership?.discriminatorField && membership?.discriminatorValue) {
          discriminator = {
            field: membership.discriminatorField,
            value: membership.discriminatorValue,
          };
        }
      }

      types[iface.name] = {
        kind: 'class',
        domain: classification.domain,
        subdomain: classification.subdomain,
        namespace: this.getNamespace(classification, iface.name),
        purpose: this.generatePurpose(iface),
        extends: iface.extends[0],
        implements: implements_,
        properties: this.extractProperties(iface),
        usedBy: [], // Will be populated after all types are processed
        uses: Array.from(uses),
        discriminator,
      };
    }

    // Process enums
    for (const enumDef of this.enums) {
      const classification = this.classifier.classify(enumDef.name, enumDef.tags);

      types[enumDef.name] = {
        kind: 'enum',
        domain: classification.domain,
        subdomain: classification.subdomain,
        namespace: this.getNamespace(classification, enumDef.name, 'Enum'),
        purpose: enumDef.description ?? `Enumeration of ${enumDef.name} values`,
        implements: [],
        properties: Object.fromEntries(
          enumDef.members.map((m) => [m.name, String(m.value)])
        ),
        usedBy: [],
        uses: [],
      };
    }

    // Process string literal union enums from type aliases
    for (const alias of this.typeAliases) {
      if (this.isStringLiteralUnion(alias)) {
        const classification = this.classifier.classify(alias.name, alias.tags);
        const values = this.extractStringLiteralValues(alias.type);

        types[alias.name] = {
          kind: 'enum',
          domain: classification.domain,
          subdomain: classification.subdomain,
          namespace: this.getNamespace(classification, alias.name, 'Enum'),
          purpose: alias.description ?? `Enumeration: ${values.join(', ')}`,
          implements: [],
          properties: Object.fromEntries(values.map((v) => [this.toEnumCase(v), v])),
          usedBy: [],
          uses: [],
        };
      } else if (this.isUnionType(alias)) {
        // Union type - creates interface + factory
        const classification = this.classifier.classify(alias.name, alias.tags);
        const members = this.extractUnionMembers(alias.type);

        // Track that union members are used by this union
        for (const member of members) {
          this.addUsedBy(member, `${alias.name}Interface`);
        }

        types[`${alias.name}Interface`] = {
          kind: 'union',
          domain: classification.domain,
          subdomain: classification.subdomain,
          namespace: `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Union\\${alias.name}Interface`,
          purpose: alias.description ?? `Union type: ${members.join(' | ')}`,
          implements: [],
          properties: {},
          usedBy: [],
          uses: members,
        };
      } else if (this.isSimpleTypeAlias(alias)) {
        // Simple type alias like `type EmptyResult = Result`
        const classification = this.classifier.classify(alias.name, alias.tags);
        const baseType = alias.type.trim();

        // Track relationship
        this.addUsedBy(baseType, alias.name);

        // Get union memberships for this type
        const unionMemberships = this.findUnionMembershipsForType(alias.name);
        const implements_ = unionMemberships.map((m) => `${m}Interface`);

        types[alias.name] = {
          kind: 'class',
          domain: classification.domain,
          subdomain: classification.subdomain,
          namespace: this.getNamespace(classification, alias.name),
          purpose: alias.description ?? `Type alias for ${baseType}`,
          extends: baseType,
          implements: implements_,
          properties: {},
          usedBy: [],
          uses: [baseType],
        };
      }
    }

    // Process intersection types (e.g., GetTaskResult = Result & Task)
    for (const intersection of this.intersectionTypes) {
      const classification = this.classifier.classify(intersection.typeName, intersection.typeAlias.tags);

      // Get union memberships for this type
      const implements_ = intersection.unionInterfaces.map((u) => `${u.unionName}Interface`);

      // Build properties from own properties (not inherited from base)
      const properties: Record<string, string> = {};
      for (const prop of intersection.ownProperties) {
        properties[prop.name] = this.simplifyType(prop.type) + (prop.isOptional ? '?' : '');
      }

      // Track relationships
      for (const usedType of intersection.intersectedTypes) {
        this.addUsedBy(usedType, intersection.typeName);
      }

      types[intersection.typeName] = {
        kind: 'class',
        domain: classification.domain,
        subdomain: classification.subdomain,
        namespace: this.getNamespace(classification, intersection.typeName),
        purpose: intersection.typeAlias.description ?? `Intersection type: ${intersection.intersectedTypes.join(' & ')}`,
        extends: intersection.baseType,
        implements: implements_,
        properties,
        usedBy: [],
        uses: [...intersection.intersectedTypes],
      };
    }

    // Populate usedBy from tracked relationships
    for (const [typeName, usedBySet] of this.typeUsedBy) {
      if (types[typeName]) {
        (types[typeName] as { usedBy: string[] }).usedBy = Array.from(usedBySet);
      }
    }

    return types;
  }

  private buildIndex(types: Record<string, SchemaMapType>): SchemaMapIndex {
    const byDomain: Record<string, string[]> = {};
    const byKind: Record<string, string[]> = {};
    const byMethod: Record<string, { request: string; result: string }> = {};

    for (const [name, type] of Object.entries(types)) {
      // Index by domain
      const domainKey = `${type.domain}/${type.subdomain}`;
      if (!byDomain[domainKey]) {
        byDomain[domainKey] = [];
      }
      byDomain[domainKey].push(name);

      // Index by kind
      const kindKey = type.kind;
      if (!byKind[kindKey]) {
        byKind[kindKey] = [];
      }
      byKind[kindKey]!.push(name);

      // Index by method (for requests)
      if (name.endsWith('Request') && type.discriminator?.value) {
        const method = type.discriminator.value;
        const resultName = name.replace('Request', 'Result');
        byMethod[method] = {
          request: name,
          result: types[resultName] ? resultName : 'Result',
        };
      }
    }

    return { byDomain, byKind, byMethod };
  }

  private buildFactories(): Record<string, SchemaMapFactory> {
    const factories: Record<string, SchemaMapFactory> = {};

    for (const alias of this.typeAliases) {
      if (!this.isUnionType(alias) || this.isStringLiteralUnion(alias)) {
        continue;
      }

      const members = this.extractUnionMembers(alias.type);
      const classification = this.classifier.classify(alias.name, alias.tags);

      // Detect discriminator field
      const discriminator = this.detectDiscriminatorField(members);
      if (!discriminator) {
        continue;
      }

      // Build mappings
      const mappings: Record<string, string> = {};
      for (const member of members) {
        const iface = this.interfaces.find((i) => i.name === member);
        if (iface) {
          const prop = iface.properties.find((p) => p.name === discriminator);
          if (prop) {
            const value = this.extractConstValue(prop.type);
            if (value) {
              mappings[value] = member;
            }
          }
        }
      }

      if (Object.keys(mappings).length > 0) {
        factories[`${alias.name}Factory`] = {
          interface: `${alias.name}Interface`,
          discriminator,
          mappings,
          domain: classification.domain,
          subdomain: classification.subdomain,
        };
      }
    }

    return factories;
  }

  private buildRpcMappings(): Record<string, SchemaMapRpc> {
    const rpc: Record<string, SchemaMapRpc> = {};

    // Find all request interfaces with method discriminators
    for (const iface of this.interfaces) {
      if (!iface.name.endsWith('Request')) {
        continue;
      }

      const methodProp = iface.properties.find((p) => p.name === 'method');
      if (!methodProp) {
        continue;
      }

      const method = this.extractConstValue(methodProp.type);
      if (!method) {
        continue;
      }

      // Find corresponding result
      const resultName = iface.name.replace('Request', 'Result');
      const hasResult = this.interfaces.some((i) => i.name === resultName);

      // Find params
      const paramsName = `${iface.name}Params`;
      const hasParams = this.interfaces.some((i) => i.name === paramsName);

      // Determine direction based on union memberships
      const memberships = this.unionMembershipMap.get(iface.name) ?? [];
      const isClientRequest = memberships.some((m) => m.unionName === 'ClientRequest');
      const isServerRequest = memberships.some((m) => m.unionName === 'ServerRequest');

      let direction: SchemaMapRpc['direction'];
      if (isClientRequest && isServerRequest) {
        direction = 'bidirectional';
      } else if (isClientRequest) {
        direction = 'client→server';
      } else if (isServerRequest) {
        direction = 'server→client';
      } else {
        direction = 'client→server'; // Default
      }

      rpc[method] = {
        direction,
        request: iface.name,
        params: hasParams ? paramsName : undefined,
        result: hasResult ? resultName : 'Result',
      };
    }

    return rpc;
  }

  private buildDomains(types: Record<string, SchemaMapType>): Record<string, SchemaMapDomain> {
    const domains: Record<string, SchemaMapDomain> = {};

    const domainDescriptions: Record<string, string> = {
      'Server/Tools': 'Tool definitions and invocation for server capabilities',
      'Server/Resources': 'Resource listing, reading, and subscription',
      'Server/Prompts': 'Prompt template definitions and retrieval',
      'Server/Logging': 'Server-side logging configuration and messages',
      'Server/Lifecycle': 'Server capabilities and lifecycle management',
      'Server/Core': 'Core server functionality (completion, etc.)',
      'Client/Sampling': 'LLM sampling requests from server to client',
      'Client/Elicitation': 'User input elicitation for gathering information',
      'Client/Roots': 'File system root management',
      'Client/Tasks': 'Async task execution and tracking',
      'Client/Lifecycle': 'Client capabilities and lifecycle management',
      'Common/Protocol': 'Shared protocol types (initialization, progress, etc.)',
      'Common/JsonRpc': 'JSON-RPC base types and messages',
      'Common/Content': 'Content block types (text, image, audio, etc.)',
      'Common/Tasks': 'Shared task-related types',
      'Common/Lifecycle': 'Shared lifecycle types (Implementation)',
      'Common/Core': 'Core shared types (Icon, etc.)',
    };

    // Group types by domain
    const domainTypes: Record<string, string[]> = {};
    for (const [name, type] of Object.entries(types)) {
      const key = `${type.domain}/${type.subdomain}`;
      if (!domainTypes[key]) {
        domainTypes[key] = [];
      }
      domainTypes[key].push(name);
    }

    // Build domain entries
    for (const [key, typeNames] of Object.entries(domainTypes)) {
      // Find entry points (requests in this domain)
      const entryPoints = typeNames
        .filter((name) => name.endsWith('Request') && types[name]?.discriminator?.value)
        .map((name) => types[name]?.discriminator?.value ?? name);

      domains[key] = {
        description: domainDescriptions[key] ?? `${key} domain types`,
        types: typeNames,
        entryPoints,
      };
    }

    return domains;
  }

  // ============================================================================
  // Private Methods - Helpers
  // ============================================================================

  private extractTypesFromInterface(iface: TsInterface): Set<string> {
    const types = new Set<string>();

    // Add parent types
    for (const ext of iface.extends) {
      types.add(ext);
    }

    // Extract types from properties
    for (const prop of iface.properties) {
      const extracted = this.extractTypeReferences(prop.type);
      for (const t of extracted) {
        types.add(t);
      }
    }

    return types;
  }

  private extractTypeReferences(type: string): string[] {
    const types: string[] = [];

    // Remove array syntax and extract base type
    const cleaned = type.replace(/\[\]$/, '').replace(/^readonly\s+/, '');

    // Skip primitive types
    const primitives = ['string', 'number', 'boolean', 'null', 'undefined', 'void', 'never', 'unknown', 'any', 'object'];
    if (primitives.includes(cleaned.toLowerCase())) {
      return types;
    }

    // Handle union types
    if (cleaned.includes('|')) {
      const parts = cleaned.split('|').map((p) => p.trim());
      for (const part of parts) {
        types.push(...this.extractTypeReferences(part));
      }
      return types;
    }

    // Handle generic types like Array<T>
    const genericMatch = cleaned.match(/^(\w+)<(.+)>$/);
    if (genericMatch) {
      const [, , inner] = genericMatch;
      if (inner) {
        types.push(...this.extractTypeReferences(inner));
      }
      return types;
    }

    // Skip inline object types
    if (cleaned.startsWith('{')) {
      return types;
    }

    // Skip string literals
    if (cleaned.startsWith('"') || cleaned.startsWith("'")) {
      return types;
    }

    // This looks like a type reference
    if (/^[A-Z]/.test(cleaned)) {
      types.push(cleaned);
    }

    return types;
  }

  private extractProperties(iface: TsInterface): Record<string, string> {
    const props: Record<string, string> = {};

    for (const prop of iface.properties) {
      props[prop.name] = this.simplifyType(prop.type) + (prop.isOptional ? '?' : '');
    }

    return props;
  }

  private simplifyType(type: string): string {
    // Remove readonly
    let simplified = type.replace(/^readonly\s+/, '');

    // Simplify array syntax
    simplified = simplified.replace(/Array<(.+)>/, '$1[]');

    // Truncate very long types
    if (simplified.length > 50) {
      simplified = simplified.substring(0, 47) + '...';
    }

    return simplified;
  }

  private generatePurpose(iface: TsInterface): string {
    if (iface.description) {
      // Take first sentence
      const firstSentence = iface.description.split(/[.\n]/)[0];
      return firstSentence?.trim() ?? iface.description.substring(0, 100);
    }

    // Generate from name
    const name = iface.name;

    if (name.endsWith('Request')) {
      const method = iface.properties.find((p) => p.name === 'method');
      if (method) {
        const value = this.extractConstValue(method.type);
        if (value) {
          return `Request for ${value} operation`;
        }
      }
      return `Request for ${name.replace('Request', '')} operation`;
    }

    if (name.endsWith('Result')) {
      return `Result from ${name.replace('Result', '')} operation`;
    }

    if (name.endsWith('Notification')) {
      return `Notification for ${name.replace('Notification', '').replace(/([A-Z])/g, ' $1').trim().toLowerCase()} events`;
    }

    if (name.endsWith('Params')) {
      return `Parameters for ${name.replace('Params', '')}`;
    }

    return `${name.replace(/([A-Z])/g, ' $1').trim()} data structure`;
  }

  private getNamespace(classification: DomainClassification, name: string, subdir?: string): string {
    const parts: string[] = [this.config.output.namespace, classification.domain, classification.subdomain];
    if (subdir) {
      parts.push(subdir);
    }
    parts.push(name);
    return parts.join('\\');
  }

  private isStringLiteralUnion(alias: TsTypeAlias): boolean {
    // Check if it's a union of string literals like "a" | "b" | "c"
    const parts = alias.type.split('|').map((p) => p.trim());
    return parts.every((p) => /^["']/.test(p));
  }

  private isUnionType(alias: TsTypeAlias): boolean {
    return alias.type.includes('|');
  }

  private isSimpleTypeAlias(alias: TsTypeAlias): boolean {
    // Simple type alias: no union (|) or intersection (&)
    // Just a single type reference like `type EmptyResult = Result`
    const type = alias.type.trim();
    return (
      !type.includes('|') &&
      !type.includes('&') &&
      /^[A-Z][a-zA-Z0-9]*$/.test(type) // Single PascalCase type name
    );
  }

  private findUnionMembershipsForType(typeName: string): string[] {
    const memberships: string[] = [];

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
        memberships.push(alias.name);
      }
    }

    return memberships;
  }

  private extractStringLiteralValues(type: string): string[] {
    return type
      .split('|')
      .map((p) => p.trim())
      .filter((p) => /^["']/.test(p))
      .map((p) => p.replace(/^["']|["']$/g, ''));
  }

  private extractUnionMembers(type: string): string[] {
    return type
      .split('|')
      .map((p) => p.trim())
      .filter((p) => /^[A-Z]/.test(p));
  }

  private toEnumCase(value: string): string {
    // Convert string like "tools/call" to "TOOLS_CALL"
    return value.toUpperCase().replace(/[^A-Z0-9]/g, '_');
  }

  private detectDiscriminatorField(memberNames: string[]): string | undefined {
    const memberInterfaces = memberNames
      .map((name) => this.interfaces.find((i) => i.name === name))
      .filter((i): i is TsInterface => i !== undefined);

    if (memberInterfaces.length === 0) {
      return undefined;
    }

    const first = memberInterfaces[0];
    if (!first) {
      return undefined;
    }

    const commonFields = first.properties
      .map((p) => p.name)
      .filter((name) =>
        memberInterfaces.every((m) => m.properties.some((p) => p.name === name))
      );

    const priorityFields = ['method', 'type', 'kind', 'role'];
    return priorityFields.find((f) => commonFields.includes(f)) ?? commonFields[0];
  }

  private extractConstValue(type: string): string | undefined {
    const trimmed = type.trim();

    if (trimmed.startsWith('"') && trimmed.endsWith('"')) {
      return trimmed.slice(1, -1);
    }
    if (trimmed.startsWith("'") && trimmed.endsWith("'")) {
      return trimmed.slice(1, -1);
    }

    return undefined;
  }

  private addUsedBy(typeName: string, usedByType: string): void {
    if (!this.typeUsedBy.has(typeName)) {
      this.typeUsedBy.set(typeName, new Set());
    }
    this.typeUsedBy.get(typeName)?.add(usedByType);
  }

  private setUses(typeName: string, uses: Set<string>): void {
    this.typeUses.set(typeName, uses);
  }
}

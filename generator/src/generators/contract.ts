/**
 * MCP PHP Schema Generator - Contract Generator
 *
 * Generates PHP interfaces (contracts) based on TypeScript extends relationships.
 * These marker interfaces enable polymorphic handling of related types.
 */

import type { TsInterface, GeneratorConfig } from '../types/index.js';

/**
 * Contract information for a base type.
 */
export interface ContractInfo {
  readonly baseName: string;
  readonly interfaceName: string;
  readonly implementors: string[];
  readonly description: string;
}

/**
 * Generated contract file.
 */
export interface GeneratedContract {
  readonly name: string;
  readonly content: string;
}

/**
 * Generates PHP interface contracts based on TypeScript extends relationships.
 */
export class ContractGenerator {
  private readonly config: GeneratorConfig;
  private readonly interfaces: readonly TsInterface[];

  constructor(config: GeneratorConfig, interfaces: readonly TsInterface[]) {
    this.config = config;
    this.interfaces = interfaces;
  }

  /**
   * Generates all contracts based on extends relationships.
   */
  generateAll(): GeneratedContract[] {
    const contracts: GeneratedContract[] = [];

    // Core utility interfaces (always generated)
    contracts.push(this.generateWithArrayTransformationInterface());
    contracts.push(this.generateWithJsonSchemaInterface());

    // Marker interfaces based on extends relationships
    const baseTypes = this.findBaseTypes();

    for (const baseType of baseTypes) {
      const implementors = this.findImplementors(baseType);
      if (implementors.length > 0) {
        contracts.push(this.generateMarkerInterface(baseType, implementors));
      }
    }

    return contracts;
  }

  /**
   * Finds all base types that are extended by other interfaces.
   */
  private findBaseTypes(): string[] {
    const baseTypes = new Set<string>();

    for (const iface of this.interfaces) {
      for (const ext of iface.extends) {
        baseTypes.add(ext);
      }
    }

    // Filter to only include types that should have contracts
    const contractableTypes = [
      'Result',
      'JSONRPCRequest',
      'JSONRPCNotification',
      'NotificationParams',
      'RequestParams',
      'PaginatedRequest',
      'PaginatedResult',
      'ResourceContents',
      'ResourceRequestParams',
      'TaskAugmentedRequestParams',
      'Task',
      'BaseMetadata',
      'Icons',
    ];

    return contractableTypes.filter((t) => baseTypes.has(t));
  }

  /**
   * Finds all types that implement (extend) a given base type.
   */
  private findImplementors(baseType: string): string[] {
    return this.interfaces
      .filter((iface) => this.extendsType(iface, baseType))
      .map((iface) => iface.name);
  }

  /**
   * Checks if an interface extends a given type (directly or indirectly).
   */
  private extendsType(iface: TsInterface, baseType: string, visited = new Set<string>()): boolean {
    if (visited.has(iface.name)) {
      return false;
    }
    visited.add(iface.name);

    if (iface.extends.includes(baseType)) {
      return true;
    }

    // Check indirect inheritance
    for (const parentName of iface.extends) {
      const parent = this.interfaces.find((i) => i.name === parentName);
      if (parent && this.extendsType(parent, baseType, visited)) {
        return true;
      }
    }

    return false;
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
   * Generates the WithArrayTransformationInterface.
   */
  private generateWithArrayTransformationInterface(): GeneratedContract {
    const indent = this.getIndent();
    const namespace = `${this.config.output.namespace}\\Common\\Contracts`;

    const content = `<?php

declare(strict_types=1);

namespace ${namespace};

/**
 * Interface for objects that support array transformation.
 *
 * @mcp-version ${this.config.schema.version}
 */
interface WithArrayTransformationInterface
{
${indent}/**
${indent} * Converts the object to an array representation.
${indent} *
${indent} * @return array<string, mixed> The array representation.
${indent} */
${indent}public function toArray(): array;

${indent}/**
${indent} * Creates an instance from array data.
${indent} *
${indent} * @param array<string, mixed> $data The array data.
${indent} * @return static The created instance.
${indent} */
${indent}public static function fromArray(array $data);
}
`;

    return { name: 'WithArrayTransformationInterface', content };
  }

  /**
   * Generates the WithJsonSchemaInterface.
   */
  private generateWithJsonSchemaInterface(): GeneratedContract {
    const indent = this.getIndent();
    const namespace = `${this.config.output.namespace}\\Common\\Contracts`;

    const content = `<?php

declare(strict_types=1);

namespace ${namespace};

/**
 * Interface for objects that provide their JSON Schema definition.
 *
 * @mcp-version ${this.config.schema.version}
 */
interface WithJsonSchemaInterface
{
${indent}/**
${indent} * Returns the JSON Schema definition for this type.
${indent} *
${indent} * @return array<string, mixed> The JSON Schema definition.
${indent} */
${indent}public static function getJsonSchema(): array;
}
`;

    return { name: 'WithJsonSchemaInterface', content };
  }

  /**
   * Generates a marker interface for a base type.
   */
  private generateMarkerInterface(baseType: string, implementors: string[]): GeneratedContract {
    const namespace = `${this.config.output.namespace}\\Common\\Contracts`;
    const interfaceName = `${baseType}Interface`;

    const implementorList = implementors.join(', ');

    const content = `<?php

declare(strict_types=1);

namespace ${namespace};

/**
 * Interface for types that extend ${baseType}.
 *
 * This interface is auto-generated from TypeScript extends metadata.
 * Types implementing this interface: ${implementorList}
 *
 * @mcp-version ${this.config.schema.version}
 */
interface ${interfaceName} extends WithArrayTransformationInterface
{
}
`;

    return { name: interfaceName, content };
  }
}

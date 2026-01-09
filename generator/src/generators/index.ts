/**
 * MCP PHP Schema Generator - Code Generators
 *
 * Generates PHP code (DTOs, Enums, Unions, Factories, Builders, Contracts) from TypeScript AST.
 */

export { DtoGenerator } from './dto.js';
export { EnumGenerator } from './enum.js';
export { NumericEnumGenerator } from './numeric-enum.js';
export { ConstantsGenerator } from './constants.js';
export { UnionGenerator } from './union.js';
export { FactoryGenerator } from './factory.js';
export { BuilderGenerator } from './builder.js';
export { ContractGenerator } from './contract.js';
export { TypeAliasWrapperGenerator } from './type-alias-wrapper.js';
export type { TypeAliasWrapperInfo } from './type-alias-wrapper.js';
export { IntersectionTypeWrapperGenerator } from './intersection-type-wrapper.js';
export type { IntersectionTypeWrapperInfo } from './intersection-type-wrapper.js';
export { TypeMapper, createConstantsMap } from './type-mapper.js';
export { SchemaMapGenerator } from './schema-map.js';
export type {
  SchemaMap,
  SchemaMapType,
  SchemaMapFactory,
  SchemaMapRpc,
  SchemaMapDomain,
  SchemaMapIndex,
  SchemaMapProperty,
} from './schema-map.js';
export type { ConstantsMap } from './type-mapper.js';
export { DomainClassifier } from './domain-classifier.js';
export { TypeResolver } from './type-resolver.js';
export type { ResolvedType } from './type-resolver.js';
export type { ContractInfo, GeneratedContract } from './contract.js';
export { SkillGenerator } from './skill-generator.js';
export * from './skill-markdown.js';

// Inheritance graph utilities
export {
  buildInheritanceGraph,
  getAncestors,
  getDescendants,
  getDirectParent,
  isRoot,
  getDepth,
  getInheritanceStats,
  printInheritanceTree,
  topologicalSort,
  sortInterfacesForGeneration,
  classifyProperties,
  getOwnProperties,
  buildInterfaceMap,
} from './inheritance-graph.js';
export type {
  InheritanceGraph,
  TopologicalSortResult,
  PropertyClassification,
  NarrowedProperty,
} from './inheritance-graph.js';

/**
 * Formats a description for PHPDoc multiline output.
 * Ensures every line has proper ` * ` prefix.
 *
 * @param description - The description text (may contain newlines)
 * @param indent - Optional indentation prefix (e.g., '    ' for properties)
 * @returns Array of formatted lines ready to be pushed to output
 */
export function formatPhpDocDescription(description: string, indent: string = ''): string[] {
  const lines: string[] = [];
  const descriptionLines = description.split('\n');

  for (const line of descriptionLines) {
    const trimmedLine = line.trim();
    if (trimmedLine === '') {
      // Empty line in description - just add the asterisk
      lines.push(`${indent} *`);
    } else {
      lines.push(`${indent} * ${trimmedLine}`);
    }
  }

  return lines;
}

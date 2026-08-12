/**
 * MCP PHP Schema Generator - TypeScript Parser
 *
 * Parses TypeScript schema files using ts-morph to extract interfaces,
 * type aliases, and metadata.
 */

import { Project, SourceFile, InterfaceDeclaration, TypeAliasDeclaration, EnumDeclaration, VariableDeclarationKind, Node } from 'ts-morph';
import type { AstOutput, TsInterface, TsTypeAlias, TsProperty, JsDocTag, TsEnum, TsEnumMember, TsConstant } from '../types/index.js';
import { createOpenBagProperty } from '../types/index.js';

/**
 * Parser options.
 */
export interface ParserOptions {
  readonly includeInternalTypes?: boolean;
}

/**
 * Parses TypeScript schema content and extracts type information.
 */
export function parseSchema(content: string, options: ParserOptions = {}): AstOutput {
  const project = new Project({
    useInMemoryFileSystem: true,
    compilerOptions: {
      strict: true,
      skipLibCheck: true,
    },
  });

  const sourceFile = project.createSourceFile('schema.ts', content);

  const interfaces = extractInterfaces(sourceFile, options);
  const typeAliases = extractTypeAliases(sourceFile, options);
  const enums = extractEnums(sourceFile, options);
  const constants = extractConstants(sourceFile);

  return {
    interfaces,
    typeAliases,
    enums,
    constants,
  };
}

/**
 * Extracts all interface declarations from a source file.
 */
function extractInterfaces(sourceFile: SourceFile, options: ParserOptions): TsInterface[] {
  const interfaces: TsInterface[] = [];

  for (const iface of sourceFile.getInterfaces()) {
    const tags = extractJsDocTags(iface);

    // Skip internal types unless explicitly included
    if (!options.includeInternalTypes && hasInternalTag(tags)) {
      continue;
    }

    interfaces.push({
      name: iface.getName(),
      description: getJsDocDescription(iface),
      extends: iface.getExtends().map((e) => {
        // Extract just the base type name without generics
        const text = e.getText();
        const genericStart = text.indexOf('<');
        return genericStart > 0 ? text.slice(0, genericStart) : text;
      }),
      properties: extractProperties(iface),
      tags,
    });
  }

  return interfaces;
}

/**
 * Extracts all type alias declarations from a source file.
 */
function extractTypeAliases(sourceFile: SourceFile, options: ParserOptions): TsTypeAlias[] {
  const typeAliases: TsTypeAlias[] = [];

  for (const alias of sourceFile.getTypeAliases()) {
    const tags = extractJsDocTags(alias);

    // Skip internal types unless explicitly included
    if (!options.includeInternalTypes && hasInternalTag(tags)) {
      continue;
    }

    // Use getTypeNode().getText() to get the source text (preserves union syntax)
    // instead of getType().getText() which returns resolved/imported types
    const typeNode = alias.getTypeNode();
    const typeText = typeNode ? typeNode.getText() : alias.getType().getText();

    typeAliases.push({
      name: alias.getName(),
      type: typeText,
      description: getJsDocDescription(alias),
      tags,
    });
  }

  return typeAliases;
}

/**
 * Extracts all enum declarations from a source file.
 */
function extractEnums(sourceFile: SourceFile, options: ParserOptions): TsEnum[] {
  const enums: TsEnum[] = [];

  for (const enumDecl of sourceFile.getEnums()) {
    const tags = extractJsDocTags(enumDecl);

    // Skip internal types unless explicitly included
    if (!options.includeInternalTypes && hasInternalTag(tags)) {
      continue;
    }

    const members: TsEnumMember[] = enumDecl.getMembers().map((member) => ({
      name: member.getName(),
      value: member.getValue() ?? member.getName(),
    }));

    enums.push({
      name: enumDecl.getName(),
      members,
      description: getJsDocDescription(enumDecl),
      tags,
    });
  }

  return enums;
}

/**
 * Extracts properties from an interface declaration.
 * Uses getTypeNode().getText() to get source type text (preserves type alias names)
 * instead of getType().getText() which returns resolved/imported types.
 */
function extractProperties(iface: InterfaceDeclaration): TsProperty[] {
  const properties: TsProperty[] = iface.getProperties().map((prop) => {
    // Use getTypeNode() to get the source text, preserving type alias names
    const typeNode = prop.getTypeNode();
    const typeText = typeNode ? typeNode.getText() : prop.getType().getText();

    return {
      name: getPropertyWireName(prop),
      type: typeText,
      isOptional: prop.hasQuestionToken(),
      description: getPropertyDescription(prop),
      tags: extractPropertyJsDocTags(prop),
      isReadonly: prop.isReadonly(),
    };
  });

  const openBag = extractOpenBag(iface, properties.length);
  if (openBag) {
    properties.push(openBag);
  }

  return properties;
}

/** Returns the exact runtime key for a property, without TypeScript source quotes. */
function getPropertyWireName(prop: ReturnType<InterfaceDeclaration['getProperties']>[0]): string {
  const nameNode = prop.getNameNode();
  if (Node.isStringLiteral(nameNode) || Node.isNoSubstitutionTemplateLiteral(nameNode)) {
    return nameNode.getLiteralValue();
  }

  return prop.getName();
}

/**
 * Builds the catch-all property for an interface declaring an index signature.
 *
 * `getProperties()` returns only PropertySignature members, so an index
 * signature like `[key: string]: unknown` is invisible to it. Without this the
 * schema's "extra keys are allowed here" is transcribed as a closed DTO and
 * every unmodelled key is dropped on the way in.
 *
 * Interfaces that are nothing but an index signature (`{ [key: string]: T }`)
 * are plain maps, not open objects — those are already modelled correctly as
 * `array<string, T>` and must not grow a bag.
 */
function extractOpenBag(iface: InterfaceDeclaration, namedPropertyCount: number): TsProperty | null {
  // The string index may be declared directly or inherited through a type
  // alias such as `MetaObject = Record<string, unknown>`. TypeScript exposes
  // both through the resolved interface type even though the latter has no
  // InterfaceDeclaration index-signature node of its own.
  if (iface.getIndexSignatures().length === 0 && !iface.getType().getStringIndexType()) {
    return null;
  }

  const isPlainMap = namedPropertyCount === 0 && iface.getExtends().length === 0;
  if (isPlainMap) {
    const indexSignature = iface.getIndexSignatures()[0];
    const valueTypeNode = indexSignature?.getReturnTypeNode();
    const valueType = valueTypeNode ? valueTypeNode.getText() : indexSignature?.getReturnType().getText();

    return createOpenBagProperty(valueType ?? 'unknown', true);
  }

  return createOpenBagProperty();
}

/**
 * Extracts JSDoc tags from a declaration.
 */
function extractJsDocTags(
  node: InterfaceDeclaration | TypeAliasDeclaration | EnumDeclaration
): JsDocTag[] {
  const tags: JsDocTag[] = [];

  for (const jsDoc of node.getJsDocs()) {
    for (const tag of jsDoc.getTags()) {
      const comment = tag.getComment();
      tags.push({
        tagName: tag.getTagName(),
        text: typeof comment === 'string' ? comment : comment?.map((part) => part?.getText() ?? '').join(''),
      });
    }
  }

  return tags;
}

/**
 * Gets the JSDoc description from a declaration.
 */
function getJsDocDescription(
  node: InterfaceDeclaration | TypeAliasDeclaration | EnumDeclaration
): string | undefined {
  const jsDocs = node.getJsDocs();
  if (jsDocs.length === 0) {
    return undefined;
  }

  const description = jsDocs[0]?.getDescription();
  return description?.trim() || undefined;
}

/**
 * Gets the description from a property's JSDoc.
 */
function getPropertyDescription(prop: ReturnType<InterfaceDeclaration['getProperties']>[0]): string | undefined {
  const jsDocs = prop.getJsDocs();
  if (jsDocs.length === 0) {
    return undefined;
  }

  return jsDocs[0]?.getDescription()?.trim() || undefined;
}

/** Extracts JSDoc tags attached to a property signature. */
function extractPropertyJsDocTags(prop: ReturnType<InterfaceDeclaration['getProperties']>[0]): JsDocTag[] {
  const tags: JsDocTag[] = [];

  for (const jsDoc of prop.getJsDocs()) {
    for (const tag of jsDoc.getTags()) {
      const comment = tag.getComment();
      tags.push({
        tagName: tag.getTagName(),
        text: typeof comment === 'string' ? comment : comment?.map((part) => part?.getText() ?? '').join(''),
      });
    }
  }

  return tags;
}

/**
 * Checks if tags include @internal.
 */
function hasInternalTag(tags: JsDocTag[]): boolean {
  return tags.some((tag) => tag.tagName === 'internal');
}

/**
 * Gets the @category tag value if present.
 */
export function getCategoryTag(tags: readonly JsDocTag[]): string | undefined {
  const categoryTag = tags.find((tag) => tag.tagName === 'category');
  return categoryTag?.text;
}

/**
 * Extracts exported constants from a source file.
 * Looks for patterns like:
 * - export const CONSTANT_NAME = 'value';  (string)
 * - export const CONSTANT_NAME = 123;      (number)
 * - export const CONSTANT_NAME = -32700;   (negative number)
 */
function extractConstants(sourceFile: SourceFile): TsConstant[] {
  const constants: TsConstant[] = [];

  for (const statement of sourceFile.getVariableStatements()) {
    // Only process exported const declarations
    if (!statement.isExported()) {
      continue;
    }
    if (statement.getDeclarationKind() !== VariableDeclarationKind.Const) {
      continue;
    }

    for (const declaration of statement.getDeclarations()) {
      const initializer = declaration.getInitializer();
      if (!initializer) {
        continue;
      }

      const initText = initializer.getText();
      const description = getConstantDescription(statement);

      // Check for string literals
      if ((initText.startsWith("'") && initText.endsWith("'")) ||
          (initText.startsWith('"') && initText.endsWith('"'))) {
        // Remove quotes to get the actual value
        const value = initText.slice(1, -1);
        constants.push({
          name: declaration.getName(),
          value,
          valueType: 'string',
          description,
        });
        continue;
      }

      // Check for numeric literals (including negative numbers)
      const numericMatch = initText.match(/^-?\d+$/);
      if (numericMatch) {
        constants.push({
          name: declaration.getName(),
          value: parseInt(initText, 10),
          valueType: 'number',
          description,
        });
      }
    }
  }

  return constants;
}

/**
 * Gets the JSDoc description from a variable statement.
 */
function getConstantDescription(statement: ReturnType<SourceFile['getVariableStatements']>[0]): string | undefined {
  const jsDocs = statement.getJsDocs();
  if (jsDocs.length === 0) {
    return undefined;
  }
  return jsDocs[0]?.getDescription()?.trim() || undefined;
}

/**
 * Parses a TypeScript file from disk.
 */
export async function parseSchemaFile(filePath: string, options: ParserOptions = {}): Promise<AstOutput> {
  const project = new Project({
    compilerOptions: {
      strict: true,
      skipLibCheck: true,
    },
  });

  const sourceFile = project.addSourceFileAtPath(filePath);

  const interfaces = extractInterfaces(sourceFile, options);
  const typeAliases = extractTypeAliases(sourceFile, options);
  const enums = extractEnums(sourceFile, options);
  const constants = extractConstants(sourceFile);

  return {
    interfaces,
    typeAliases,
    enums,
    constants,
  };
}

/**
 * Resolves inheritance by collecting all properties including inherited ones.
 */
export function resolveInheritance(
  interfaceName: string,
  interfaces: readonly TsInterface[],
  visited: Set<string> = new Set()
): TsProperty[] {
  if (visited.has(interfaceName)) {
    return []; // Prevent circular inheritance
  }
  visited.add(interfaceName);

  const iface = interfaces.find((i) => i.name === interfaceName);
  if (!iface) {
    return [];
  }

  const allProperties: TsProperty[] = [];

  // First, collect properties from parent interfaces (with deduplication)
  for (const parentName of iface.extends) {
    const parentProps = resolveInheritance(parentName, interfaces, visited);
    for (const prop of parentProps) {
      const existingIndex = allProperties.findIndex((p) => p.name === prop.name);
      if (existingIndex >= 0) {
        // Later parent overrides earlier parent
        allProperties[existingIndex] = prop;
      } else {
        allProperties.push(prop);
      }
    }
  }

  // Then add own properties (can override parent properties)
  for (const prop of iface.properties) {
    const existingIndex = allProperties.findIndex((p) => p.name === prop.name);
    if (existingIndex >= 0) {
      allProperties[existingIndex] = prop; // Override
    } else {
      allProperties.push(prop);
    }
  }

  return allProperties;
}

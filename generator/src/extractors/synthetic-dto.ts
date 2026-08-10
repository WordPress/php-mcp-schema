/**
 * MCP PHP Schema Generator - Synthetic DTO Extractor
 *
 * Extracts inline object types from TypeScript interfaces and creates
 * synthetic interfaces for PHP DTO generation.
 */

import type { TsInterface, TsProperty } from '../types/index.js';
import { createOpenBagProperty } from '../types/index.js';

/**
 * Extracted inline object type.
 */
export interface InlineObjectType {
  readonly name: string;
  readonly parentName: string;
  readonly propertyName: string;
  readonly properties: TsProperty[];
  readonly description?: string;
  readonly depth: number;
}

/**
 * Result of synthetic DTO extraction.
 */
export interface SyntheticExtractionResult {
  readonly interfaces: TsInterface[];
  readonly propertyTypeMap: Map<string, string>; // "Parent.property" -> "SyntheticTypeName"
}

/**
 * Types the schema treats as open without saying so in TypeScript.
 *
 * `schema.json` for 2025-11-25 describes `inputSchema` and `outputSchema`
 * without `additionalProperties: false`, so extra JSON Schema keywords are
 * valid on the wire today. `schema.ts` types them as plain inline objects,
 * which is lossy: TypeScript does not reject extra properties at runtime, so
 * nothing breaks upstream and the omission went unnoticed. Transcribed
 * literally it becomes a closed PHP constructor that deletes them.
 *
 * The MCP draft closes the gap — both fields gain `[key: string]: unknown` and
 * the doc comment names `$ref`, `$defs` and `$anchor` explicitly. Once the
 * generator targets a revision carrying those index signatures, the normal
 * detection path covers these two and this list can be deleted.
 */
const UNDECLARED_OPEN_TYPES: ReadonlySet<string> = new Set([
  'ToolInputSchema',
  'ToolOutputSchema',
]);

/**
 * Extracts synthetic DTOs from inline object types in interfaces.
 */
export class SyntheticDtoExtractor {
  private readonly syntheticInterfaces: TsInterface[] = [];
  private readonly propertyTypeMap = new Map<string, string>();
  private readonly processedTypes = new Set<string>();
  /**
   * Tracks generated synthetic names per parent to detect collisions.
   * Map: parentName -> Map<syntheticName -> originalPropertyName>
   */
  private readonly generatedNamesPerParent = new Map<string, Map<string, string>>();

  /**
   * Extracts synthetic DTOs from all interfaces.
   */
  extract(interfaces: readonly TsInterface[]): SyntheticExtractionResult {
    for (const iface of interfaces) {
      this.extractFromInterface(iface, iface.name, 0);
    }

    return {
      interfaces: this.syntheticInterfaces,
      propertyTypeMap: this.propertyTypeMap,
    };
  }

  /**
   * Extracts inline types from an interface's properties.
   */
  private extractFromInterface(iface: TsInterface, baseName: string, depth: number): void {
    for (const prop of iface.properties) {
      this.extractFromProperty(prop, baseName, depth);
    }
  }

  /**
   * Extracts inline types from a property.
   */
  private extractFromProperty(prop: TsProperty, parentName: string, depth: number): void {
    let type = prop.type.trim();

    // Handle nullable types: "{ ... } | undefined" -> "{ ... }"
    const nullableMatch = type.match(/^(\{[\s\S]*\})\s*\|\s*undefined$/);
    if (nullableMatch) {
      type = nullableMatch[1]!;
    }

    // Skip if not an inline object type
    if (!this.isExtractableInlineType(type)) {
      return;
    }

    // Skip index signatures like { [key: string]: T }
    if (this.isIndexSignature(type)) {
      return;
    }

    // Generate synthetic type name
    const syntheticName = this.generateSyntheticName(parentName, prop.name);

    // Skip if already processed
    const typeKey = `${parentName}.${prop.name}`;
    if (this.processedTypes.has(typeKey)) {
      return;
    }
    this.processedTypes.add(typeKey);

    // Parse inline object properties
    const inlineProperties = this.parseInlineObjectProperties(type);

    // Types the schema permits extra keys on without declaring an index
    // signature still need a bag; detection alone would miss them.
    const alreadyOpen = inlineProperties.some((p) => p.isOpenBag);
    if (!alreadyOpen && UNDECLARED_OPEN_TYPES.has(syntheticName)) {
      inlineProperties.push(createOpenBagProperty());
    }

    // Create synthetic interface
    const syntheticInterface: TsInterface = {
      name: syntheticName,
      description: prop.description,
      extends: [],
      properties: inlineProperties,
      tags: [],
      isSynthetic: true,
      syntheticParent: parentName,
    };

    this.syntheticInterfaces.push(syntheticInterface);
    this.propertyTypeMap.set(typeKey, syntheticName);

    // Recursively extract nested inline types
    this.extractFromInterface(syntheticInterface, syntheticName, depth + 1);
  }

  /**
   * Checks if a type is an extractable inline object (not an index signature).
   */
  private isExtractableInlineType(type: string): boolean {
    if (!type.startsWith('{')) {
      return false;
    }

    // Must be balanced braces
    let depth = 0;
    for (const char of type) {
      if (char === '{') depth++;
      if (char === '}') depth--;
    }

    return depth === 0 && type.endsWith('}');
  }

  /**
   * Checks if a type is an index signature { [key: T]: V }.
   */
  private isIndexSignature(type: string): boolean {
    return /^\{\s*\[/.test(type);
  }

  /**
   * Generates a synthetic type name from parent and property names.
   *
   * Converts property names to PascalCase, handling:
   * - Leading underscores: `_meta` → `Meta`
   * - Underscore separators: `some_name` → `SomeName`
   * - Already PascalCase: `SomeName` → `SomeName`
   *
   * Detects and throws on naming collisions (e.g., `_meta` and `meta` both becoming `Meta`).
   *
   * @throws Error if generated name collides with another property's synthetic name
   */
  private generateSyntheticName(parentName: string, propertyName: string): string {
    const pascalProperty = this.toPascalCase(propertyName);
    const syntheticName = `${parentName}${pascalProperty}`;

    // Check for collision with previously generated names for this parent
    let parentNames = this.generatedNamesPerParent.get(parentName);
    if (!parentNames) {
      parentNames = new Map<string, string>();
      this.generatedNamesPerParent.set(parentName, parentNames);
    }

    const existingPropertyName = parentNames.get(syntheticName);
    if (existingPropertyName !== undefined && existingPropertyName !== propertyName) {
      throw new Error(
        `Synthetic name collision detected in '${parentName}':\n` +
        `  Property '${existingPropertyName}' → '${syntheticName}'\n` +
        `  Property '${propertyName}' → '${syntheticName}'\n` +
        `Both properties would generate the same synthetic class name after PascalCase conversion.\n` +
        `Please rename one of the properties to avoid collision.`
      );
    }

    // Register this synthetic name
    parentNames.set(syntheticName, propertyName);

    return syntheticName;
  }

  /**
   * Converts a string to PascalCase, handling underscores and edge cases.
   *
   * Examples:
   * - `_meta` → `Meta`
   * - `some_name` → `SomeName`
   * - `someName` → `SomeName`
   * - `__private` → `Private`
   */
  private toPascalCase(str: string): string {
    // Strip leading underscores and split by underscores
    const cleanStr = str.replace(/^_+/, '');

    // Split by underscores and capitalize each part
    const parts = cleanStr.split('_').filter(part => part.length > 0);

    return parts
      .map(part => part.charAt(0).toUpperCase() + part.slice(1))
      .join('');
  }

  /**
   * Parses properties from an inline object type string.
   * This is a simplified parser for common patterns.
   */
  private parseInlineObjectProperties(type: string): TsProperty[] {
    const properties: TsProperty[] = [];

    // Remove outer braces and trim
    let content = type.slice(1, -1).trim();

    // Split by semicolons while respecting nested braces
    const propStrings = this.splitPropertyStrings(content);

    let hasIndexSignature = false;

    for (const propStr of propStrings) {
      // An index signature member means the schema allows keys beyond those
      // named here. parsePropertyString() cannot match it, so without this it
      // is dropped and the inline type is transcribed as closed.
      if (this.isIndexSignatureMember(propStr.trim())) {
        hasIndexSignature = true;
        continue;
      }

      const prop = this.parsePropertyString(propStr.trim());
      if (prop) {
        properties.push(prop);
      }
    }

    // A member-less index signature is a plain map and is handled upstream by
    // isIndexSignature(); reaching here with no named properties means the type
    // is empty, and an empty object needs no bag.
    if (hasIndexSignature && properties.length > 0) {
      properties.push(createOpenBagProperty());
    }

    return properties;
  }

  /**
   * Checks whether a member of an inline object type is an index signature,
   * e.g. `[key: string]: unknown`. Any leading JSDoc is stripped first.
   */
  private isIndexSignatureMember(propStr: string): boolean {
    const withoutJsDoc = propStr.replace(/^\/\*\*[\s\S]*?\*\/\s*/, '');

    return /^\[\s*\w+\s*:\s*(?:string|number)\s*\]\s*:/.test(withoutJsDoc);
  }

  /**
   * Splits property strings while respecting nested structures.
   */
  private splitPropertyStrings(content: string): string[] {
    const properties: string[] = [];
    let current = '';
    let depth = 0;
    let inString = false;
    let stringChar = '';

    for (let i = 0; i < content.length; i++) {
      const char = content[i]!;
      const prevChar = i > 0 ? content[i - 1] : '';

      // Handle string literals
      if ((char === '"' || char === "'") && prevChar !== '\\') {
        if (!inString) {
          inString = true;
          stringChar = char;
        } else if (char === stringChar) {
          inString = false;
        }
      }

      if (!inString) {
        if (char === '{' || char === '[' || char === '(') depth++;
        if (char === '}' || char === ']' || char === ')') depth--;

        if (char === ';' && depth === 0) {
          if (current.trim()) {
            properties.push(current.trim());
          }
          current = '';
          continue;
        }
      }

      current += char;
    }

    // Add last property (may not have trailing semicolon)
    if (current.trim()) {
      properties.push(current.trim());
    }

    return properties;
  }

  /**
   * Parses a single property string like "name?: string" or "$schema?: string".
   * Also handles JSDoc comments that may precede the property definition.
   */
  private parsePropertyString(propStr: string): TsProperty | null {
    if (!propStr) {
      return null;
    }

    // Extract JSDoc description if present
    let description: string | undefined;
    let cleanPropStr = propStr;

    // Check for JSDoc comment: /** ... */
    const jsDocMatch = propStr.match(/^\/\*\*[\s\S]*?\*\/\s*/);
    if (jsDocMatch) {
      const jsDocComment = jsDocMatch[0];
      cleanPropStr = propStr.slice(jsDocComment.length).trim();

      // Extract the description text from the JSDoc
      // Remove /** and */ and clean up the * prefixes on each line
      const commentContent = jsDocComment
        .replace(/^\/\*\*\s*/, '')  // Remove opening /**
        .replace(/\*\/\s*$/, '')    // Remove closing */
        .split('\n')
        .map(line => line.replace(/^\s*\*\s?/, '').trimEnd()) // Remove * prefix and trailing whitespace
        .filter(line => line.length > 0)
        .join(' ')
        .trim();

      if (commentContent) {
        description = commentContent;
      }
    }

    // Match pattern: name?: Type (name can start with $ like $schema)
    const match = cleanPropStr.match(/^(\$?\w+)(\?)?:\s*(.+)$/);
    if (!match) {
      return null;
    }

    const [, name, optional, typeStr] = match;
    if (!name || !typeStr) {
      return null;
    }

    return {
      name,
      type: typeStr.trim(),
      isOptional: optional === '?',
      isReadonly: false,
      description,
    };
  }
}

/**
 * Updates interfaces with synthetic type references.
 */
export function updateInterfacesWithSyntheticTypes(
  interfaces: TsInterface[],
  propertyTypeMap: Map<string, string>
): TsInterface[] {
  return interfaces.map((iface) => ({
    ...iface,
    properties: iface.properties.map((prop) => {
      const key = `${iface.name}.${prop.name}`;
      const syntheticType = propertyTypeMap.get(key);

      if (syntheticType) {
        return {
          ...prop,
          type: prop.isOptional ? `${syntheticType} | undefined` : syntheticType,
          originalInlineType: prop.type,
        };
      }

      return prop;
    }),
  }));
}

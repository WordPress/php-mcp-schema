/**
 * MCP PHP Schema Generator - Version Tracker
 *
 * Tracks when definitions and properties were introduced and last modified
 * by comparing multiple MCP schema versions from GitHub.
 */

import { readFileSync, existsSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// ============================================================================
// Types
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
 * Change information detected between two schema versions.
 */
export interface ChangeInfo {
  /** Properties added in this version */
  readonly addedProperties: readonly string[];
  /** Properties removed in this version */
  readonly removedProperties: readonly string[];
  /** Properties with modified types */
  readonly modifiedProperties: readonly string[];
  /** Union members added */
  readonly addedUnionMembers: readonly string[];
  /** Union members removed */
  readonly removedUnionMembers: readonly string[];
  /** Whether the description changed */
  readonly descriptionChanged: boolean;
}

/**
 * Raw JSON Schema definition structure.
 */
interface JsonSchemaDefinition {
  readonly description?: string;
  readonly type?: string;
  readonly properties?: Record<string, JsonSchemaProperty>;
  readonly required?: readonly string[];
  readonly anyOf?: readonly JsonSchemaRef[];
  readonly oneOf?: readonly JsonSchemaRef[];
  readonly allOf?: readonly JsonSchemaRef[];
  readonly $ref?: string;
}

interface JsonSchemaProperty {
  readonly type?: string;
  readonly $ref?: string;
  readonly description?: string;
  readonly items?: JsonSchemaProperty;
  readonly anyOf?: readonly JsonSchemaRef[];
  readonly oneOf?: readonly JsonSchemaRef[];
  readonly const?: string | number | boolean;
}

interface JsonSchemaRef {
  readonly $ref?: string;
  readonly type?: string;
  readonly items?: JsonSchemaProperty;
}

/**
 * Raw JSON Schema structure.
 */
interface JsonSchema {
  readonly $defs?: Record<string, JsonSchemaDefinition>;
  readonly definitions?: Record<string, JsonSchemaDefinition>;
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
// Constants
// ============================================================================

/**
 * GitHub raw content URL for MCP specification schemas.
 */
const GITHUB_RAW_URL = 'https://raw.githubusercontent.com/modelcontextprotocol/specification/main/schema';

// ============================================================================
// Version Configuration
// ============================================================================

/**
 * Interface for the versions config file structure.
 */
interface VersionsConfig {
  readonly versions: readonly string[];
}

/**
 * Gets the path to the versions config file.
 */
function getVersionsConfigPath(): string {
  // In compiled code, __dirname is dist/version-tracker, so go up two levels to get to generator/
  return resolve(__dirname, '../../config/versions.json');
}

/**
 * Loads available schema versions from the config file.
 * @returns Array of version strings in chronological order
 * @throws Error if config file doesn't exist or is invalid
 */
export function loadSchemaVersions(): readonly string[] {
  const configPath = getVersionsConfigPath();

  if (!existsSync(configPath)) {
    throw new Error(`Versions config file not found: ${configPath}`);
  }

  try {
    const content = readFileSync(configPath, 'utf-8');
    const config = JSON.parse(content) as VersionsConfig;

    if (!Array.isArray(config.versions) || config.versions.length === 0) {
      throw new Error('Versions config must contain a non-empty "versions" array');
    }

    return config.versions;
  } catch (error) {
    if (error instanceof SyntaxError) {
      throw new Error(`Invalid JSON in versions config: ${configPath}`);
    }
    throw error;
  }
}

/**
 * Filters versions to only include those up to and including the target version.
 * @param allVersions - All available versions in chronological order
 * @param targetVersion - The version being generated
 * @returns Filtered versions array
 */
export function getVersionsUpTo(allVersions: readonly string[], targetVersion: string): readonly string[] {
  const targetIndex = allVersions.indexOf(targetVersion);

  if (targetIndex === -1) {
    // Target version not found - return all versions (graceful fallback)
    return allVersions;
  }

  return allVersions.slice(0, targetIndex + 1);
}

/**
 * JSON-RPC protocol properties to exclude from change tracking.
 * These are inherited from base types and don't represent MCP-specific evolution.
 */
const JSONRPC_PROTOCOL_PROPERTIES = ['id', 'jsonrpc', 'method'];

// ============================================================================
// Schema Fetching
// ============================================================================

/**
 * Fetches a specific schema version from GitHub.
 */
async function fetchSchemaVersion(version: string): Promise<JsonSchema> {
  const url = `${GITHUB_RAW_URL}/${version}/schema.json`;
  const response = await fetch(url);

  if (!response.ok) {
    throw new Error(`Failed to fetch schema version ${version}: ${response.status} ${response.statusText}`);
  }

  return response.json() as Promise<JsonSchema>;
}

/**
 * Fetches specified schema versions from GitHub in parallel.
 * @param versions - Array of version strings to fetch
 */
async function fetchSchemaVersions(versions: readonly string[]): Promise<Map<string, JsonSchema>> {
  const results = await Promise.all(
    versions.map(async (version) => {
      const schema = await fetchSchemaVersion(version);
      return [version, schema] as const;
    })
  );

  return new Map(results);
}

// ============================================================================
// Schema Comparison
// ============================================================================

/**
 * Gets definitions from a schema (handles both $defs and definitions).
 */
function getDefinitions(schema: JsonSchema): Record<string, JsonSchemaDefinition> {
  return schema.$defs ?? schema.definitions ?? {};
}

/**
 * Gets property names from a definition.
 */
function getPropertyNames(definition: JsonSchemaDefinition): string[] {
  return Object.keys(definition.properties ?? {});
}

/**
 * Extracts union member names from anyOf/oneOf.
 */
function extractUnionMembers(schema: JsonSchemaDefinition | JsonSchemaProperty): string[] {
  const members: string[] = [];
  const union = schema.anyOf ?? schema.oneOf ?? [];

  for (const member of union) {
    if (member.$ref) {
      // Extract type name from $ref like "#/$defs/TextContent"
      const parts = member.$ref.split('/');
      const name = parts[parts.length - 1];
      if (name) {
        members.push(name);
      }
    } else if (member.type) {
      if (member.type === 'array' && member.items?.$ref) {
        const parts = member.items.$ref.split('/');
        const name = parts[parts.length - 1];
        if (name) {
          members.push(`array<${name}>`);
        }
      } else {
        members.push(member.type);
      }
    }
  }

  return members.sort();
}

/**
 * Normalizes items definition for comparison.
 */
function normalizeItems(items: JsonSchemaProperty | undefined): string {
  if (!items) {
    return '';
  }
  // Sort keys for consistent comparison
  const sorted = Object.keys(items).sort().reduce((acc, key) => {
    acc[key] = items[key as keyof JsonSchemaProperty];
    return acc;
  }, {} as Record<string, unknown>);
  return JSON.stringify(sorted);
}

/**
 * Checks if a property definition has changed significantly.
 */
function hasPropertyChanged(
  oldProp: JsonSchemaProperty | undefined,
  newProp: JsonSchemaProperty | undefined
): boolean {
  if (!oldProp || !newProp) {
    return oldProp !== newProp;
  }

  // Check type change
  if (oldProp.type !== newProp.type) {
    return true;
  }

  // Check $ref change
  if (oldProp.$ref !== newProp.$ref) {
    return true;
  }

  // Check anyOf/oneOf changes
  const oldUnion = extractUnionMembers(oldProp);
  const newUnion = extractUnionMembers(newProp);
  if (JSON.stringify(oldUnion) !== JSON.stringify(newUnion)) {
    return true;
  }

  // Check items change (for arrays)
  return normalizeItems(oldProp.items) !== normalizeItems(newProp.items);


}

/**
 * Compares two schema definitions and returns detected changes.
 */
function compareDefinitions(
  oldDef: JsonSchemaDefinition | undefined,
  newDef: JsonSchemaDefinition | undefined
): ChangeInfo {
  // New definition - no changes to report
  if (!oldDef) {
    return {
      addedProperties: [],
      removedProperties: [],
      modifiedProperties: [],
      addedUnionMembers: [],
      removedUnionMembers: [],
      descriptionChanged: false,
    };
  }

  // Removed definition - shouldn't happen in practice
  if (!newDef) {
    return {
      addedProperties: [],
      removedProperties: [],
      modifiedProperties: [],
      addedUnionMembers: [],
      removedUnionMembers: [],
      descriptionChanged: false,
    };
  }

  const oldProps = oldDef.properties ?? {};
  const newProps = newDef.properties ?? {};

  const oldPropNames = Object.keys(oldProps);
  const newPropNames = Object.keys(newProps);

  // Filter out JSON-RPC protocol properties
  const filterProtocolProps = (names: string[]): string[] =>
    names.filter((n) => !JSONRPC_PROTOCOL_PROPERTIES.includes(n));

  const addedProperties = filterProtocolProps(
    newPropNames.filter((n) => !oldPropNames.includes(n))
  );

  const removedProperties = filterProtocolProps(
    oldPropNames.filter((n) => !newPropNames.includes(n))
  );

  // Check for modified properties (type changes)
  const commonProps = oldPropNames.filter(
    (n) => newPropNames.includes(n) && !JSONRPC_PROTOCOL_PROPERTIES.includes(n)
  );

  const modifiedProperties = commonProps.filter((propName) =>
    hasPropertyChanged(oldProps[propName], newProps[propName])
  );

  // Compare union members
  const oldUnionMembers = extractUnionMembers(oldDef);
  const newUnionMembers = extractUnionMembers(newDef);

  const addedUnionMembers = newUnionMembers.filter((m) => !oldUnionMembers.includes(m));
  const removedUnionMembers = oldUnionMembers.filter((m) => !newUnionMembers.includes(m));

  // Check description change
  const normalizeDesc = (s: string | undefined): string =>
    (s ?? '').trim().replace(/\s+/g, ' ');
  const descriptionChanged = normalizeDesc(oldDef.description) !== normalizeDesc(newDef.description);

  return {
    addedProperties,
    removedProperties,
    modifiedProperties,
    addedUnionMembers,
    removedUnionMembers,
    descriptionChanged,
  };
}

/**
 * Checks if a ChangeInfo indicates any changes.
 */
function hasChanges(info: ChangeInfo): boolean {
  return (
    info.addedProperties.length > 0 ||
    info.removedProperties.length > 0 ||
    info.modifiedProperties.length > 0 ||
    info.addedUnionMembers.length > 0 ||
    info.removedUnionMembers.length > 0
    // Note: description changes alone don't count as structural changes
  );
}

/**
 * Generates a human-readable change summary.
 */
function generateChangeSummary(info: ChangeInfo): string {
  const parts: string[] = [];

  if (info.addedProperties.length > 0) {
    parts.push(`added properties: ${info.addedProperties.join(', ')}`);
  }

  if (info.removedProperties.length > 0) {
    parts.push(`removed properties: ${info.removedProperties.join(', ')}`);
  }

  if (info.modifiedProperties.length > 0) {
    const propWord = info.modifiedProperties.length === 1 ? 'property' : 'properties';
    parts.push(`modified ${propWord}: ${info.modifiedProperties.join(', ')}`);
  }

  if (info.addedUnionMembers.length > 0) {
    parts.push(`added union members: ${info.addedUnionMembers.join(', ')}`);
  }

  if (info.removedUnionMembers.length > 0) {
    parts.push(`removed union members: ${info.removedUnionMembers.join(', ')}`);
  }

  return parts.join('; ');
}

// ============================================================================
// Version Tracker Builder
// ============================================================================

/**
 * Options for building a version tracker.
 */
export interface BuildVersionTrackerOptions {
  /** The target version being generated - only versions up to this will be compared */
  readonly targetVersion: string;
  /** Optional callback for progress updates */
  readonly onProgress?: (message: string) => void;
}

/**
 * Builds a version tracker by comparing schema versions up to and including the target version.
 * @param options - Options including the target version and optional progress callback
 */
export async function buildVersionTracker(
  options: BuildVersionTrackerOptions
): Promise<VersionTracker> {
  const { targetVersion, onProgress } = options;
  const progress = onProgress ?? ((): void => {});

  // Load all available versions from config
  progress('Loading schema versions from config...');
  const allVersions = loadSchemaVersions();

  // Filter to only include versions up to and including the target
  const versionsToCompare = getVersionsUpTo(allVersions, targetVersion);
  progress(`Comparing ${versionsToCompare.length} version(s) up to ${targetVersion}...`);

  progress('Fetching schema versions from GitHub...');
  const schemas = await fetchSchemaVersions(versionsToCompare);

  progress('Comparing schema versions...');

  // Maps to store version information
  const definitionIntroducedIn = new Map<string, string>();
  const definitionLastModified = new Map<string, string>();
  const definitionChangeSummary = new Map<string, string>();
  const propertyIntroducedIn = new Map<string, Map<string, string>>();

  let previousSchema: JsonSchema | undefined;

  for (const version of versionsToCompare) {
    const currentSchema = schemas.get(version);
    if (!currentSchema) {
      continue;
    }

    const currentDefs = getDefinitions(currentSchema);
    const previousDefs = previousSchema ? getDefinitions(previousSchema) : {};

    for (const defName of Object.keys(currentDefs)) {
      const currentDef = currentDefs[defName];
      const previousDef = previousDefs[defName];

      // Track definition introduction
      if (!definitionIntroducedIn.has(defName)) {
        definitionIntroducedIn.set(defName, version);
        definitionLastModified.set(defName, version);
      } else if (previousDef) {
        // Definition exists - check for changes
        const changeInfo = compareDefinitions(previousDef, currentDef);

        if (hasChanges(changeInfo)) {
          definitionLastModified.set(defName, version);
          definitionChangeSummary.set(defName, generateChangeSummary(changeInfo));
        }
      }

      // Track property introductions
      if (!propertyIntroducedIn.has(defName)) {
        propertyIntroducedIn.set(defName, new Map());
      }
      const propVersions = propertyIntroducedIn.get(defName)!;

      const propNames = getPropertyNames(currentDef!);
      for (const propName of propNames) {
        if (!propVersions.has(propName)) {
          propVersions.set(propName, version);
        }
      }
    }

    previousSchema = currentSchema;
  }

  progress(`Version tracking complete: ${definitionIntroducedIn.size} definitions tracked`);

  // Build the tracker object
  return {
    getDefinitionVersion(name: string): VersionInfo | undefined {
      const introducedIn = definitionIntroducedIn.get(name);
      if (!introducedIn) {
        return undefined;
      }

      const lastModified = definitionLastModified.get(name);
      const changeSummary = definitionChangeSummary.get(name);

      return {
        introducedIn,
        lastModified: lastModified !== introducedIn ? lastModified : undefined,
        changeSummary,
      };
    },

    getPropertyVersion(definitionName: string, propertyName: string): PropertyVersionInfo | undefined {
      const propVersions = propertyIntroducedIn.get(definitionName);
      if (!propVersions) {
        return undefined;
      }

      const introducedIn = propVersions.get(propertyName);
      if (!introducedIn) {
        return undefined;
      }

      return { introducedIn };
    },

    getPropertyVersions(definitionName: string): ReadonlyMap<string, PropertyVersionInfo> {
      const propVersions = propertyIntroducedIn.get(definitionName);
      if (!propVersions) {
        return new Map();
      }

      const result = new Map<string, PropertyVersionInfo>();
      for (const [propName, introducedIn] of propVersions) {
        result.set(propName, { introducedIn });
      }
      return result;
    },

    wasModified(definitionName: string): boolean {
      const introducedIn = definitionIntroducedIn.get(definitionName);
      const lastModified = definitionLastModified.get(definitionName);
      return introducedIn !== undefined && lastModified !== undefined && introducedIn !== lastModified;
    },
  };
}

/**
 * Creates a no-op version tracker for when version tracking is disabled.
 */
export function createEmptyVersionTracker(): VersionTracker {
  return {
    getDefinitionVersion: () => undefined,
    getPropertyVersion: () => undefined,
    getPropertyVersions: () => new Map(),
    wasModified: () => false,
  };
}

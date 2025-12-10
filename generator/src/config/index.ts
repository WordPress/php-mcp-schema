/**
 * MCP PHP Schema Generator - Configuration
 *
 * Default configuration and configuration utilities.
 */

import { readFileSync, existsSync, readdirSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';
import type { GeneratorConfig, PhpOutputConfig, SchemaSource } from '../types/index.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

/**
 * Default schema source configuration (version must be provided via config file).
 * Schema is always fetched from the official MCP GitHub repository.
 */
export const DEFAULT_SCHEMA_SOURCE: Omit<SchemaSource, 'version'> = {
  repository: 'modelcontextprotocol/modelcontextprotocol',
  branch: 'main',
  path: 'schema',
};

/**
 * Default PHP output configuration.
 * Note: Generated code targets PHP 7.4 for maximum compatibility.
 */
export const DEFAULT_PHP_OUTPUT: PhpOutputConfig = {
  outputDir: '../src',
  namespace: 'WP\\McpSchema',
  indentation: 'spaces',
  indentSize: 4,
};

/**
 * Creates a generator configuration by merging provided options with defaults.
 * @param options - Configuration options (schema.version is required)
 * @throws Error if schema.version is not provided
 */
export function createConfig(options: Partial<GeneratorConfig> & { schema: { version: string } }): GeneratorConfig {
  if (!options.schema?.version) {
    throw new Error('Schema version is required. Use --config to specify a config file.');
  }

  return {
    schema: {
      ...DEFAULT_SCHEMA_SOURCE,
      ...options.schema,
    } as SchemaSource,
    output: {
      ...DEFAULT_PHP_OUTPUT,
      ...options.output,
    },
    verbose: options.verbose ?? false,
    dryRun: options.dryRun ?? false,
  };
}

/**
 * Validates a generator configuration.
 * @throws Error if configuration is invalid
 */
export function validateConfig(config: GeneratorConfig): void {
  // Validate schema version
  if (!config.schema.version) {
    throw new Error('Schema version is required');
  }

  // Validate indentation
  if (config.output.indentSize < 1 || config.output.indentSize > 8) {
    throw new Error('Indent size must be between 1 and 8');
  }
}

/**
 * Gets the full GitHub URL for the schema.
 */
export function getSchemaGitHubUrl(config: GeneratorConfig): string {
  const { repository, branch, path, version } = config.schema;
  return `https://raw.githubusercontent.com/${repository}/${branch}/${path}/${version}/schema.ts`;
}

/**
 * Gets the output path for a generated file.
 * DTOs are placed directly in the subdomain folder, other types get their own subfolder.
 */
export function getOutputPath(
  config: GeneratorConfig,
  domain: string,
  subdomain: string,
  type: string,
  filename: string
): string {
  const { outputDir } = config.output;
  // DTOs go directly in subdomain folder, other types get their own subfolder
  if (type === 'Dto') {
    return `${outputDir}/${domain}/${subdomain}/${filename}`;
  }
  return `${outputDir}/${domain}/${subdomain}/${type}/${filename}`;
}

/**
 * Gets the path to the config directory.
 */
export function getConfigDir(): string {
  // In compiled code, __dirname is dist/config, so go up two levels to get to generator/
  return resolve(__dirname, '../../config');
}

/**
 * Gets the path to a config file by version.
 */
export function getConfigPath(version: string): string {
  return resolve(getConfigDir(), `${version}.json`);
}

/**
 * Loads configuration from a JSON file.
 * @param filePath - Path to the JSON config file
 * @returns Parsed configuration merged with defaults
 * @throws Error if file doesn't exist, is invalid JSON, or missing version
 */
export function loadConfigFromFile(filePath: string): GeneratorConfig {
  const absolutePath = resolve(filePath);

  if (!existsSync(absolutePath)) {
    throw new Error(`Config file not found: ${absolutePath}`);
  }

  try {
    const content = readFileSync(absolutePath, 'utf-8');
    const fileConfig = JSON.parse(content) as Partial<GeneratorConfig>;

    if (!fileConfig.schema?.version) {
      throw new Error(`Config file missing required "schema.version": ${absolutePath}`);
    }

    return createConfig(fileConfig as Partial<GeneratorConfig> & { schema: { version: string } });
  } catch (error) {
    if (error instanceof SyntaxError) {
      throw new Error(`Invalid JSON in config file: ${absolutePath}`);
    }
    throw error;
  }
}

/**
 * Loads configuration from a version-named config file in the config directory.
 * @param version - Schema version (e.g., "2025-11-25")
 * @returns Parsed configuration merged with defaults
 * @throws Error if file doesn't exist or is invalid JSON
 */
export function loadConfigByVersion(version: string): GeneratorConfig {
  const configPath = getConfigPath(version);
  return loadConfigFromFile(configPath);
}

/**
 * Lists available config versions in the config directory.
 */
export function listConfigVersions(): string[] {
  const configDir = getConfigDir();
  if (!existsSync(configDir)) {
    return [];
  }

  const files = readdirSync(configDir);
  return files
    .filter((f) => f.endsWith('.json'))
    .map((f) => f.replace('.json', ''))
    .sort();
}

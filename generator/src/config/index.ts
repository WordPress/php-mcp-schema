/**
 * MCP PHP Schema Generator - Configuration
 *
 * Default configuration and configuration utilities.
 */

import { readFileSync, existsSync, readdirSync } from 'fs';
import { resolve, dirname, basename } from 'path';
import { fileURLToPath } from 'url';
import type { GeneratorConfig, PhpOutputConfig, SchemaSource, SkillOutputConfig } from '../types/index.js';

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

/** Skill generation is opt-in per revision and always has an explicit destination. */
export const DEFAULT_SKILL_OUTPUT: SkillOutputConfig = {
  enabled: false,
  outputDir: '../skill',
};

/** Converts an MCP revision date to its PHP namespace/directory segment. */
export function getRevisionSegment(version: string): string {
  if (!/^\d{4}-\d{2}-\d{2}$/.test(version)) {
    throw new Error(`Invalid schema revision: ${version}`);
  }

  return `V${version.replace(/-/g, '')}`;
}

/**
 * Creates a generator configuration by merging provided options with defaults.
 * @param options - Configuration options (schema.version is required)
 * @throws Error if schema.version is not provided
 */
export type GeneratorConfigOptions = Omit<
  Partial<GeneratorConfig>,
  'schema' | 'output' | 'skill'
> & {
  readonly schema: Partial<SchemaSource> & { readonly version: string };
  readonly output?: Partial<PhpOutputConfig>;
  readonly skill?: Partial<SkillOutputConfig>;
};

export function createConfig(options: GeneratorConfigOptions): GeneratorConfig {
  if (!options.schema?.version) {
    throw new Error('Schema version is required. Use --config to specify a config file.');
  }

  const revisionSegment = getRevisionSegment(options.schema.version);

  const config: GeneratorConfig = {
    schema: {
      ...DEFAULT_SCHEMA_SOURCE,
      ...options.schema,
    } as SchemaSource,
    output: {
      ...DEFAULT_PHP_OUTPUT,
      outputDir: `../src/${revisionSegment}`,
      namespace: `WP\\McpSchema\\${revisionSegment}`,
      ...options.output,
    },
    skill: {
      ...DEFAULT_SKILL_OUTPUT,
      ...options.skill,
    },
    verbose: options.verbose ?? false,
    dryRun: options.dryRun ?? false,
  };

  validateConfig(config);
  return config;
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

  const expectedTree = getRevisionSegment(config.schema.version);
  if (basename(resolve(config.output.outputDir)) !== expectedTree) {
    throw new Error(
      `Output directory must end in the ${expectedTree} revision tree`
    );
  }

  if (config.skill.enabled && config.skill.outputDir.trim() === '') {
    throw new Error('Skill output directory is required when skill generation is enabled');
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

/** Gets the directory whose JSON files form the shipping revision manifest. */
export function getRevisionsConfigDir(): string {
  return resolve(getConfigDir(), 'revisions');
}

/**
 * Loads and validates the shipping revision manifest.
 *
 * Every file name must match its schema version, and every shipping revision
 * must exist in versions.json so version annotations cannot silently drift.
 */
export function loadShippingRevisionConfigs(): GeneratorConfig[] {
  const revisionsDir = getRevisionsConfigDir();
  if (!existsSync(revisionsDir)) {
    throw new Error(`Shipping revisions directory not found: ${revisionsDir}`);
  }

  const configPaths = readdirSync(revisionsDir)
    .filter((file) => file.endsWith('.json'))
    .sort()
    .map((file) => resolve(revisionsDir, file));

  if (configPaths.length === 0) {
    throw new Error(`No shipping revision configs found in: ${revisionsDir}`);
  }

  const versionsConfig = JSON.parse(
    readFileSync(resolve(getConfigDir(), 'versions.json'), 'utf-8')
  ) as { versions?: unknown };
  if (!Array.isArray(versionsConfig.versions)) {
    throw new Error('versions.json must contain a versions array');
  }
  const history = new Set(
    versionsConfig.versions.filter((version): version is string => typeof version === 'string')
  );

  const seen = new Set<string>();
  return configPaths.map((configPath) => {
    const rawConfig = JSON.parse(readFileSync(configPath, 'utf-8')) as {
      skill?: { enabled?: unknown; outputDir?: unknown };
    };
    const config = loadConfigFromFile(configPath);
    const fileVersion = basename(configPath, '.json');
    const derivedConfig = createConfig({ schema: { version: config.schema.version } });

    if (fileVersion !== config.schema.version) {
      throw new Error(
        `Shipping revision file ${fileVersion}.json declares ${config.schema.version}`
      );
    }
    if (!history.has(config.schema.version)) {
      throw new Error(
        `Shipping revision ${config.schema.version} is absent from versions.json`
      );
    }
    if (seen.has(config.schema.version)) {
      throw new Error(`Duplicate shipping revision: ${config.schema.version}`);
    }
    if (
      config.output.outputDir !== derivedConfig.output.outputDir ||
      config.output.namespace !== derivedConfig.output.namespace
    ) {
      throw new Error(
        `Shipping revision ${config.schema.version} must use its derived namespace and output tree`
      );
    }
    if (
      typeof rawConfig.skill?.enabled !== 'boolean' ||
      typeof rawConfig.skill.outputDir !== 'string'
    ) {
      throw new Error(
        `Shipping revision ${config.schema.version} must declare skill.enabled and skill.outputDir`
      );
    }

    seen.add(config.schema.version);
    return config;
  });
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

    return createConfig(fileConfig as GeneratorConfigOptions);
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
    .filter((f) => /^\d{4}-\d{2}-\d{2}\.json$/.test(f))
    .map((f) => f.replace('.json', ''))
    .sort();
}

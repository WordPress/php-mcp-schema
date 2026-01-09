/**
 * MCP PHP Schema Generator
 *
 * Generates PHP 7.4 DTOs from the Model Context Protocol TypeScript schema.
 *
 * @package @wordpress/php-mcp-schema-generator
 */

import type { GeneratorConfig, GenerationResult, GeneratedFile, GenerationStats, GenerationError, TsInterface, TsTypeAlias, UnionMembershipMap, UnionMembershipInfo, VersionTracker } from './types/index.js';
import { fetchSchema, fetchSchemaFresh } from './fetcher/index.js';
import { parseSchema } from './parser/index.js';
import { DtoGenerator, EnumGenerator, NumericEnumGenerator, ConstantsGenerator, UnionGenerator, FactoryGenerator, BuilderGenerator, ContractGenerator, TypeAliasWrapperGenerator, IntersectionTypeWrapperGenerator, DomainClassifier, createConstantsMap, SkillGenerator } from './generators/index.js';
import { FileWriter, generateAbstractDto, generateAbstractEnum, generateValidatesRequiredFieldsTrait } from './writers/index.js';
import { SyntheticDtoExtractor, updateInterfacesWithSyntheticTypes } from './extractors/index.js';
import { buildVersionTracker, createEmptyVersionTracker } from './version-tracker/index.js';

// Re-export types for external use
export type {
  GeneratorConfig,
  GenerationResult,
  GeneratedFile,
  GenerationStats,
  GenerationError,
  AstOutput,
  TsInterface,
  TsTypeAlias,
  TsProperty,
  JsDocTag,
  DomainClassification,
  PhpType,
  PhpProperty,
  PhpClassMeta,
  UnionMembershipMap,
  UnionMembershipInfo,
  VersionTracker,
  VersionInfo,
  PropertyVersionInfo,
} from './types/index.js';

// Re-export config utilities
export { createConfig, validateConfig, DEFAULT_SCHEMA_SOURCE, DEFAULT_PHP_OUTPUT } from './config/index.js';

// Re-export fetcher utilities
export { fetchSchema, fetchSchemaFresh, clearCache } from './fetcher/index.js';

// Re-export parser utilities
export { parseSchema, parseSchemaFile, resolveInheritance, getCategoryTag } from './parser/index.js';

// Re-export generators
export { DtoGenerator, EnumGenerator, NumericEnumGenerator, ConstantsGenerator, UnionGenerator, FactoryGenerator, BuilderGenerator, ContractGenerator, TypeAliasWrapperGenerator, IntersectionTypeWrapperGenerator, TypeMapper, DomainClassifier, SchemaMapGenerator } from './generators/index.js';
export type { TypeAliasWrapperInfo, IntersectionTypeWrapperInfo, SchemaMap, SchemaMapType, SchemaMapFactory, SchemaMapRpc, SchemaMapDomain, SchemaMapIndex } from './generators/index.js';

// Re-export writers
export { FileWriter, generateAbstractDto, generateAbstractEnum, generateValidatesRequiredFieldsTrait } from './writers/index.js';

// Re-export extractors
export { SyntheticDtoExtractor, updateInterfacesWithSyntheticTypes } from './extractors/index.js';

// Re-export version tracker
export { buildVersionTracker, createEmptyVersionTracker, loadSchemaVersions, getVersionsUpTo } from './version-tracker/index.js';
export type { BuildVersionTrackerOptions } from './version-tracker/index.js';

/**
 * Generation options.
 */
export interface GenerateOptions {
  /** Force fresh fetch from GitHub (ignore cache) */
  readonly fresh?: boolean;
  /** Callback for progress updates */
  readonly onProgress?: (message: string) => void;
}

// ============================================================================
// Discriminator Detection Helpers
// ============================================================================

/**
 * Detects the discriminator field for a union type by finding a common field
 * with const values across all members.
 */
function detectDiscriminatorField(
  memberNames: string[],
  interfaces: readonly TsInterface[],
  typeAliases: readonly TsTypeAlias[]
): string | undefined {
  // Get leaf interfaces for all members (flattening nested unions)
  const allLeafInterfaces: TsInterface[] = [];
  for (const memberName of memberNames) {
    allLeafInterfaces.push(...getLeafInterfaces(memberName, interfaces, typeAliases));
  }

  if (allLeafInterfaces.length === 0) {
    return undefined;
  }

  const firstLeaf = allLeafInterfaces[0];
  if (!firstLeaf) {
    return undefined;
  }

  // Find common fields across all leaf interfaces
  const commonFields = firstLeaf.properties
    .map((p) => p.name)
    .filter((name) =>
      allLeafInterfaces.every((m) => m.properties.some((p) => p.name === name))
    );

  // Prioritize 'method' and 'type' as discriminator fields
  const priorityFields = ['method', 'type', 'kind', 'role'];
  return priorityFields.find((f) => commonFields.includes(f)) ?? commonFields[0];
}

/**
 * Gets the discriminator value for a specific member.
 */
function getDiscriminatorValue(
  memberName: string,
  discriminatorField: string,
  interfaces: readonly TsInterface[]
): string | undefined {
  const iface = interfaces.find((i) => i.name === memberName);
  if (!iface) {
    return undefined;
  }

  const prop = iface.properties.find((p) => p.name === discriminatorField);
  if (!prop) {
    return undefined;
  }

  return extractConstValue(prop.type);
}

/**
 * Gets the leaf interfaces for a member (handles nested unions).
 */
function getLeafInterfaces(
  memberName: string,
  interfaces: readonly TsInterface[],
  typeAliases: readonly TsTypeAlias[]
): TsInterface[] {
  // Check if it's a direct interface
  const directInterface = interfaces.find((i) => i.name === memberName);
  if (directInterface) {
    return [directInterface];
  }

  // Check if it's a union type alias
  const typeAlias = typeAliases.find((a) => a.name === memberName);
  if (typeAlias && typeAlias.type.includes('|')) {
    // Extract member names from union type
    const unionMembers = typeAlias.type
      .split('|')
      .map((m) => m.trim())
      .filter((m) => m.length > 0);

    // Recursively get leaf interfaces
    const leaves: TsInterface[] = [];
    for (const unionMember of unionMembers) {
      leaves.push(...getLeafInterfaces(unionMember, interfaces, typeAliases));
    }
    return leaves;
  }

  return [];
}

/**
 * Extracts a const value from a literal type.
 * Only extracts single literals, not union types like "a" | "b".
 */
function extractConstValue(type: string): string | undefined {
  const trimmed = type.trim();

  // Check if it's a single string literal (not a union)
  if (trimmed.startsWith('"') && trimmed.endsWith('"')) {
    const middle = trimmed.slice(1, -1);
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

  return undefined;
}

/**
 * Main generation function.
 *
 * Fetches the MCP TypeScript schema, parses it, and generates PHP code.
 */
export async function generate(
  config: GeneratorConfig,
  options: GenerateOptions = {}
): Promise<GenerationResult> {
  const startTime = Date.now();
  const errors: GenerationError[] = [];
  const files: GeneratedFile[] = [];

  const progress = options.onProgress ?? ((): void => {});

  // Step 1: Fetch schema
  progress('Fetching schema...');
  const fetchResult = options.fresh
    ? await fetchSchemaFresh(config)
    : await fetchSchema(config);

  if (config.verbose) {
    progress(`Schema fetched from: ${fetchResult.source}${fetchResult.cached ? ' (cached)' : ''}`);
  }

  // Step 2: Parse schema
  progress('Parsing TypeScript schema...');
  const ast = parseSchema(fetchResult.content, {
    includeInternalTypes: true, // Include all types for complete schema
  });

  if (config.verbose) {
    progress(`Parsed ${ast.interfaces.length} interfaces, ${ast.typeAliases.length} type aliases, ${ast.constants?.length ?? 0} constants`);
  }

  // Create constants map for TypeMapper (resolves 'typeof CONSTANT_NAME' to actual values)
  const constantsMap = createConstantsMap(ast.constants);

  // Step 2.5: Extract synthetic DTOs from inline object types
  progress('Extracting inline object types...');
  const syntheticExtractor = new SyntheticDtoExtractor();
  const syntheticResult = syntheticExtractor.extract(ast.interfaces);

  // Update original interfaces with synthetic type references
  const updatedInterfaces = updateInterfacesWithSyntheticTypes(
    [...ast.interfaces] as TsInterface[],
    syntheticResult.propertyTypeMap
  );

  // Combine original and synthetic interfaces
  const allInterfaces: TsInterface[] = [...updatedInterfaces, ...syntheticResult.interfaces];

  if (config.verbose) {
    progress(`Extracted ${syntheticResult.interfaces.length} synthetic DTOs from inline types`);
  }

  // Step 3: Set up generators
  // Create shared classifier so all generators use the same classification cache
  const classifier = new DomainClassifier();
  const enumGenerator = new EnumGenerator(config);
  const unionGenerator = new UnionGenerator(config, ast.typeAliases);  // Pass typeAliases for parent union detection

  // Build union membership map BEFORE creating DTO generator
  // This tells us which DTOs need to implement which union interfaces
  // Also includes discriminator field and value for each member
  progress('Building union membership map...');
  const unionMembershipMap: UnionMembershipMap = new Map();

  for (const alias of ast.typeAliases) {
    if (unionGenerator.isUnion(alias)) {
      const members = unionGenerator.extractMembers(alias);
      const unionClassification = classifier.classify(alias.name, alias.tags);
      const unionNamespace = `${config.output.namespace}\\${unionClassification.domain}\\${unionClassification.subdomain}\\Union`;

      // Detect discriminator field for this union
      const discriminatorField = detectDiscriminatorField(members, allInterfaces, ast.typeAliases);

      for (const memberName of members) {
        // Get the discriminator value for this specific member
        const discriminatorValue = discriminatorField
          ? getDiscriminatorValue(memberName, discriminatorField, allInterfaces)
          : undefined;

        const membershipInfo: UnionMembershipInfo = {
          unionName: alias.name,
          namespace: unionNamespace,
          discriminatorField,
          discriminatorValue,
        };

        const existing = unionMembershipMap.get(memberName);
        if (existing) {
          existing.push(membershipInfo);
        } else {
          unionMembershipMap.set(memberName, [membershipInfo]);
        }
      }
    }
  }

  if (config.verbose) {
    progress(`Built union membership map: ${unionMembershipMap.size} DTOs implement union interfaces`);
  }

  // Step 3.5: Build version tracker by fetching and comparing schema versions up to target
  progress('Building version tracker from schema history...');
  let versionTracker: VersionTracker;
  try {
    versionTracker = await buildVersionTracker({
      targetVersion: config.schema.version,
      onProgress: config.verbose ? progress : undefined,
    });
  } catch (error) {
    // If version tracking fails (e.g., network error), continue without it
    const errorMessage = error instanceof Error ? error.message : String(error);
    progress(`Warning: Version tracking unavailable (${errorMessage}). Continuing without version annotations.`);
    versionTracker = createEmptyVersionTracker();
  }

  const dtoGenerator = new DtoGenerator(allInterfaces, config, {}, ast.typeAliases, classifier, unionMembershipMap, versionTracker, constantsMap);
  const factoryGenerator = new FactoryGenerator(config, allInterfaces, ast.typeAliases);  // Include typeAliases for nested union support
  const builderGenerator = new BuilderGenerator(config, allInterfaces, ast.typeAliases, classifier, versionTracker, constantsMap);
  const writer = new FileWriter(config);

  // Step 4: Create directory structure
  progress('Creating directory structure...');
  await writer.createDirectoryStructure();

  // Step 5: Generate base classes and traits
  progress('Generating base classes...');
  files.push({
    path: `Common/AbstractDataTransferObject.php`,
    content: generateAbstractDto(config),
    type: 'dto',
  });
  files.push({
    path: `Common/AbstractEnum.php`,
    content: generateAbstractEnum(config),
    type: 'enum',
  });
  files.push({
    path: `Common/Traits/ValidatesRequiredFields.php`,
    content: generateValidatesRequiredFieldsTrait(config),
    type: 'dto', // Categorize as 'dto' since it's used by DTOs
  });

  // Step 5.5: Generate McpConstants class from schema constants
  // This includes protocol versions, JSON-RPC version, and error codes
  if (ast.constants && ast.constants.length > 0) {
    progress('Generating constants class...');
    const constantsGenerator = new ConstantsGenerator(config);
    files.push({
      path: constantsGenerator.getOutputPath(),
      content: constantsGenerator.generate(ast.constants),
      type: 'enum', // Categorize as 'enum' since it's a constants class
    });

    if (config.verbose) {
      progress(`Generated McpConstants with ${ast.constants.length} constants`);
    }
  }

  // Step 6: Generate DTOs from interfaces (including synthetic ones)
  // First pass: classify all non-synthetic interfaces to populate cache
  progress('Generating DTOs...');
  for (const iface of allInterfaces) {
    if (!iface.isSynthetic) {
      classifier.classify(iface.name, iface.tags);
    }
  }

  // Second pass: generate all DTOs
  for (const iface of allInterfaces) {
    try {
      const content = dtoGenerator.generate(iface);
      const classification = classifier.classify(iface.name, iface.tags, iface.syntheticParent);
      const path = writer.getOutputPath(classification, 'Dto', iface.name);
      files.push({ path, content, type: 'dto' });
    } catch (error) {
      const message = error instanceof Error ? error.message : String(error);
      errors.push({
        type: iface.name,
        message: `Failed to generate DTO: ${message}`,
        source: 'interface',
      });
    }
  }

  // Step 7: Generate enums and unions from type aliases
  progress('Generating enums and unions...');
  for (const alias of ast.typeAliases) {
    try {
      if (enumGenerator.isEnum(alias)) {
        const content = enumGenerator.generate(alias);
        const classification = classifier.classify(alias.name, alias.tags);
        const path = writer.getOutputPath(classification, 'Enum', alias.name);
        files.push({ path, content, type: 'enum' });
      } else if (unionGenerator.isUnion(alias)) {
        // Generate union interface
        const interfaceContent = unionGenerator.generate(alias);
        const classification = classifier.classify(alias.name, alias.tags);
        const interfacePath = writer.getOutputPath(classification, 'Union', `${alias.name}Interface`);
        files.push({ path: interfacePath, content: interfaceContent, type: 'union' });

        // Generate factory (always enabled)
        const members = unionGenerator.extractMembers(alias);
        const factoryContent = factoryGenerator.generate(alias, members);
        // Only create factory file if generator returned content (has discriminator)
        if (factoryContent !== null) {
          const factoryPath = writer.getOutputPath(classification, 'Factory', `${alias.name}Factory`);
          files.push({ path: factoryPath, content: factoryContent, type: 'factory' });
        }
      }
    } catch (error) {
      const message = error instanceof Error ? error.message : String(error);
      errors.push({
        type: alias.name,
        message: `Failed to generate type: ${message}`,
        source: 'typeAlias',
      });
    }
  }

  // Step 7.1: Generate numeric enums from TypeScript enum declarations
  // These are actual TypeScript enums (like ErrorCode) with numeric values,
  // different from string literal union types handled by EnumGenerator.
  progress('Generating numeric enums...');
  const numericEnumGenerator = new NumericEnumGenerator(config);
  const numericEnums = ast.enums?.filter((e) => numericEnumGenerator.isNumericEnum(e)) ?? [];

  if (config.verbose) {
    progress(`Found ${numericEnums.length} numeric enums to generate`);
  }

  for (const tsEnum of numericEnums) {
    try {
      const content = numericEnumGenerator.generate(tsEnum);
      const classification = numericEnumGenerator.classify(tsEnum);
      const path = writer.getOutputPath(classification, 'Enum', tsEnum.name);
      files.push({ path, content, type: 'enum' });
    } catch (error) {
      const message = error instanceof Error ? error.message : String(error);
      errors.push({
        type: tsEnum.name,
        message: `Failed to generate numeric enum: ${message}`,
        source: 'enum',
      });
    }
  }

  // Step 7.5: Generate type alias wrappers
  // Type aliases like `type EmptyResult = Result` don't get their own DTOs, but when
  // they're referenced in unions (e.g., `ServerResult = EmptyResult | InitializeResult | ...`),
  // we need wrapper classes so they can implement the union interfaces.
  // See: https://github.com/WordPress/php-mcp-schema/issues/XX (Missing EmptyResult type)
  progress('Generating type alias wrappers...');
  const typeAliasWrapperGenerator = new TypeAliasWrapperGenerator(config, allInterfaces, ast.typeAliases);
  const aliasesNeedingWrappers = typeAliasWrapperGenerator.findAliasesNeedingWrappers(unionMembershipMap);

  if (config.verbose) {
    progress(`Found ${aliasesNeedingWrappers.length} type aliases needing wrapper classes`);
  }

  for (const aliasInfo of aliasesNeedingWrappers) {
    try {
      const content = typeAliasWrapperGenerator.generate(aliasInfo);
      const path = typeAliasWrapperGenerator.getOutputPath(aliasInfo);
      files.push({ path, content, type: 'dto' }); // Categorize as DTO since it extends a DTO
    } catch (error) {
      const message = error instanceof Error ? error.message : String(error);
      errors.push({
        type: aliasInfo.aliasName,
        message: `Failed to generate type alias wrapper: ${message}`,
        source: 'typeAliasWrapper',
      });
    }
  }

  // Step 7.6: Generate intersection type wrappers
  // Intersection types like `type GetTaskResult = Result & Task` combine properties
  // from multiple interfaces. When they're referenced in unions
  // (e.g., `ClientResult = EmptyResult | GetTaskResult | ...`), we need concrete
  // classes that have all properties from the intersected types.
  // PHP's single inheritance limitation is handled by:
  // - Extending the most "generic" type (typically Result)
  // - Merging properties from other intersected types into the class
  progress('Generating intersection type wrappers...');
  const intersectionTypeWrapperGenerator = new IntersectionTypeWrapperGenerator(config, allInterfaces, ast.typeAliases);
  const intersectionsNeedingWrappers = intersectionTypeWrapperGenerator.findIntersectionsNeedingWrappers(unionMembershipMap);

  if (config.verbose) {
    progress(`Found ${intersectionsNeedingWrappers.length} intersection types needing wrapper classes`);
  }

  for (const intersectionInfo of intersectionsNeedingWrappers) {
    try {
      const content = intersectionTypeWrapperGenerator.generate(intersectionInfo);
      const path = intersectionTypeWrapperGenerator.getOutputPath(intersectionInfo);
      files.push({ path, content, type: 'dto' }); // Categorize as DTO since it extends a DTO
    } catch (error) {
      const message = error instanceof Error ? error.message : String(error);
      errors.push({
        type: intersectionInfo.typeName,
        message: `Failed to generate intersection type wrapper: ${message}`,
        source: 'intersectionTypeWrapper',
      });
    }
  }

  // Step 8: Generate builders (currently disabled)
  // TODO: Builder generation can be enabled in the future by setting generateBuilders to true.
  // The BuilderGenerator class is fully implemented and ready to use.
  const generateBuilders = false;
  if (generateBuilders) {
    progress('Generating builders...');
    for (const iface of allInterfaces) {
      try {
        const content = builderGenerator.generate(iface);
        const classification = classifier.classify(iface.name, iface.tags, iface.syntheticParent);
        const path = writer.getOutputPath(classification, 'Builder', `${iface.name}Builder`);
        files.push({ path, content, type: 'builder' });
      } catch (error) {
        const message = error instanceof Error ? error.message : String(error);
        errors.push({
          type: iface.name,
          message: `Failed to generate builder: ${message}`,
          source: 'builder',
        });
      }
    }
  }

  // Step 9: Generate contracts (PHP interfaces based on extends relationships)
  progress('Generating contracts...');
  const contractGenerator = new ContractGenerator(config, allInterfaces);
  const contracts = contractGenerator.generateAll();

  for (const contract of contracts) {
    const path = `Common/Contracts/${contract.name}.php`;
    files.push({ path, content: contract.content, type: 'interface' });
  }

  // Step 9.5: Generate skill files for Claude Code
  // Skill files go to the project root skill/ directory, not inside src/
  progress('Generating skill files...');
  const skillGenerator = new SkillGenerator(
    config,
    allInterfaces,
    ast.typeAliases,
    ast.enums ?? [],
    unionMembershipMap,
    classifier,
    intersectionsNeedingWrappers
  );
  const skillResult = skillGenerator.generateAll();

  // Write skill files directly to project root (not via FileWriter which uses output.outputDir)
  const { mkdir, writeFile, chmod } = await import('fs/promises');
  const { dirname, join, resolve } = await import('path');

  // Resolve skill directory relative to output dir's parent (project root)
  const projectRoot = resolve(config.output.outputDir, '..');

  for (const skillFile of skillResult.files) {
    const fullPath = join(projectRoot, skillFile.path);
    await mkdir(dirname(fullPath), { recursive: true });
    await writeFile(fullPath, skillFile.content, 'utf-8');

    // Make scripts executable
    if (skillFile.type === 'script') {
      await chmod(fullPath, 0o755);
    }
  }

  if (config.verbose) {
    progress(`Generated ${skillResult.files.length} skill files (${skillResult.stats.markdownFiles} markdown, ${skillResult.stats.jsonFiles} JSON, ${skillResult.stats.scriptFiles} scripts)`);
  }

  // Step 10: Validate class names (PSR-1 compliance)
  progress('Validating class names...');
  const invalidClassNames: string[] = [];
  for (const iface of allInterfaces) {
    if (iface.name.includes('_')) {
      invalidClassNames.push(iface.name);
    }
  }
  for (const alias of ast.typeAliases) {
    if (alias.name.includes('_')) {
      invalidClassNames.push(alias.name);
    }
  }

  if (invalidClassNames.length > 0) {
    throw new Error(
      `PSR-1 violation: Class names containing underscores are not allowed.\n` +
      `Invalid names: ${invalidClassNames.join(', ')}\n` +
      `Please fix the generator to produce valid PascalCase names.`
    );
  }

  // Step 11: Write files
  progress('Writing files...');
  const writeResult = await writer.writeFiles(files);

  for (const result of writeResult.results) {
    if (!result.success) {
      errors.push({
        type: result.path,
        message: result.error ?? 'Unknown write error',
        source: 'writer',
      });
    }
  }

  // Calculate stats
  const stats: GenerationStats = {
    totalTypes: allInterfaces.length + ast.typeAliases.length,
    dtos: files.filter((f) => f.type === 'dto').length,
    enums: files.filter((f) => f.type === 'enum').length,
    unions: files.filter((f) => f.type === 'union').length,
    factories: files.filter((f) => f.type === 'factory').length,
    builders: files.filter((f) => f.type === 'builder').length,
    interfaces: files.filter((f) => f.type === 'interface').length, // Contracts
    duration: Date.now() - startTime,
  };

  progress('Done!');

  return {
    files,
    stats,
    errors,
  };
}

/**
 * MCP PHP Schema Generator - File Writers
 *
 * Handles writing generated PHP files to disk with proper directory structure.
 */

import { mkdir, writeFile, rm, access, constants } from 'fs/promises';
import { dirname, join } from 'path';
import type { GeneratedFile, GeneratorConfig, DomainClassification } from '../types/index.js';

/**
 * Write result for a single file.
 */
export interface WriteResult {
  readonly path: string;
  readonly success: boolean;
  readonly error?: string;
}

/**
 * Batch write result.
 */
export interface BatchWriteResult {
  readonly total: number;
  readonly successful: number;
  readonly failed: number;
  readonly results: readonly WriteResult[];
}

/**
 * Writes generated PHP files to disk.
 */
export class FileWriter {
  private readonly config: GeneratorConfig;

  constructor(config: GeneratorConfig) {
    this.config = config;
  }

  /**
   * Writes a single generated file.
   */
  async writeFile(file: GeneratedFile): Promise<WriteResult> {
    if (this.config.dryRun) {
      return { path: file.path, success: true };
    }

    try {
      const fullPath = this.getFullPath(file.path);
      await mkdir(dirname(fullPath), { recursive: true });
      await writeFile(fullPath, file.content, 'utf-8');

      return { path: file.path, success: true };
    } catch (error) {
      const message = error instanceof Error ? error.message : String(error);
      return { path: file.path, success: false, error: message };
    }
  }

  /**
   * Writes multiple generated files.
   */
  async writeFiles(files: readonly GeneratedFile[]): Promise<BatchWriteResult> {
    const results: WriteResult[] = [];

    for (const file of files) {
      const result = await this.writeFile(file);
      results.push(result);
    }

    const successful = results.filter((r) => r.success).length;
    const failed = results.filter((r) => !r.success).length;

    return {
      total: files.length,
      successful,
      failed,
      results,
    };
  }

  /**
   * Gets the full file path for a generated file.
   */
  private getFullPath(relativePath: string): string {
    return join(this.config.output.outputDir, relativePath);
  }

  /**
   * Gets the output path for a type.
   * DTOs are placed directly in the subdomain folder, other types get their own subfolder.
   */
  getOutputPath(
    classification: DomainClassification,
    typeCategory: 'Dto' | 'Enum' | 'Union' | 'Factory' | 'Builder',
    className: string
  ): string {
    // DTOs go directly in subdomain folder, other types get their own subfolder
    if (typeCategory === 'Dto') {
      return `${classification.domain}/${classification.subdomain}/${className}.php`;
    }
    return `${classification.domain}/${classification.subdomain}/${typeCategory}/${className}.php`;
  }

  /**
   * Clears the output directory.
   */
  async clearOutput(): Promise<void> {
    if (this.config.dryRun) {
      return;
    }

    const outputDir = this.config.output.outputDir;

    try {
      // Check if directory exists before removing
      await access(outputDir, constants.F_OK);
      await rm(outputDir, { recursive: true, force: true });
    } catch {
      // Directory doesn't exist, nothing to clear
    }
  }

  /**
   * Creates the base directory structure.
   *
   * Note: Most directories are created on-demand when files are written.
   * This method only creates directories that are needed before file writes.
   */
  async createDirectoryStructure(): Promise<void> {
    if (this.config.dryRun) {
      return;
    }

    // Directories are created on-demand by writeFile() using mkdir({ recursive: true })
    // No pre-emptive directory creation needed - this prevents empty directories
  }
}

/**
 * Generates the AbstractDataTransferObject base class.
 */
export function generateAbstractDto(config: GeneratorConfig): string {
  // Version is used in directory structure but NOT in namespace (PHP namespaces can't start with digits)
  const namespace = `${config.output.namespace}\\Common`;
  const indent = config.output.indentation === 'tabs' ? '\t' : ' '.repeat(config.output.indentSize);

  return `<?php

declare(strict_types=1);

namespace ${namespace};

/**
 * Base class for all Data Transfer Objects.
 *
 * @mcp-version ${config.schema.version}
 */
abstract class AbstractDataTransferObject
{
${indent}/**
${indent} * Creates an instance from an array.
${indent} *
${indent} * @param array<string, mixed> $data
${indent} * @return static
${indent} */
${indent}abstract public static function fromArray(array $data): self;

${indent}/**
${indent} * Converts the instance to an array.
${indent} *
${indent} * @return array<string, mixed>
${indent} */
${indent}abstract public function toArray(): array;

${indent}/**
${indent} * Converts the instance to JSON.
${indent} *
${indent} * @return string
${indent} */
${indent}public function toJson(): string
${indent}{
${indent}${indent}return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
${indent}}

${indent}/**
${indent} * Creates an instance from JSON.
${indent} *
${indent} * @param string $json
${indent} * @return static
${indent} */
${indent}public static function fromJson(string $json): self
${indent}{
${indent}${indent}/** @var array<string, mixed> $data */
${indent}${indent}$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
${indent}${indent}return static::fromArray($data);
${indent}}
}
`;
}

/**
 * Generates the AbstractEnum base class for PHP 7.4.
 */
export function generateAbstractEnum(config: GeneratorConfig): string {
  // Version is used in directory structure but NOT in namespace (PHP namespaces can't start with digits)
  const namespace = `${config.output.namespace}\\Common`;
  const indent = config.output.indentation === 'tabs' ? '\t' : ' '.repeat(config.output.indentSize);

  return `<?php

declare(strict_types=1);

namespace ${namespace};

/**
 * Base class for PHP 7.4 enum emulation.
 *
 * @mcp-version ${config.schema.version}
 */
abstract class AbstractEnum
{
${indent}/** @var string */
${indent}private string $value;

${indent}/** @var array<string, static> */
${indent}private static array $instances = [];

${indent}/**
${indent} * @param string $value
${indent} */
${indent}private function __construct(string $value)
${indent}{
${indent}${indent}$this->value = $value;
${indent}}

${indent}/**
${indent} * Creates an instance from a value.
${indent} *
${indent} * @param string $value
${indent} * @return static
${indent} * @throws \\InvalidArgumentException
${indent} */
${indent}public static function from(string $value): self
${indent}{
${indent}${indent}$values = static::values();
${indent}${indent}if (!in_array($value, $values, true)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(
${indent}${indent}${indent}${indent}sprintf('Invalid enum value: %s. Valid values: %s', $value, implode(', ', $values))
${indent}${indent}${indent});
${indent}${indent}}

${indent}${indent}$key = static::class . '::' . $value;
${indent}${indent}if (!isset(self::$instances[$key])) {
${indent}${indent}${indent}/** @phpstan-ignore new.static (Intentional: private constructor prevents subclass override) */
${indent}${indent}${indent}self::$instances[$key] = new static($value);
${indent}${indent}}

${indent}${indent}return self::$instances[$key];
${indent}}

${indent}/**
${indent} * Creates an instance from a value, or null if invalid.
${indent} *
${indent} * @param string $value
${indent} * @return static|null
${indent} */
${indent}public static function tryFrom(string $value): ?self
${indent}{
${indent}${indent}try {
${indent}${indent}${indent}return static::from($value);
${indent}${indent}} catch (\\InvalidArgumentException $e) {
${indent}${indent}${indent}return null;
${indent}${indent}}
${indent}}

${indent}/**
${indent} * Returns all valid values for this enum.
${indent} *
${indent} * @return string[]
${indent} */
${indent}abstract public static function values(): array;

${indent}/**
${indent} * Returns all cases as instances.
${indent} *
${indent} * @return static[]
${indent} */
${indent}public static function cases(): array
${indent}{
${indent}${indent}return array_map(fn(string $value) => static::from($value), static::values());
${indent}}

${indent}/**
${indent} * Gets the enum value.
${indent} *
${indent} * @return string
${indent} */
${indent}public function getValue(): string
${indent}{
${indent}${indent}return $this->value;
${indent}}

${indent}/**
${indent} * Converts to string.
${indent} *
${indent} * @return string
${indent} */
${indent}public function __toString(): string
${indent}{
${indent}${indent}return $this->value;
${indent}}

${indent}/**
${indent} * Compares with another instance.
${indent} *
${indent} * @param self $other
${indent} * @return bool
${indent} */
${indent}public function equals(self $other): bool
${indent}{
${indent}${indent}return $this->value === $other->value && static::class === get_class($other);
${indent}}
}
`;
}

/**
 * Generates the ValidatesRequiredFields trait for PHP 7.4.
 *
 * This trait provides a reusable method for validating required fields in fromArray().
 * Benefits:
 * - DRY: Removes ~1800 lines of repeated validation code
 * - Better errors: Reports class name and ALL missing fields at once
 * - Consistent: Same validation pattern across all DTOs
 */
export function generateValidatesRequiredFieldsTrait(config: GeneratorConfig): string {
  const namespace = `${config.output.namespace}\\Common\\Traits`;
  const indent = config.output.indentation === 'tabs' ? '\t' : ' '.repeat(config.output.indentSize);

  return `<?php

declare(strict_types=1);

namespace ${namespace};

/**
 * Trait for validating required fields in fromArray() methods.
 *
 * Use this trait in DTOs that need to validate required fields from input arrays.
 * Reports ALL missing fields at once for better developer experience.
 *
 * @mcp-version ${config.schema.version}
 */
trait ValidatesRequiredFields
{
${indent}/**
${indent} * Validates that all required fields are present in the data array.
${indent} *
${indent} * @param array<string, mixed> $data The input data array
${indent} * @param string[] $requiredFields List of required field names
${indent} * @return void
${indent} * @throws \\InvalidArgumentException If any required fields are missing
${indent} */
${indent}protected static function assertRequired(array $data, array $requiredFields): void
${indent}{
${indent}${indent}$missing = array_filter(
${indent}${indent}${indent}$requiredFields,
${indent}${indent}${indent}static fn(string $field): bool => !array_key_exists($field, $data)
${indent}${indent});
${indent}${indent}
${indent}${indent}if (count($missing) > 0) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'%s: missing required field(s): %s',
${indent}${indent}${indent}${indent}static::class,
${indent}${indent}${indent}${indent}implode(', ', $missing)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}}
}
`;
}

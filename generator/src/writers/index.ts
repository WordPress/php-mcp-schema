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

${indent}/**
${indent} * Asserts a value is a string and returns it.
${indent} *
${indent} * @param mixed $value
${indent} * @return string
${indent} * @phpstan-assert string $value
${indent} */
${indent}protected static function asString($value): string
${indent}{
${indent}${indent}if (!is_string($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected string, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Asserts a value is an int and returns it.
${indent} *
${indent} * @param mixed $value
${indent} * @return int
${indent} * @phpstan-assert int $value
${indent} */
${indent}protected static function asInt($value): int
${indent}{
${indent}${indent}if (!is_int($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected int, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Asserts a value is a float and returns it.
${indent} *
${indent} * @param mixed $value
${indent} * @return float
${indent} * @phpstan-assert float $value
${indent} */
${indent}protected static function asFloat($value): float
${indent}{
${indent}${indent}if (!is_float($value) && !is_int($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected float, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return (float) $value;
${indent}}

${indent}/**
${indent} * Asserts a value is a bool and returns it.
${indent} *
${indent} * @param mixed $value
${indent} * @return bool
${indent} * @phpstan-assert bool $value
${indent} */
${indent}protected static function asBool($value): bool
${indent}{
${indent}${indent}if (!is_bool($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected bool, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Asserts a value is an array and returns it.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<string, mixed>
${indent} * @phpstan-assert array<string, mixed> $value
${indent} */
${indent}protected static function asArray($value): array
${indent}{
${indent}${indent}if (!is_array($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected array, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}/** @var array<string, mixed> */
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Returns a value as string or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return string|null
${indent} */
${indent}protected static function asStringOrNull($value): ?string
${indent}{
${indent}${indent}return $value === null ? null : self::asString($value);
${indent}}

${indent}/**
${indent} * Returns a value as int or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return int|null
${indent} */
${indent}protected static function asIntOrNull($value): ?int
${indent}{
${indent}${indent}return $value === null ? null : self::asInt($value);
${indent}}

${indent}/**
${indent} * Returns a value as float or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return float|null
${indent} */
${indent}protected static function asFloatOrNull($value): ?float
${indent}{
${indent}${indent}return $value === null ? null : self::asFloat($value);
${indent}}

${indent}/**
${indent} * Returns a value as bool or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return bool|null
${indent} */
${indent}protected static function asBoolOrNull($value): ?bool
${indent}{
${indent}${indent}return $value === null ? null : self::asBool($value);
${indent}}

${indent}/**
${indent} * Returns a value as array or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<string, mixed>|null
${indent} */
${indent}protected static function asArrayOrNull($value): ?array
${indent}{
${indent}${indent}return $value === null ? null : self::asArray($value);
${indent}}

${indent}/**
${indent} * Asserts a value is an object and returns it.
${indent} *
${indent} * Accepts both PHP arrays and objects, auto-converting arrays to objects.
${indent} * This aligns with MCP spec where JSON objects can be PHP arrays or objects.
${indent} *
${indent} * @param mixed $value
${indent} * @return object
${indent} * @phpstan-assert object $value
${indent} */
${indent}protected static function asObject($value): object
${indent}{
${indent}${indent}if (is_array($value)) {
${indent}${indent}${indent}return (object) $value;
${indent}${indent}}
${indent}${indent}if (!is_object($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected array or object, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Returns a value as object or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return object|null
${indent} */
${indent}protected static function asObjectOrNull($value): ?object
${indent}{
${indent}${indent}return $value === null ? null : self::asObject($value);
${indent}}

${indent}/**
${indent} * Asserts a value is an array of strings and returns it.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<int, string>
${indent} */
${indent}protected static function asStringArray($value): array
${indent}{
${indent}${indent}if (!is_array($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected array, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}/** @var array<int, string> */
${indent}${indent}return array_values(array_map(static fn($item): string => (string) $item, $value));
${indent}}

${indent}/**
${indent} * Returns a value as array of strings or null.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<int, string>|null
${indent} */
${indent}protected static function asStringArrayOrNull($value): ?array
${indent}{
${indent}${indent}return $value === null ? null : self::asStringArray($value);
${indent}}

${indent}/**
${indent} * Asserts a value is an associative array with string values only.
${indent} *
${indent} * Used for MCP types like { [key: string]: string } index signatures.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<string, string>
${indent} * @phpstan-assert array<string, string> $value
${indent} */
${indent}protected static function asStringMap($value): array
${indent}{
${indent}${indent}if (!is_array($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected array, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}foreach ($value as $key => $v) {
${indent}${indent}${indent}if (!is_string($v)) {
${indent}${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}${indent}'Expected string value for key "%s", got %s',
${indent}${indent}${indent}${indent}${indent}(string) $key,
${indent}${indent}${indent}${indent}${indent}gettype($v)
${indent}${indent}${indent}${indent}));
${indent}${indent}${indent}}
${indent}${indent}}
${indent}${indent}/** @var array<string, string> */
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Returns a value as string map or null.
${indent} *
${indent} * Used for optional MCP types like { [key: string]: string } | null.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<string, string>|null
${indent} */
${indent}protected static function asStringMapOrNull($value): ?array
${indent}{
${indent}${indent}return $value === null ? null : self::asStringMap($value);
${indent}}

${indent}/**
${indent} * Asserts a value is an associative array with object values only.
${indent} *
${indent} * Used for MCP types like { [key: string]: object } index signatures.
${indent} * Accepts both PHP arrays and objects as values, auto-converting arrays to objects.
${indent} * This aligns with MCP spec where JSON objects can be PHP arrays or objects.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<string, object>
${indent} * @phpstan-assert array<string, object> $value
${indent} */
${indent}protected static function asObjectMap($value): array
${indent}{
${indent}${indent}if (!is_array($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected array, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}
${indent}${indent}$result = [];
${indent}${indent}foreach ($value as $key => $v) {
${indent}${indent}${indent}if (is_array($v)) {
${indent}${indent}${indent}${indent}$result[$key] = (object) $v;
${indent}${indent}${indent}} elseif (is_object($v)) {
${indent}${indent}${indent}${indent}$result[$key] = $v;
${indent}${indent}${indent}} else {
${indent}${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}${indent}'Expected array or object for key "%s", got %s',
${indent}${indent}${indent}${indent}${indent}(string) $key,
${indent}${indent}${indent}${indent}${indent}gettype($v)
${indent}${indent}${indent}${indent}));
${indent}${indent}${indent}}
${indent}${indent}}
${indent}${indent}
${indent}${indent}/** @var array<string, object> */
${indent}${indent}return $result;
${indent}}

${indent}/**
${indent} * Returns a value as object map or null.
${indent} *
${indent} * Used for optional MCP types like { [key: string]: object } | null.
${indent} *
${indent} * @param mixed $value
${indent} * @return array<string, object>|null
${indent} */
${indent}protected static function asObjectMapOrNull($value): ?array
${indent}{
${indent}${indent}return $value === null ? null : self::asObjectMap($value);
${indent}}

${indent}/**
${indent} * Asserts a value is a scalar (string, int, float, or bool) for sprintf.
${indent} *
${indent} * @param mixed $value
${indent} * @return string|int|float|bool
${indent} */
${indent}protected static function asScalar($value)
${indent}{
${indent}${indent}if (!is_scalar($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected scalar value, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Asserts a value is a string or number (int/float) and returns it.
${indent} *
${indent} * Used for MCP types like ProgressToken that accept string | number.
${indent} *
${indent} * @param mixed $value
${indent} * @return string|int|float
${indent} */
${indent}protected static function asStringOrNumber($value)
${indent}{
${indent}${indent}if (!is_string($value) && !is_int($value) && !is_float($value)) {
${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(
${indent}${indent}${indent}${indent}'Expected string or number, got %s',
${indent}${indent}${indent}${indent}gettype($value)
${indent}${indent}${indent}));
${indent}${indent}}
${indent}${indent}return $value;
${indent}}

${indent}/**
${indent} * Returns a value as string or number (int/float), or null.
${indent} *
${indent} * Used for optional MCP types like ProgressToken that accept string | number | null.
${indent} *
${indent} * @param mixed $value
${indent} * @return string|int|float|null
${indent} */
${indent}protected static function asStringOrNumberOrNull($value)
${indent}{
${indent}${indent}return $value === null ? null : self::asStringOrNumber($value);
${indent}}
}
`;
}

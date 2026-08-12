/**
 * MCP PHP Schema Generator - Builder Generator
 *
 * Generates PHP builder classes for fluent DTO construction.
 */

import type { TsInterface, TsProperty, TsTypeAlias, GeneratorConfig, DomainClassification, VersionTracker } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { TypeMapper, ConstantsMap } from './type-mapper.js';
import { TypeResolver } from './type-resolver.js';
import { resolveInheritance } from '../parser/index.js';
import { formatPhpDocDescription, getDeprecatedPhpDocTag } from './index.js';
import { assertNoPhpPropertyNameCollisions, getPhpPropertyName } from './property-names.js';

/**
 * Generates PHP builder classes for DTOs.
 */
export class BuilderGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;
  private readonly interfaces: readonly TsInterface[];
  private readonly typeResolver: TypeResolver;
  private readonly versionTracker: VersionTracker | undefined;

  constructor(
    config: GeneratorConfig,
    interfaces: readonly TsInterface[],
    typeAliases: readonly TsTypeAlias[] = [],
    classifier?: DomainClassifier,
    versionTracker?: VersionTracker,
    constants?: ConstantsMap
  ) {
    this.config = config;
    this.classifier = classifier ?? new DomainClassifier();
    this.interfaces = interfaces;
    this.typeResolver = new TypeResolver(typeAliases, interfaces, config.output.namespace, this.classifier, constants);
    this.versionTracker = versionTracker;
  }

  /**
   * Generates PHP builder code for an interface.
   */
  generate(iface: TsInterface): string {
    const classification = this.classifier.classify(iface.name, iface.tags, iface.syntheticParent);
    const properties = resolveInheritance(iface.name, this.interfaces);
    const indent = this.getIndent();

    assertNoPhpPropertyNameCollisions(
      iface.name,
      properties.filter((property) => !property.isOpenBag).map((property) => property.name)
    );

    return this.renderBuilder(iface, properties, classification, indent);
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
   * Renders the PHP builder class.
   */
  private renderBuilder(
    iface: TsInterface,
    properties: TsProperty[],
    classification: DomainClassification,
    indent: string
  ): string {
    const lines: string[] = [];
    const namespace = this.getNamespace(classification);
    const builderName = `${iface.name}Builder`;
    const dtoName = iface.name;

    // Get the actual DTO namespace by resolving the interface through TypeResolver
    // This ensures we use the same classification as the DTO generator
    const resolvedDto = this.typeResolver.resolve(iface.name, undefined, iface.tags);
    const dtoNamespace = resolvedDto.namespace ?? this.getDtoNamespace(classification);

    // Identify required properties
    const requiredProps = properties.filter((p) => !p.isOptional);

    // Resolve all property types and collect imports
    const resolvedProps = properties.map((p) => ({
      prop: p,
      resolved: this.typeResolver.resolve(p.type, p.name, iface.tags),
    }));

    // Collect unique imports (excluding same-namespace classes)
    const imports = new Map<string, string>(); // FQN -> simple name
    for (const { resolved } of resolvedProps) {
      if (resolved.needsImport && resolved.namespace && resolved.className) {
        const fqn = `${resolved.namespace}\\${resolved.className}`;
        // Don't import from same namespace
        if (resolved.namespace !== namespace) {
          imports.set(fqn, resolved.className);
        }
      }
    }

    // PHP opening tag
    lines.push('<?php');
    lines.push('');
    lines.push('declare(strict_types=1);');
    lines.push('');

    // Namespace
    lines.push(`namespace ${namespace};`);
    lines.push('');

    // Use statements
    lines.push(`use ${dtoNamespace}\\${dtoName};`);
    for (const [fqn] of imports) {
      lines.push(`use ${fqn};`);
    }
    lines.push('');

    // Class docblock
    lines.push('/**');
    if (iface.description) {
      lines.push(...formatPhpDocDescription(iface.description));
      lines.push(' *');
    }
    lines.push(` * Builder for ${dtoName} instances.`);
    lines.push(' *');

    // Version tracking annotations
    const versionInfo = this.versionTracker?.getDefinitionVersion(iface.name);
    if (versionInfo) {
      lines.push(` * @since ${versionInfo.introducedIn}`);
      if (versionInfo.lastModified && versionInfo.changeSummary) {
        lines.push(` * @last-updated ${versionInfo.lastModified} (${versionInfo.changeSummary})`);
      }
      lines.push(' *');
    }

    const deprecatedTag = getDeprecatedPhpDocTag(iface.tags);
    if (deprecatedTag) {
      lines.push(` * ${deprecatedTag}`);
      lines.push(' *');
    }

    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration
    lines.push(`final class ${builderName}`);
    lines.push('{');

    // Property declarations
    for (const { prop, resolved } of resolvedProps) {
      const phpType = resolved.phpType;
      lines.push(`${indent}/**`);
      if (prop.description) {
        lines.push(...formatPhpDocDescription(prop.description, indent));
        lines.push(`${indent} *`);
      }
      // Add @since for this property if version info is available
      const propVersionInfo = this.versionTracker?.getPropertyVersion(iface.name, prop.name);
      if (propVersionInfo) {
        lines.push(`${indent} * @since ${propVersionInfo.introducedIn}`);
        lines.push(`${indent} *`);
      }
      const deprecatedTag = getDeprecatedPhpDocTag(prop.tags);
      if (deprecatedTag) {
        lines.push(`${indent} * ${deprecatedTag}`);
        lines.push(`${indent} *`);
      }
      // Builder properties are always nullable (initialized to null)
      // Include |null in PHPDoc to match the actual nullable behavior
      const phpDocType = TypeMapper.getPhpDocType(phpType);
      const nullableDocType = phpDocType.includes('|null') || phpDocType === 'mixed' || phpType.isUntyped
        ? phpDocType
        : `${phpDocType}|null`;
      lines.push(`${indent} * @var ${nullableDocType}`);
      lines.push(`${indent} */`);

      // All builder properties are nullable to support fluent building
      // For untyped properties (PHP 7.4), omit type hint entirely
      const phpTypeHint = TypeMapper.getTypeHint(phpType);
      if (phpTypeHint === '') {
        lines.push(`${indent}private $${this.sanitizePropertyName(prop.name)} = null;`);
      } else if (phpTypeHint.startsWith('?')) {
        lines.push(`${indent}private ${phpTypeHint} $${this.sanitizePropertyName(prop.name)} = null;`);
      } else {
        lines.push(`${indent}private ?${phpTypeHint} $${this.sanitizePropertyName(prop.name)} = null;`);
      }
      lines.push('');
    }

    // Track required properties
    if (requiredProps.length > 0) {
      lines.push(`${indent}/**`);
      lines.push(`${indent} * @var array<string, bool> Tracks which required properties have been set.`);
      lines.push(`${indent} */`);
      lines.push(`${indent}private array $_set = [];`);
      lines.push('');
    }

    // Setter methods
    for (const { prop, resolved } of resolvedProps) {
      const phpType = resolved.phpType;
      const methodName = this.getSetterName(prop.name);
      const paramName = this.sanitizePropertyName(prop.name);
      const isRequired = !prop.isOptional;

      lines.push(`${indent}/**`);
      if (prop.description) {
        lines.push(...formatPhpDocDescription(prop.description, indent));
        lines.push(`${indent} *`);
      }
      lines.push(`${indent} * @param ${TypeMapper.getPhpDocType(phpType)} $${paramName}`);
      lines.push(`${indent} * @return self`);
      lines.push(`${indent} */`);
      // For untyped properties (PHP 7.4), omit parameter type hint
      const setterTypeHint = TypeMapper.getTypeHint(phpType);
      const setterParam = setterTypeHint ? `${setterTypeHint} $${paramName}` : `$${paramName}`;
      lines.push(`${indent}public function ${methodName}(${setterParam}): self`);
      lines.push(`${indent}{`);
      lines.push(`${indent}${indent}$this->${paramName} = $${paramName};`);
      if (isRequired) {
        lines.push(`${indent}${indent}$this->_set['${prop.name}'] = true;`);
      }
      lines.push(`${indent}${indent}return $this;`);
      lines.push(`${indent}}`);
      lines.push('');
    }

    // Build method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Builds the ${dtoName} instance.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return ${dtoName}`);
    lines.push(`${indent} * @throws \\InvalidArgumentException If required properties are not set.`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public function build(): ${dtoName}`);
    lines.push(`${indent}{`);

    // Required property validation
    if (requiredProps.length > 0) {
      lines.push(`${indent}${indent}$missing = [];`);
      lines.push('');
      for (const prop of requiredProps) {
        lines.push(`${indent}${indent}if (!isset($this->_set['${prop.name}'])) {`);
        lines.push(`${indent}${indent}${indent}$missing[] = '${prop.name}';`);
        lines.push(`${indent}${indent}}`);
      }
      lines.push('');
      lines.push(`${indent}${indent}if (count($missing) > 0) {`);
      lines.push(`${indent}${indent}${indent}throw new \\InvalidArgumentException(`);
      lines.push(`${indent}${indent}${indent}${indent}sprintf('Missing required properties: %s', implode(', ', $missing))`);
      lines.push(`${indent}${indent}${indent});`);
      lines.push(`${indent}${indent}}`);
      lines.push('');
    }

    // Build the data array
    lines.push(`${indent}${indent}$data = [];`);
    lines.push('');
    for (const { prop } of resolvedProps) {
      const paramName = this.sanitizePropertyName(prop.name);
      if (prop.isOptional) {
        lines.push(`${indent}${indent}if ($this->${paramName} !== null) {`);
        lines.push(`${indent}${indent}${indent}$data['${prop.name}'] = $this->${paramName};`);
        lines.push(`${indent}${indent}}`);
      } else {
        lines.push(`${indent}${indent}$data['${prop.name}'] = $this->${paramName};`);
      }
    }
    lines.push('');
    lines.push(`${indent}${indent}return ${dtoName}::fromArray($data);`);

    lines.push(`${indent}}`);

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Gets the PHP namespace for a classification (Builder namespace).
   * Note: Version is used in directory structure but NOT in namespace (PHP namespaces can't start with digits)
   */
  private getNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Builder`;
  }

  /**
   * Gets the PHP namespace for the DTO.
   * DTOs are placed in the DTO subfolder namespace.
   */
  private getDtoNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\DTO`;
  }

  /**
   * Gets the setter method name for a property.
   * Handles special prefixes like _ and $ to create valid PHP method names.
   */
  private getSetterName(propName: string): string {
    let name = getPhpPropertyName(propName);
    // Handle special property names like _meta -> meta
    if (name.startsWith('_')) {
      name = name.substring(1);
    }
    return name;
  }

  /**
   * Sanitizes a property name for PHP.
   * Handles special characters like $ which would create invalid variable names.
   */
  private sanitizePropertyName(name: string): string {
    return getPhpPropertyName(name);
  }
}

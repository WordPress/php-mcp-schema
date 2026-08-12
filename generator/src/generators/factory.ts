/**
 * MCP PHP Schema Generator - Factory Generator
 *
 * Generates PHP factory classes for instantiating union type members.
 */

import type { TsTypeAlias, TsInterface, GeneratorConfig, DomainClassification } from '../types/index.js';
import { DomainClassifier } from './domain-classifier.js';
import { formatPhpDocDescription } from './index.js';

/**
 * Discriminator field information.
 */
interface DiscriminatorInfo {
  readonly field: string;
  readonly values: Map<string, string>; // value -> member type name (for simple single-member cases)
  /** Disambiguation rules for colliding discriminator values */
  readonly disambiguations: Map<string, DisambiguationRule[]>; // value -> rules to try in order
}

/**
 * Rule for disambiguating between members with the same discriminator value.
 */
interface DisambiguationRule {
  readonly memberName: string;
  readonly isUnion: boolean;
  /** Field that must be present for this rule to match (e.g., 'oneOf', 'enumNames') */
  readonly requiredField?: string;
  /** Field that must be absent for this rule to match */
  readonly absentField?: string;
  /** True if this is the fallback rule (no field checks) */
  readonly isFallback?: boolean;
}

/**
 * Member routing information for factory generation.
 */
interface MemberRouting {
  readonly name: string;
  readonly isUnion: boolean; // true if member is a union type alias (route to factory)
}

/**
 * Generates PHP factory classes for union types.
 */
export class FactoryGenerator {
  private readonly classifier: DomainClassifier;
  private readonly config: GeneratorConfig;
  private readonly interfaces: readonly TsInterface[];
  private readonly typeAliases: readonly TsTypeAlias[];
  private readonly unionNames: Set<string>; // Set of type alias names that are unions

  constructor(config: GeneratorConfig, interfaces: readonly TsInterface[], typeAliases: readonly TsTypeAlias[] = []) {
    this.config = config;
    this.classifier = new DomainClassifier();
    this.interfaces = interfaces;
    this.typeAliases = typeAliases;
    // Build set of union type alias names for quick lookup
    this.unionNames = new Set(
      typeAliases
        .filter((alias) => alias.type.includes('|'))
        .map((alias) => alias.name)
    );
  }

  /**
   * Generates PHP factory code for a union type.
   *
   * Returns null if no discriminator is detected, indicating the factory
   * should not be generated. This matches the PHP generator behavior where
   * factories are only useful when a discriminator field exists.
   */
  generate(typeAlias: TsTypeAlias, members: string[]): string | null {
    const classification = this.classifier.classify(typeAlias.name, typeAlias.tags);

    // Validate members - they must be either interfaces OR union type aliases
    // Type aliases like 'EmptyResult = Result' won't have DTOs or factories generated
    const validMembers = members.filter((m) => {
      const isInterface = this.interfaces.some((i) => i.name === m);
      const isUnion = this.unionNames.has(m);
      return isInterface || isUnion;
    });

    // Build member routing info
    const memberRouting = this.buildMemberRouting(validMembers);

    // Detect discriminator from flattened leaf interfaces
    const discriminator = this.detectDiscriminatorWithRouting(memberRouting);

    // Skip factory generation if no discriminator found
    // Factories without discriminators use unreliable try/catch fallback matching
    // which is order-dependent and prone to false matches
    // Note: Check for values OR disambiguations (not just values)
    if (!discriminator || (discriminator.values.size === 0 && discriminator.disambiguations.size === 0)) {
      return null;
    }

    const indent = this.getIndent();

    return this.renderFactoryWithRouting(typeAlias.name, memberRouting, classification, typeAlias.description, discriminator, indent);
  }

  /**
   * Builds routing information for each member.
   * Determines if member is a union (route to factory) or interface (route to DTO).
   */
  private buildMemberRouting(memberNames: string[]): MemberRouting[] {
    return memberNames.map((name) => ({
      name,
      isUnion: this.unionNames.has(name),
    }));
  }

  /**
   * Gets the leaf interfaces for a member.
   * For interfaces, returns the interface itself.
   * For union type aliases, recursively flattens to get all leaf interfaces.
   */
  private getLeafInterfaces(memberName: string): TsInterface[] {
    // Check if it's a direct interface
    const directInterface = this.interfaces.find((i) => i.name === memberName);
    if (directInterface) {
      return [directInterface];
    }

    // Check if it's a union type alias
    const typeAlias = this.typeAliases.find((a) => a.name === memberName);
    if (typeAlias && typeAlias.type.includes('|')) {
      // Extract member names from union type
      const unionMembers = typeAlias.type
        .split('|')
        .map((m) => m.trim())
        .filter((m) => m.length > 0);

      // Recursively get leaf interfaces
      const leaves: TsInterface[] = [];
      for (const unionMember of unionMembers) {
        leaves.push(...this.getLeafInterfaces(unionMember));
      }
      return leaves;
    }

    return [];
  }

  /**
   * Detects discriminator for union with routing info.
   * Uses flattened leaf interfaces for discriminator detection.
   * Handles collisions by building disambiguation rules based on field presence.
   */
  private detectDiscriminatorWithRouting(routing: MemberRouting[]): DiscriminatorInfo | undefined {
    // Collect all leaf interfaces from all members
    const allLeafInterfaces: TsInterface[] = [];
    for (const member of routing) {
      allLeafInterfaces.push(...this.getLeafInterfaces(member.name));
    }

    if (allLeafInterfaces.length === 0) {
      return undefined;
    }

    // Find common fields across all leaf interfaces
    const firstLeaf = allLeafInterfaces[0];
    if (!firstLeaf) {
      return undefined;
    }

    const commonFields = firstLeaf.properties
      .map((p) => p.name)
      .filter((name) =>
        allLeafInterfaces.every((m) => m.properties.some((p) => p.name === name))
      );

    // Prioritize 'method' and 'type' as discriminator fields
    const priorityFields = ['method', 'type', 'kind', 'role'];
    const discriminatorField = priorityFields.find((f) => commonFields.includes(f)) ?? commonFields[0];

    if (!discriminatorField) {
      return undefined;
    }

    // Build value -> members mapping (track all members for each value, not just last)
    const valueToMembers = new Map<string, Array<{ member: MemberRouting; leaf: TsInterface }>>();

    for (const member of routing) {
      const leafInterfaces = this.getLeafInterfaces(member.name);
      for (const leaf of leafInterfaces) {
        const prop = leaf.properties.find((p) => p.name === discriminatorField);
        if (prop) {
          const constValues = this.extractConstValues(prop.type);
          if (constValues) {
            for (const value of constValues) {
              const existing = valueToMembers.get(value) ?? [];
              existing.push({ member, leaf });
              valueToMembers.set(value, existing);
            }
          }
        }
      }
    }

    if (valueToMembers.size === 0) {
      return undefined;
    }

    // Build simple values map (for non-colliding cases) and disambiguation rules
    const values = new Map<string, string>();
    const disambiguations = new Map<string, DisambiguationRule[]>();

    for (const [value, memberLeafs] of valueToMembers) {
      // Get unique parent members for this discriminator value
      const uniqueMembers = new Map<string, { member: MemberRouting; leaves: TsInterface[] }>();
      for (const { member, leaf } of memberLeafs) {
        const existing = uniqueMembers.get(member.name);
        if (existing) {
          existing.leaves.push(leaf);
        } else {
          uniqueMembers.set(member.name, { member, leaves: [leaf] });
        }
      }

      if (uniqueMembers.size === 1) {
        // No collision - simple case
        const [memberName] = [...uniqueMembers.keys()];
        if (memberName) {
          values.set(value, memberName);
        }
      } else {
        // Collision - need disambiguation rules
        const rules = this.buildDisambiguationRules(uniqueMembers);
        disambiguations.set(value, rules);
      }
    }

    // Need at least some values or disambiguations
    if (values.size === 0 && disambiguations.size === 0) {
      return undefined;
    }

    return {
      field: discriminatorField,
      values,
      disambiguations,
    };
  }

  /**
   * Builds disambiguation rules for members that share the same discriminator value.
   * Uses field presence to distinguish between members.
   */
  private buildDisambiguationRules(
    uniqueMembers: Map<string, { member: MemberRouting; leaves: TsInterface[] }>
  ): DisambiguationRule[] {
    const rules: DisambiguationRule[] = [];
    const memberEntries = [...uniqueMembers.entries()];

    // Collect all fields from all leaves of all members
    const memberFields = new Map<string, Set<string>>();
    for (const [memberName, { leaves }] of memberEntries) {
      const fields = new Set<string>();
      for (const leaf of leaves) {
        for (const prop of leaf.properties) {
          fields.add(prop.name);
        }
      }
      memberFields.set(memberName, fields);
    }

    // Track members that need a fallback route
    // (they have leaves that can't be uniquely distinguished)
    const membersNeedingFallback = new Set<string>();

    // Find distinguishing fields for each member
    for (const [memberName, { member, leaves }] of memberEntries) {
      const thisFields = memberFields.get(memberName) ?? new Set();

      // Find fields that this member has but others don't
      let uniqueField: string | undefined;
      for (const field of thisFields) {
        // Skip the discriminator field itself and common fields
        if (field === 'type' || field === 'default' || field === 'title' || field === 'description') {
          continue;
        }

        const isUnique = memberEntries.every(([otherName, { leaves: otherLeaves }]) => {
          if (otherName === memberName) return true;
          // Check if any other member's leaves have this field
          return !otherLeaves.some((leaf) => leaf.properties.some((p) => p.name === field));
        });

        if (isUnique) {
          uniqueField = field;
          break;
        }
      }

      if (uniqueField) {
        rules.push({
          memberName,
          isUnion: member.isUnion,
          requiredField: uniqueField,
        });

        // If this member has leaves that DON'T have the unique field,
        // it also needs to be a fallback (e.g., SingleSelectEnumSchema has
        // TitledSingleSelectEnumSchema with oneOf, but also UntitledSingleSelectEnumSchema without)
        const hasLeavesWithoutField = leaves.some(
          (leaf) => !leaf.properties.some((p) => p.name === uniqueField)
        );
        if (hasLeavesWithoutField) {
          membersNeedingFallback.add(memberName);
        }
      } else {
        // No unique field found - this member needs to be fallback
        membersNeedingFallback.add(memberName);
      }
    }

    // Add fallback rule - exactly ONE fallback is needed when disambiguating
    // The fallback handles the case when NONE of the distinguishing fields match
    //
    // Key insight: The fallback should be the "base type" - typically a direct interface
    // (non-union) rather than a union with complex variants. Even if a member has
    // a distinguishing rule (like StringSchema with 'minLength'), it should still
    // be the fallback when it's the simpler/base type.
    //
    // Priority: non-unions (direct interfaces) > unions
    // Rationale: Unions have members with distinguishing fields (enum, oneOf, etc.)
    //            If those fields are absent, fall back to the base type.

    // ALL members are fallback candidates - we need to pick the best one
    const fallbackCandidates = memberEntries.map(([memberName, { member }]) => ({
      memberName,
      member,
    }));

    // Sort: prefer non-unions (direct interfaces) over unions
    fallbackCandidates.sort((a, b) => {
      // Non-unions first (false < true when converted to number)
      if (a.member.isUnion !== b.member.isUnion) {
        return a.member.isUnion ? 1 : -1;
      }
      return 0;
    });

    const bestFallback = fallbackCandidates[0];
    if (bestFallback) {
      rules.push({
        memberName: bestFallback.memberName,
        isUnion: bestFallback.member.isUnion,
        isFallback: true,
      });
    }

    return rules;
  }

  /**
   * Extracts const values from a literal type or union of literal types.
   *
   * Handles:
   * - Single literal: "string" → ["string"]
   * - Union of literals: "number" | "integer" → ["number", "integer"]
   *
   * @returns Array of literal values, or undefined if not a literal type
   */
  private extractConstValues(type: string): string[] | undefined {
    // Split by | to handle union types like "number" | "integer"
    const alternatives = type.split(/\s*\|\s*/);
    const values: string[] = [];

    for (const alt of alternatives) {
      const trimmed = alt.trim();
      // Match single or double quoted strings
      const match = trimmed.match(/^["'](.+)["']$/);
      if (match?.[1]) {
        values.push(match[1]);
      }
    }

    return values.length > 0 ? values : undefined;
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
   * Renders the PHP factory class with proper routing for unions vs interfaces.
   * Routes union type alias members to their factories, interface members directly to DTOs.
   */
  private renderFactoryWithRouting(
    name: string,
    routing: MemberRouting[],
    classification: DomainClassification,
    description: string | undefined,
    discriminator: DiscriminatorInfo,
    indent: string
  ): string {
    const lines: string[] = [];
    const namespace = this.getNamespace(classification);
    const factoryName = `${name}Factory`;
    const interfaceName = `${name}Interface`;

    // Build a map of member name to routing info for lookup
    const routingMap = new Map(routing.map((r) => [r.name, r]));

    // Determine if this factory has any disambiguation cases
    const hasDisambiguation = discriminator.disambiguations.size > 0;

    // PHP opening tag
    lines.push('<?php');
    lines.push('');
    lines.push('declare(strict_types=1);');
    lines.push('');

    // Namespace
    lines.push(`namespace ${namespace};`);
    lines.push('');

    // Use statements (version is in directory structure but NOT in namespace)
    const unionNamespace = `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Union`;
    lines.push(`use ${unionNamespace}\\${interfaceName};`);

    // Import members - factories for unions, DTOs for interfaces
    for (const member of routing) {
      if (member.isUnion) {
        // Import the factory for union type aliases
        // Union factories are in the Factory namespace
        const memberAlias = this.typeAliases.find((a) => a.name === member.name);
        if (memberAlias) {
          const memberClassification = this.classifier.classify(member.name, memberAlias.tags);
          const factoryNamespace = `${this.config.output.namespace}\\${memberClassification.domain}\\${memberClassification.subdomain}\\Factory`;
          lines.push(`use ${factoryNamespace}\\${member.name}Factory;`);
        }
      } else {
        // Import the DTO directly for interfaces
        const memberIface = this.interfaces.find((i) => i.name === member.name);
        if (memberIface) {
          const memberClassification = this.classifier.classify(member.name, memberIface.tags);
          const memberNamespace = `${this.config.output.namespace}\\${memberClassification.domain}\\${memberClassification.subdomain}\\DTO`;
          lines.push(`use ${memberNamespace}\\${member.name};`);
        }
      }
    }
    lines.push('');

    // Class docblock
    lines.push('/**');
    if (description) {
      lines.push(` * Factory for creating ${name} union type instances.`);
      lines.push(' *');
      lines.push(...formatPhpDocDescription(description));
    } else {
      lines.push(` * Factory for creating ${name} union type instances.`);
    }
    lines.push(' *');
    lines.push(` * @mcp-domain ${classification.domain}`);
    lines.push(` * @mcp-subdomain ${classification.subdomain}`);
    lines.push(` * @mcp-version ${this.config.schema.version}`);
    lines.push(' */');

    // Class declaration
    lines.push(`final class ${factoryName}`);
    lines.push('{');

    // Check if any routing goes to another factory (for PHPDoc type annotation)
    const hasFactoryRouting = routing.some((r) => r.isUnion);

    // REGISTRY constant - maps discriminator values to class names
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Registry mapping discriminator values to implementation classes.`);
    if (hasFactoryRouting) {
      lines.push(`${indent} * Note: Some values route to other factories for nested union resolution.`);
    }
    lines.push(`${indent} *`);
    // Use stricter type when all routes are to DTOs, looser type when some route to factories
    if (hasFactoryRouting) {
      lines.push(`${indent} * @var array<string, class-string>`);
    } else {
      lines.push(`${indent} * @var array<string, class-string<${interfaceName}>>`);
    }
    lines.push(`${indent} */`);
    lines.push(`${indent}public const REGISTRY = [`);

    // Add simple (non-colliding) cases to registry
    for (const [value, memberName] of discriminator.values) {
      const memberRouting = routingMap.get(memberName);
      const target = memberRouting?.isUnion ? `${memberName}Factory` : memberName;
      lines.push(`${indent}${indent}'${value}' => ${target}::class,`);
    }

    // Add disambiguation cases to registry (use the primary/fallback target)
    for (const [value, rules] of discriminator.disambiguations) {
      // Use fallback as primary, or first rule if no fallback
      const fallbackRule = rules.find((r) => r.isFallback);
      const primaryRule = fallbackRule ?? rules[0];
      if (primaryRule) {
        const target = primaryRule.isUnion ? `${primaryRule.memberName}Factory` : primaryRule.memberName;
        lines.push(`${indent}${indent}'${value}' => ${target}::class,`);
      }
    }

    lines.push(`${indent}];`);
    lines.push('');

    const field = discriminator.field;

    // fromArray method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Creates an instance from an array.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param array<string, mixed> $data`);
    lines.push(`${indent} * @return ${interfaceName}`);
    lines.push(`${indent} * @throws \\InvalidArgumentException`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function fromArray(array $data): ${interfaceName}`);
    lines.push(`${indent}{`);

    lines.push(`${indent}${indent}if (!isset($data['${field}'])) {`);
    lines.push(`${indent}${indent}${indent}throw new \\InvalidArgumentException('Missing discriminator field: ${field}');`);
    lines.push(`${indent}${indent}}`);
    lines.push('');

    if (hasDisambiguation) {
      // Use switch for disambiguation cases
      lines.push(`${indent}${indent}switch ($data['${field}']) {`);

      // Handle simple (non-colliding) cases
      for (const [value, memberName] of discriminator.values) {
        const memberRouting = routingMap.get(memberName);
        const target = memberRouting?.isUnion ? `${memberName}Factory` : memberName;

        lines.push(`${indent}${indent}${indent}case '${value}':`);
        lines.push(`${indent}${indent}${indent}${indent}return ${target}::fromArray($data);`);
      }

      // Handle disambiguation cases (colliding discriminator values)
      for (const [value, rules] of discriminator.disambiguations) {
        lines.push(`${indent}${indent}${indent}case '${value}':`);

        // Generate if/elseif chain for disambiguation
        let isFirst = true;
        const fallbackRule = rules.find((r) => r.isFallback);
        const nonFallbackRules = rules.filter((r) => !r.isFallback);

        for (const rule of nonFallbackRules) {
          const target = rule.isUnion ? `${rule.memberName}Factory` : rule.memberName;
          const keyword = isFirst ? 'if' : 'elseif';

          if (rule.requiredField) {
            lines.push(`${indent}${indent}${indent}${indent}${keyword} (isset($data['${rule.requiredField}'])) {`);
            lines.push(`${indent}${indent}${indent}${indent}${indent}return ${target}::fromArray($data);`);
            lines.push(`${indent}${indent}${indent}${indent}}`);
            isFirst = false;
          }
        }

        // Handle fallback (no field check)
        if (fallbackRule) {
          const target = fallbackRule.isUnion ? `${fallbackRule.memberName}Factory` : fallbackRule.memberName;
          if (nonFallbackRules.length > 0) {
            lines.push(`${indent}${indent}${indent}${indent}else {`);
            lines.push(`${indent}${indent}${indent}${indent}${indent}return ${target}::fromArray($data);`);
            lines.push(`${indent}${indent}${indent}${indent}}`);
          } else {
            lines.push(`${indent}${indent}${indent}${indent}return ${target}::fromArray($data);`);
          }
        } else if (nonFallbackRules.length > 0) {
          // No fallback, throw exception for unmatched cases
          lines.push(`${indent}${indent}${indent}${indent}throw new \\InvalidArgumentException(`);
          lines.push(`${indent}${indent}${indent}${indent}${indent}'Cannot determine type for ${field}=' . $data['${field}']`);
          lines.push(`${indent}${indent}${indent}${indent});`);
        }
      }

      lines.push(`${indent}${indent}${indent}default:`);
      lines.push(`${indent}${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(`);
      lines.push(`${indent}${indent}${indent}${indent}${indent}"Unknown ${field} value '%s'. Valid values: %s",`);
      lines.push(`${indent}${indent}${indent}${indent}${indent}is_scalar($data['${field}']) ? $data['${field}'] : gettype($data['${field}']),`);
      lines.push(`${indent}${indent}${indent}${indent}${indent}implode(', ', array_keys(self::REGISTRY))`);
      lines.push(`${indent}${indent}${indent}${indent}));`);
      lines.push(`${indent}${indent}}`);
    } else {
      // Simple REGISTRY-based routing (no disambiguation needed)
      lines.push(`${indent}${indent}/** @var string $${field} */`);
      lines.push(`${indent}${indent}$${field} = $data['${field}'];`);
      lines.push(`${indent}${indent}if (!isset(self::REGISTRY[$${field}])) {`);
      lines.push(`${indent}${indent}${indent}throw new \\InvalidArgumentException(sprintf(`);
      lines.push(`${indent}${indent}${indent}${indent}"Unknown ${field} value '%s'. Valid values: %s",`);
      lines.push(`${indent}${indent}${indent}${indent}$${field},`);
      lines.push(`${indent}${indent}${indent}${indent}implode(', ', array_keys(self::REGISTRY))`);
      lines.push(`${indent}${indent}${indent}));`);
      lines.push(`${indent}${indent}}`);
      lines.push('');
      lines.push(`${indent}${indent}$class = self::REGISTRY[$${field}];`);
      lines.push(`${indent}${indent}return $class::fromArray($data);`);
    }

    lines.push(`${indent}}`);
    lines.push('');

    // supports() method
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Checks if a ${field} value is supported by this factory.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @param string $${field}`);
    lines.push(`${indent} * @return bool`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function supports(string $${field}): bool`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return isset(self::REGISTRY[$${field}]);`);
    lines.push(`${indent}}`);
    lines.push('');

    // methods() or types() method (use field name)
    const methodName = field === 'method' ? 'methods' : `${field}s`;
    lines.push(`${indent}/**`);
    lines.push(`${indent} * Returns all supported ${field} values.`);
    lines.push(`${indent} *`);
    lines.push(`${indent} * @return array<string>`);
    lines.push(`${indent} */`);
    lines.push(`${indent}public static function ${methodName}(): array`);
    lines.push(`${indent}{`);
    lines.push(`${indent}${indent}return array_keys(self::REGISTRY);`);
    lines.push(`${indent}}`);

    // Closing brace
    lines.push('}');
    lines.push('');

    return lines.join('\n');
  }

  /**
   * Gets the PHP namespace for a classification.
   * The configured namespace already contains a legal V-prefixed revision segment.
   */
  private getNamespace(classification: DomainClassification): string {
    return `${this.config.output.namespace}\\${classification.domain}\\${classification.subdomain}\\Factory`;
  }
}

/**
 * MCP PHP Schema Generator - Inheritance Graph
 *
 * Builds and manages inheritance relationships between TypeScript interfaces.
 * Used for determining generation order and property classification.
 */

import type { TsInterface, TsProperty } from '../types/index.js';

/**
 * Represents the inheritance graph for all interfaces.
 */
export interface InheritanceGraph {
  /** Maps type name → parent type names (direct parents only) */
  readonly parents: ReadonlyMap<string, readonly string[]>;
  /** Maps type name → child type names (direct children only) */
  readonly children: ReadonlyMap<string, readonly string[]>;
  /** Type names that have no MCP parents (root of inheritance chains) */
  readonly roots: readonly string[];
  /** All type names in the graph */
  readonly allTypes: ReadonlySet<string>;
}

/**
 * TypeScript built-in types that should not be treated as MCP parents.
 * These are the types that indicate a "root" in our inheritance hierarchy.
 */
const BUILTIN_TYPES: Set<string> = new Set([
  // No built-in base types in MCP schema - all interfaces are standalone
  // or extend other MCP interfaces
]);

/**
 * Builds an inheritance graph from TypeScript interfaces.
 *
 * @param interfaces - The parsed TypeScript interfaces
 * @returns The complete inheritance graph
 */
export function buildInheritanceGraph(interfaces: readonly TsInterface[]): InheritanceGraph {
  const typeNames = new Set(interfaces.map((i) => i.name));
  const parents = new Map<string, string[]>();
  const children = new Map<string, string[]>();
  const roots: string[] = [];

  // Initialize maps for all types
  for (const iface of interfaces) {
    parents.set(iface.name, []);
    children.set(iface.name, []);
  }

  // Build parent/child relationships
  for (const iface of interfaces) {
    // Filter to only include parents that are in our interface list (MCP types)
    const mcpParents = iface.extends.filter((p) => typeNames.has(p) && !BUILTIN_TYPES.has(p));

    parents.set(iface.name, mcpParents);

    // Add this type as a child of each parent
    for (const parentName of mcpParents) {
      const parentChildren = children.get(parentName) ?? [];
      parentChildren.push(iface.name);
      children.set(parentName, parentChildren);
    }

    // If no MCP parents, this is a root type
    if (mcpParents.length === 0) {
      roots.push(iface.name);
    }
  }

  return {
    parents: parents as ReadonlyMap<string, readonly string[]>,
    children: children as ReadonlyMap<string, readonly string[]>,
    roots,
    allTypes: typeNames,
  };
}

/**
 * Gets all ancestors of a type (parents, grandparents, etc.).
 * Returns them in order from nearest to furthest ancestor.
 */
export function getAncestors(
  typeName: string,
  graph: InheritanceGraph,
  visited: Set<string> = new Set()
): string[] {
  if (visited.has(typeName)) {
    return []; // Prevent circular inheritance
  }
  visited.add(typeName);

  const directParents = graph.parents.get(typeName) ?? [];
  const ancestors: string[] = [...directParents];

  for (const parent of directParents) {
    ancestors.push(...getAncestors(parent, graph, visited));
  }

  return ancestors;
}

/**
 * Gets all descendants of a type (children, grandchildren, etc.).
 * Returns them in order from nearest to furthest descendant.
 */
export function getDescendants(
  typeName: string,
  graph: InheritanceGraph,
  visited: Set<string> = new Set()
): string[] {
  if (visited.has(typeName)) {
    return []; // Prevent circular inheritance
  }
  visited.add(typeName);

  const directChildren = graph.children.get(typeName) ?? [];
  const descendants: string[] = [...directChildren];

  for (const child of directChildren) {
    descendants.push(...getDescendants(child, graph, visited));
  }

  return descendants;
}

/**
 * Gets the immediate (direct) parent of a type.
 * Returns undefined if the type is a root (extends AbstractDataTransferObject).
 * For multiple inheritance, returns the first parent.
 */
export function getDirectParent(typeName: string, graph: InheritanceGraph): string | undefined {
  const directParents = graph.parents.get(typeName) ?? [];
  return directParents[0];
}

/**
 * Checks if a type is a root type (no MCP parents).
 */
export function isRoot(typeName: string, graph: InheritanceGraph): boolean {
  return graph.roots.includes(typeName);
}

/**
 * Gets the depth of a type in the inheritance hierarchy.
 * Root types have depth 0, their children have depth 1, etc.
 */
export function getDepth(typeName: string, graph: InheritanceGraph): number {
  const directParent = getDirectParent(typeName, graph);
  if (!directParent) {
    return 0;
  }
  return 1 + getDepth(directParent, graph);
}

/**
 * Returns inheritance chain statistics for debugging/logging.
 */
export function getInheritanceStats(graph: InheritanceGraph): {
  totalTypes: number;
  rootTypes: number;
  maxDepth: number;
  typesWithChildren: number;
  inheritanceChains: { root: string; depth: number; descendants: number }[];
} {
  let maxDepth = 0;
  let typesWithChildren = 0;
  const inheritanceChains: { root: string; depth: number; descendants: number }[] = [];

  for (const typeName of graph.allTypes) {
    const depth = getDepth(typeName, graph);
    maxDepth = Math.max(maxDepth, depth);

    const childCount = (graph.children.get(typeName) ?? []).length;
    if (childCount > 0) {
      typesWithChildren++;
    }
  }

  // Build chain info for root types
  for (const root of graph.roots) {
    const descendants = getDescendants(root, graph);
    if (descendants.length > 0) {
      // Calculate max depth from this root
      let chainDepth = 0;
      for (const desc of descendants) {
        chainDepth = Math.max(chainDepth, getDepth(desc, graph));
      }
      inheritanceChains.push({
        root,
        depth: chainDepth,
        descendants: descendants.length,
      });
    }
  }

  // Sort chains by descendant count (most significant first)
  inheritanceChains.sort((a, b) => b.descendants - a.descendants);

  return {
    totalTypes: graph.allTypes.size,
    rootTypes: graph.roots.length,
    maxDepth,
    typesWithChildren,
    inheritanceChains,
  };
}

/**
 * Prints a tree representation of the inheritance hierarchy.
 * Useful for debugging and understanding the structure.
 */
export function printInheritanceTree(
  graph: InheritanceGraph,
  maxRoots: number = 10
): string {
  const lines: string[] = [];
  const significantRoots = graph.roots
    .filter((r) => (graph.children.get(r) ?? []).length > 0)
    .slice(0, maxRoots);

  for (const root of significantRoots) {
    printNode(root, graph, lines, '', true);
    lines.push('');
  }

  return lines.join('\n');
}

function printNode(
  typeName: string,
  graph: InheritanceGraph,
  lines: string[],
  prefix: string,
  isLast: boolean
): void {
  const connector = isLast ? '└── ' : '├── ';
  lines.push(`${prefix}${connector}${typeName}`);

  const children = graph.children.get(typeName) ?? [];
  const childPrefix = prefix + (isLast ? '    ' : '│   ');

  for (let i = 0; i < children.length; i++) {
    printNode(children[i]!, graph, lines, childPrefix, i === children.length - 1);
  }
}

// ============================================================================
// Topological Sort
// ============================================================================

/**
 * Result of topological sort operation.
 */
export interface TopologicalSortResult {
  /** Types in topological order (parents before children) */
  readonly sorted: readonly string[];
  /** True if sorting was successful (no cycles) */
  readonly success: boolean;
  /** Types involved in a cycle, if any */
  readonly cycleTypes?: readonly string[];
}

/**
 * Performs topological sort on the inheritance graph using Kahn's algorithm.
 *
 * Returns types in an order where parent classes come before their children,
 * ensuring that when generating PHP files, parent classes exist before
 * child classes reference them.
 *
 * @example
 * // Returns: ['Request', 'JSONRPCRequest', 'InitializeRequest', ...]
 * const result = topologicalSort(graph);
 *
 * @param graph - The inheritance graph
 * @returns Sorted type names with success status
 */
export function topologicalSort(graph: InheritanceGraph): TopologicalSortResult {
  // Calculate in-degree (number of parents) for each type
  const inDegree = new Map<string, number>();
  for (const typeName of graph.allTypes) {
    const parentCount = (graph.parents.get(typeName) ?? []).length;
    inDegree.set(typeName, parentCount);
  }

  // Start with nodes that have no parents (in-degree = 0)
  const queue: string[] = [];
  for (const typeName of graph.allTypes) {
    if (inDegree.get(typeName) === 0) {
      queue.push(typeName);
    }
  }

  // Sort the initial queue for deterministic output
  queue.sort();

  const sorted: string[] = [];

  while (queue.length > 0) {
    // Process nodes at same level in sorted order (deterministic)
    const current = queue.shift()!;
    sorted.push(current);

    // "Remove" edges from this node by decrementing in-degree of children
    const children = graph.children.get(current) ?? [];
    const newlyReady: string[] = [];

    for (const child of children) {
      const currentInDegree = inDegree.get(child) ?? 0;
      inDegree.set(child, currentInDegree - 1);

      // If all parents have been processed, this child is ready
      if (currentInDegree - 1 === 0) {
        newlyReady.push(child);
      }
    }

    // Sort newly ready nodes for deterministic output
    newlyReady.sort();
    queue.push(...newlyReady);
  }

  // Check for cycles - if we didn't process all nodes, there's a cycle
  if (sorted.length < graph.allTypes.size) {
    const cycleTypes = [...graph.allTypes].filter((t) => !sorted.includes(t));
    return {
      sorted,
      success: false,
      cycleTypes,
    };
  }

  return {
    sorted,
    success: true,
  };
}

/**
 * Sorts interfaces for generation, ensuring parents come before children.
 *
 * This is a convenience wrapper around topologicalSort that works directly
 * with TsInterface arrays, returning the interfaces in the correct order.
 *
 * @param interfaces - The interfaces to sort
 * @param graph - The precomputed inheritance graph (optional, will be built if not provided)
 * @returns Interfaces in topological order
 * @throws Error if circular dependencies are detected
 */
export function sortInterfacesForGeneration(
  interfaces: readonly TsInterface[],
  graph?: InheritanceGraph
): TsInterface[] {
  const inheritanceGraph = graph ?? buildInheritanceGraph(interfaces);
  const result = topologicalSort(inheritanceGraph);

  if (!result.success) {
    throw new Error(
      `Circular inheritance detected involving types: ${result.cycleTypes?.join(', ')}`
    );
  }

  // Create a map for O(1) lookup
  const interfaceMap = new Map(interfaces.map((i) => [i.name, i]));

  // Return interfaces in sorted order
  return result.sorted
    .map((name) => interfaceMap.get(name))
    .filter((i): i is TsInterface => i !== undefined);
}

// ============================================================================
// Property Classification
// ============================================================================

/**
 * Classification of properties for inheritance-aware code generation.
 */
export interface PropertyClassification {
  /** Properties defined directly in this type (not in any parent) */
  readonly ownProperties: readonly TsProperty[];
  /** Properties inherited from parent types (should not be redeclared) */
  readonly inheritedProperties: readonly TsProperty[];
  /** Properties with same name as parent but narrower/different type */
  readonly narrowedProperties: readonly NarrowedProperty[];
  /** All properties (own + inherited, with narrowed replacing inherited) */
  readonly allProperties: readonly TsProperty[];
}

/**
 * A property that narrows a parent property's type.
 */
export interface NarrowedProperty {
  /** The property as defined in this type */
  readonly property: TsProperty;
  /** The original property from the parent */
  readonly parentProperty: TsProperty;
  /** The parent type name where the original property is defined */
  readonly parentTypeName: string;
}

/**
 * Classifies properties into own, inherited, and narrowed categories.
 *
 * This is the core function for inheritance-aware code generation.
 * It determines which properties should be declared in a class vs inherited.
 *
 * IMPORTANT: PHP only supports single inheritance. For TypeScript interfaces
 * that extend multiple parents (e.g., `extends Result, SamplingMessage`),
 * only properties from the FIRST parent chain are "inherited" in PHP.
 * Properties from other TypeScript parents become "own" properties.
 *
 * @param iface - The interface to classify properties for
 * @param graph - The inheritance graph
 * @param interfaceMap - Map of all interfaces by name
 * @returns Classification of properties
 *
 * @example
 * // For CreateMessageResult extends Result, SamplingMessage:
 * // - PHP extends: Result (first parent)
 * // - inherited: _meta (from Result)
 * // - own: role, content (from SamplingMessage - treated as own in PHP!)
 * // - own: model, stopReason (defined directly)
 */
export function classifyProperties(
  iface: TsInterface,
  graph: InheritanceGraph,
  interfaceMap: ReadonlyMap<string, TsInterface>
): PropertyClassification {
  // Get all direct parents from TypeScript
  const allDirectParents = graph.parents.get(iface.name) ?? [];

  // PHP parent is the first parent (PHP single inheritance)
  const phpParentName = allDirectParents[0];

  // Other TypeScript parents (not the PHP parent)
  const nonPhpParents = allDirectParents.slice(1);

  // Collect properties from the PHP parent chain ONLY
  // These are the properties that will be truly inherited in PHP
  const inheritedPropsMap = new Map<string, { prop: TsProperty; fromType: string }>();

  if (phpParentName) {
    // Get ancestors of the PHP parent chain only
    const phpAncestors = [phpParentName, ...getAncestors(phpParentName, graph)];

    for (const ancestorName of phpAncestors) {
      const ancestor = interfaceMap.get(ancestorName);
      if (!ancestor) continue;

      for (const prop of ancestor.properties) {
        // Only add if not already present (nearer ancestors take precedence)
        if (!inheritedPropsMap.has(prop.name)) {
          inheritedPropsMap.set(prop.name, { prop, fromType: ancestorName });
        }
      }
    }
  }

  // Collect properties from non-PHP TypeScript parents
  // These must become OWN properties in PHP (since we can only extend one class)
  const nonPhpParentProps = new Map<string, TsProperty>();
  for (const parentName of nonPhpParents) {
    // Include the parent itself and its entire ancestor chain
    const ancestorChain = [parentName, ...getAncestors(parentName, graph)];

    for (const ancestorName of ancestorChain) {
      const ancestor = interfaceMap.get(ancestorName);
      if (!ancestor) continue;

      for (const prop of ancestor.properties) {
        // Only add if not already present and not in PHP parent chain
        if (!nonPhpParentProps.has(prop.name) && !inheritedPropsMap.has(prop.name)) {
          nonPhpParentProps.set(prop.name, prop);
        }
      }
    }
  }

  // Classify this interface's direct properties
  const ownProperties: TsProperty[] = [];
  const narrowedProperties: NarrowedProperty[] = [];

  for (const prop of iface.properties) {
    const inheritedInfo = inheritedPropsMap.get(prop.name);

    if (!inheritedInfo) {
      // Property is not in the PHP parent chain - it's own
      ownProperties.push(prop);
    } else if (isTypeNarrowed(prop, inheritedInfo.prop)) {
      // Property exists in parent but with different/broader type - it's narrowed
      narrowedProperties.push({
        property: prop,
        parentProperty: inheritedInfo.prop,
        parentTypeName: inheritedInfo.fromType,
      });
    }
    // If property exists in PHP parent with same type, it's just inherited (skip)
  }

  // Add properties from non-PHP TypeScript parents as own properties
  // (since PHP can't inherit from multiple classes)
  for (const [name, prop] of nonPhpParentProps) {
    // Only add if not already in ownProperties
    if (!ownProperties.some((p) => p.name === name)) {
      ownProperties.push(prop);
    }
  }

  // Build inherited properties list (excluding narrowed ones)
  const narrowedNames = new Set(narrowedProperties.map((n) => n.property.name));
  const inheritedProperties: TsProperty[] = [];
  for (const [name, info] of inheritedPropsMap) {
    if (!narrowedNames.has(name)) {
      inheritedProperties.push(info.prop);
    }
  }

  // Build allProperties (own + inherited, with narrowed replacing inherited where applicable)
  const allProperties: TsProperty[] = [];
  const addedNames = new Set<string>();

  // First add inherited properties
  for (const prop of inheritedProperties) {
    allProperties.push(prop);
    addedNames.add(prop.name);
  }

  // Then add narrowed properties (replacing any inherited)
  for (const narrowed of narrowedProperties) {
    allProperties.push(narrowed.property);
    addedNames.add(narrowed.property.name);
  }

  // Finally add own properties
  for (const prop of ownProperties) {
    if (!addedNames.has(prop.name)) {
      allProperties.push(prop);
      addedNames.add(prop.name);
    }
  }

  return {
    ownProperties,
    inheritedProperties,
    narrowedProperties,
    allProperties,
  };
}

/**
 * Checks if a property's type is narrowed compared to a parent property.
 *
 * Type narrowing occurs when:
 * - The types are textually different (more specific type in child)
 * - The child type is a subtype of the parent type
 *
 * Examples:
 * - `params?: object` → `params: InitializeRequestParams` (narrowed)
 * - `params?: array` → `params?: array` (not narrowed)
 */
function isTypeNarrowed(childProp: TsProperty, parentProp: TsProperty): boolean {
  // If types are identical (after normalization), not narrowed
  const childType = normalizeType(childProp.type);
  const parentType = normalizeType(parentProp.type);

  if (childType === parentType) {
    // Check optionality - if child is required but parent is optional, it's narrowed
    if (!childProp.isOptional && parentProp.isOptional) {
      return true;
    }
    return false;
  }

  // Types are different - this is a narrowing
  return true;
}

/**
 * Normalizes a type string for comparison.
 */
function normalizeType(type: string): string {
  return type
    .replace(/\s+/g, ' ') // Normalize whitespace
    .replace(/\s*\|\s*/g, ' | ') // Normalize union spacing
    .trim();
}

/**
 * Gets properties that are ONLY defined in this interface (not inherited).
 * This is the list of properties that should be declared in a PHP class
 * that properly extends its parent.
 */
export function getOwnProperties(
  iface: TsInterface,
  graph: InheritanceGraph,
  interfaceMap: ReadonlyMap<string, TsInterface>
): readonly TsProperty[] {
  const classification = classifyProperties(iface, graph, interfaceMap);
  return classification.ownProperties;
}

/**
 * Convenience function to build an interface map from an array.
 */
export function buildInterfaceMap(
  interfaces: readonly TsInterface[]
): ReadonlyMap<string, TsInterface> {
  return new Map(interfaces.map((i) => [i.name, i]));
}

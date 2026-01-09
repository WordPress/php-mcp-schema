/**
 * MCP PHP Schema Generator - Skill Types
 *
 * Type definitions for the Claude Code skill generator.
 * These types represent the output structure for progressive discovery.
 */

// ============================================================================
// Skill Configuration Types
// ============================================================================

/**
 * Configuration for skill generation.
 */
export interface SkillConfig {
  /** Output directory for skill files (relative to project root) */
  readonly outputDir: string;
  /** Whether to generate skill files */
  readonly enabled: boolean;
}

// ============================================================================
// Skill Index Types (Lightweight Discovery)
// ============================================================================

/**
 * Lightweight schema index for quick discovery.
 * Target size: ~2KB
 */
export interface SkillSchemaIndex {
  readonly version: string;
  readonly namespace: string;
  readonly summary: SkillSchemaSummary;
  readonly domains: Record<string, string[]>;
  readonly rpcMethods: SkillRpcMethod[];
  readonly entryPoints: Record<string, string>;
}

/**
 * High-level summary of the schema.
 */
export interface SkillSchemaSummary {
  readonly types: number;
  readonly domains: number;
  readonly subdomains: number;
  readonly rpcMethods: number;
  readonly factories: number;
}

/**
 * RPC method entry for the index.
 */
export interface SkillRpcMethod {
  readonly method: string;
  readonly direction: 'client→server' | 'server→client' | 'bidirectional';
}

// ============================================================================
// Domain Data Types (Split JSON Files)
// ============================================================================

/**
 * Type information for domain JSON files.
 */
export interface SkillTypeInfo {
  readonly kind: 'class' | 'enum' | 'union' | 'factory' | 'constant';
  readonly domain: string;
  readonly subdomain: string;
  readonly namespace: string;
  readonly purpose: string;
  readonly extends?: string;
  readonly implements: string[];
  readonly properties: Record<string, string>;
  readonly usedBy: string[];
  readonly uses: string[];
  readonly discriminator?: {
    readonly field: string;
    readonly value?: string;
  };
}

/**
 * Factory information for domain JSON files.
 */
export interface SkillFactoryInfo {
  readonly interface: string;
  readonly discriminator: string;
  readonly mappings: Record<string, string>;
}

/**
 * Complete domain data structure.
 */
export interface SkillDomainData {
  readonly version: string;
  readonly domain: string;
  readonly types: Record<string, SkillTypeInfo>;
  readonly factories: Record<string, SkillFactoryInfo>;
}

// ============================================================================
// Markdown Reference Types
// ============================================================================

/**
 * Type entry for markdown reference tables.
 */
export interface SkillTypeTableEntry {
  readonly name: string;
  readonly purpose: string;
  readonly keyProperties: string;
}

/**
 * Subdomain section for domain reference files.
 */
export interface SkillSubdomainSection {
  readonly name: string;
  readonly types: SkillTypeTableEntry[];
  readonly relationships: string[];
}

/**
 * RPC method entry for rpc-methods.md.
 */
export interface SkillRpcEntry {
  readonly method: string;
  readonly direction: string;
  readonly request: string;
  readonly params?: string;
  readonly result: string;
}

/**
 * Factory entry for factories.md.
 */
export interface SkillFactoryEntry {
  readonly name: string;
  readonly interface: string;
  readonly discriminator: string;
  readonly mappings: Array<{ value: string; type: string }>;
}

// ============================================================================
// Generated File Types
// ============================================================================

/**
 * Generated skill file.
 */
export interface SkillGeneratedFile {
  readonly path: string;
  readonly content: string;
  readonly type: 'markdown' | 'json' | 'script';
}

/**
 * Result from skill generation.
 */
export interface SkillGenerationResult {
  readonly files: readonly SkillGeneratedFile[];
  readonly stats: SkillGenerationStats;
}

/**
 * Statistics about skill generation.
 */
export interface SkillGenerationStats {
  readonly markdownFiles: number;
  readonly jsonFiles: number;
  readonly scriptFiles: number;
  readonly totalSize: number;
  readonly indexSize: number;
}

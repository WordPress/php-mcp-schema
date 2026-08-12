/**
 * MCP PHP Schema Generator - Skill Generator
 *
 * Generates Claude Code skill files for progressive schema discovery.
 * Produces markdown references, JSON data files, and search scripts.
 */

import type {
  TsInterface,
  TsTypeAlias,
  TsEnum,
  GeneratorConfig,
  UnionMembershipMap,
  McpDomain,
} from '../types/index.js';
import type {
  SkillGeneratedFile,
  SkillGenerationResult,
  SkillSchemaIndex,
  SkillDomainData,
  SkillTypeInfo,
  SkillFactoryInfo,
  SkillTypeTableEntry,
  SkillSubdomainSection,
  SkillRpcEntry,
  SkillFactoryEntry,
} from '../types/skill-types.js';
import type { DomainClassifier } from './domain-classifier.js';
import type { IntersectionTypeWrapperInfo } from './intersection-type-wrapper.js';
import type { SchemaMap } from './schema-map.js';
import { SchemaMapGenerator } from './schema-map.js';
import {
  generateRpcTable,
  generateSubdomainSection,
  generateFactorySection,
  generateTableOfContents,
  generateDomainOverviewTable,
  generateFrontmatter,
  formatKeyProperties,
  extractFirstSentence,
} from './skill-markdown.js';

// ============================================================================
// Skill Generator
// ============================================================================

/**
 * Generates Claude Code skill files from schema data.
 */
export class SkillGenerator {
  private readonly config: GeneratorConfig;
  private readonly interfaces: readonly TsInterface[];
  private readonly typeAliases: readonly TsTypeAlias[];
  private readonly enums: readonly TsEnum[];
  private readonly unionMembershipMap: UnionMembershipMap;
  private readonly classifier: DomainClassifier;
  private readonly intersectionTypes: readonly IntersectionTypeWrapperInfo[];
  private readonly outputDir: string;

  // Cached schema map for reuse
  private schemaMap: SchemaMap | null = null;

  constructor(
    config: GeneratorConfig,
    interfaces: readonly TsInterface[],
    typeAliases: readonly TsTypeAlias[],
    enums: readonly TsEnum[],
    unionMembershipMap: UnionMembershipMap,
    classifier: DomainClassifier,
    intersectionTypes: readonly IntersectionTypeWrapperInfo[],
    outputDir = 'skill'
  ) {
    this.config = config;
    this.interfaces = interfaces;
    this.typeAliases = typeAliases;
    this.enums = enums;
    this.unionMembershipMap = unionMembershipMap;
    this.classifier = classifier;
    this.intersectionTypes = intersectionTypes;
    this.outputDir = outputDir;
  }

  /**
   * Generates all skill files.
   */
  generateAll(): SkillGenerationResult {
    const files: SkillGeneratedFile[] = [];

    // Ensure schema map is generated
    const schemaMap = this.getSchemaMap();

    // Generate SKILL.md (main entry point)
    files.push(this.generateSkillMd(schemaMap));

    // Generate reference markdown files
    files.push(this.generateOverviewMd(schemaMap));
    files.push(this.generateDomainMd('Common', schemaMap));
    files.push(this.generateDomainMd('Server', schemaMap));
    files.push(this.generateDomainMd('Client', schemaMap));
    files.push(this.generateRpcMethodsMd(schemaMap));
    files.push(this.generateFactoriesMd(schemaMap));

    // Generate JSON data files
    files.push(this.generateSchemaIndex(schemaMap));
    files.push(this.generateDomainJson('Common', schemaMap));
    files.push(this.generateDomainJson('Server', schemaMap));
    files.push(this.generateDomainJson('Client', schemaMap));

    // Generate search scripts
    files.push(this.generateSearchTypesScript());
    files.push(this.generateGetTypeScript());
    files.push(this.generateFindRpcScript());

    // Calculate stats
    let totalSize = 0;
    let indexSize = 0;
    for (const file of files) {
      totalSize += file.content.length;
      if (file.path.endsWith('schema-index.json')) {
        indexSize = file.content.length;
      }
    }

    return {
      files,
      stats: {
        markdownFiles: files.filter((f) => f.type === 'markdown').length,
        jsonFiles: files.filter((f) => f.type === 'json').length,
        scriptFiles: files.filter((f) => f.type === 'script').length,
        totalSize,
        indexSize,
      },
    };
  }

  /**
   * Gets or creates the schema map.
   */
  private getSchemaMap(): SchemaMap {
    if (!this.schemaMap) {
      const generator = new SchemaMapGenerator(
        this.config,
        this.interfaces,
        this.typeAliases,
        this.enums,
        this.unionMembershipMap,
        this.classifier,
        this.intersectionTypes
      );
      this.schemaMap = generator.generate();
    }
    return this.schemaMap;
  }

  // ============================================================================
  // SKILL.md Generation
  // ============================================================================

  private generateSkillMd(schemaMap: SchemaMap): SkillGeneratedFile {
    const lines: string[] = [];

    // Frontmatter
    lines.push(generateFrontmatter({
      name: 'mcp-php-schema',
      description: 'Navigate and understand the MCP PHP schema. Use when implementing MCP clients/servers, understanding protocol types, or finding the right DTO for a task.',
    }));
    lines.push('');

    // Title
    lines.push(`# MCP PHP Schema Reference (${this.config.schema.version})`);
    lines.push('');

    // Quick Navigation
    lines.push('## Quick Navigation');
    lines.push('');
    lines.push('- **Server types** (resources, tools, prompts): [reference/server.md](reference/server.md)');
    lines.push('- **Client types** (sampling, elicitation, roots): [reference/client.md](reference/client.md)');
    lines.push('- **Common types** (protocol, JSON-RPC): [reference/common.md](reference/common.md)');
    lines.push('- **RPC methods**: [reference/rpc-methods.md](reference/rpc-methods.md)');
    lines.push('- **Factories**: [reference/factories.md](reference/factories.md)');
    lines.push('');

    // Schema Structure
    const domainStats = this.getDomainStats(schemaMap);
    const totalTypes = domainStats.reduce((sum, d) => sum + d.types, 0);
    const totalSubdomains = Object.keys(schemaMap.domains).length;

    lines.push('## Schema Structure');
    lines.push('');
    lines.push(`${domainStats.length} domains, ${totalSubdomains} subdomains, ${totalTypes} types total.`);
    lines.push('');

    // Domains Overview
    lines.push('### Domains Overview');
    lines.push('');
    lines.push(generateDomainOverviewTable(domainStats));
    lines.push('');

    // Common Patterns
    lines.push('## Common Patterns');
    lines.push('');
    lines.push('### Finding a Request/Result Pair');
    lines.push('');
    lines.push('1. Check [rpc-methods.md](reference/rpc-methods.md) for method name');
    lines.push('2. Look up request type in domain file');
    lines.push('3. Find corresponding result type');
    lines.push('');
    lines.push('### Using Factory Classes');
    lines.push('');
    lines.push('Factories create the correct DTO from discriminator values.');
    lines.push('See [factories.md](reference/factories.md) for patterns.');
    lines.push('');

    // JSON Data
    lines.push('## JSON Data Files');
    lines.push('');
    lines.push('For programmatic access:');
    lines.push('');
    lines.push('- `data/schema-index.json` - Lightweight discovery index');
    lines.push('- `data/schema-common.json` - Common domain types');
    lines.push('- `data/schema-server.json` - Server domain types');
    lines.push('- `data/schema-client.json` - Client domain types');
    lines.push('');

    // Search Scripts
    lines.push('## Search Scripts');
    lines.push('');
    lines.push('```bash');
    lines.push('# Search types by name');
    lines.push('./scripts/search-types.sh "Resource"');
    lines.push('');
    lines.push('# Get type details');
    lines.push('./scripts/get-type.sh "CallToolRequest"');
    lines.push('');
    lines.push('# Find RPC method');
    lines.push('./scripts/find-rpc.sh "tools/call"');
    lines.push('```');
    lines.push('');

    return {
      path: `${this.outputDir}/SKILL.md`,
      content: lines.join('\n'),
      type: 'markdown',
    };
  }

  // ============================================================================
  // Reference Markdown Generation
  // ============================================================================

  private generateOverviewMd(schemaMap: SchemaMap): SkillGeneratedFile {
    const lines: string[] = [];

    lines.push('# MCP PHP Schema Overview');
    lines.push('');
    lines.push(`Version: ${schemaMap.version}`);
    lines.push(`Namespace: \`${schemaMap.namespace}\``);
    lines.push('');

    // Architecture
    lines.push('## Architecture');
    lines.push('');
    lines.push('The schema follows the Model Context Protocol specification.');
    lines.push('Types are organized into three domains:');
    lines.push('');
    lines.push('- **Common**: Base types, JSON-RPC, content blocks');
    lines.push('- **Server**: Resources, tools, prompts, logging');
    lines.push('- **Client**: Sampling, elicitation, roots, tasks');
    lines.push('');

    // Type Hierarchy
    lines.push('## Type Hierarchy');
    lines.push('');
    lines.push('```');
    lines.push('Request (base for all requests)');
    lines.push('├── PaginatedRequest');
    lines.push('├── [Domain]Request types');
    lines.push('');
    lines.push('Result (base for all results)');
    lines.push('├── PaginatedResult');
    lines.push('├── [Domain]Result types');
    lines.push('');
    lines.push('Notification (base for notifications)');
    lines.push('├── [Domain]Notification types');
    lines.push('```');
    lines.push('');

    // Union Interfaces
    lines.push('## Union Interfaces');
    lines.push('');
    lines.push('Union types are represented as interfaces with factory classes:');
    lines.push('');
    lines.push('| Union | Purpose |');
    lines.push('| --- | --- |');
    lines.push('| `ClientRequestInterface` | All requests from client to server |');
    lines.push('| `ServerRequestInterface` | All requests from server to client |');
    lines.push('| `ClientResultInterface` | All results for client requests |');
    lines.push('| `ServerResultInterface` | All results for server requests |');
    lines.push('| `ContentBlockInterface` | Text, image, audio, resource content |');
    lines.push('');

    return {
      path: `${this.outputDir}/reference/overview.md`,
      content: lines.join('\n'),
      type: 'markdown',
    };
  }

  private generateDomainMd(domain: McpDomain, schemaMap: SchemaMap): SkillGeneratedFile {
    const lines: string[] = [];
    const domainLower = domain.toLowerCase();

    lines.push(`# ${domain} Domain Types`);
    lines.push('');

    // Get subdomains for this domain
    const subdomains = this.getSubdomainsForDomain(domain, schemaMap);

    // Table of contents
    const tocSections = subdomains.map((sd) => ({
      name: sd.name,
      count: sd.types.length,
    }));
    lines.push(generateTableOfContents(tocSections));

    // Generate each subdomain section
    for (const subdomain of subdomains) {
      lines.push(generateSubdomainSection(subdomain));
    }

    return {
      path: `${this.outputDir}/reference/${domainLower}.md`,
      content: lines.join('\n'),
      type: 'markdown',
    };
  }

  private generateRpcMethodsMd(schemaMap: SchemaMap): SkillGeneratedFile {
    const lines: string[] = [];

    lines.push('# RPC Methods Reference');
    lines.push('');

    // Group by direction
    const clientToServer: SkillRpcEntry[] = [];
    const serverToClient: SkillRpcEntry[] = [];
    const bidirectional: SkillRpcEntry[] = [];

    for (const [method, rpc] of Object.entries(schemaMap.rpc)) {
      const entry: SkillRpcEntry = {
        method,
        direction: rpc.direction,
        request: rpc.request,
        params: rpc.params,
        result: rpc.result,
      };

      if (rpc.direction === 'client→server') {
        clientToServer.push(entry);
      } else if (rpc.direction === 'server→client') {
        serverToClient.push(entry);
      } else {
        bidirectional.push(entry);
      }
    }

    // Client to Server
    if (clientToServer.length > 0) {
      lines.push('## Client → Server');
      lines.push('');
      lines.push(generateRpcTable(clientToServer));
      lines.push('');
    }

    // Server to Client
    if (serverToClient.length > 0) {
      lines.push('## Server → Client');
      lines.push('');
      lines.push(generateRpcTable(serverToClient));
      lines.push('');
    }

    // Bidirectional
    if (bidirectional.length > 0) {
      lines.push('## Bidirectional');
      lines.push('');
      lines.push(generateRpcTable(bidirectional));
      lines.push('');
    }

    return {
      path: `${this.outputDir}/reference/rpc-methods.md`,
      content: lines.join('\n'),
      type: 'markdown',
    };
  }

  private generateFactoriesMd(schemaMap: SchemaMap): SkillGeneratedFile {
    const lines: string[] = [];

    lines.push('# Factory Classes Reference');
    lines.push('');
    lines.push('Factories instantiate the correct DTO based on discriminator values.');

    // Group factories by domain
    const factoriesByDomain = new Map<string, SkillFactoryEntry[]>();

    for (const [name, factory] of Object.entries(schemaMap.factories)) {
      const domain = factory.domain;
      if (!factoriesByDomain.has(domain)) {
        factoriesByDomain.set(domain, []);
      }

      factoriesByDomain.get(domain)!.push({
        name,
        interface: factory.interface,
        discriminator: factory.discriminator,
        mappings: Object.entries(factory.mappings).map(([value, type]) => ({
          value,
          type,
        })),
      });
    }

    // Generate sections by domain
    for (const [domain, factories] of factoriesByDomain) {
      lines.push('');
      lines.push(`## ${domain} Factories`);
      lines.push('');

      for (const factory of factories) {
        lines.push(generateFactorySection(factory));
      }
    }

    return {
      path: `${this.outputDir}/reference/factories.md`,
      content: lines.join('\n'),
      type: 'markdown',
    };
  }

  // ============================================================================
  // JSON Data Generation
  // ============================================================================

  private generateSchemaIndex(schemaMap: SchemaMap): SkillGeneratedFile {
    // Build lightweight index
    const domains: Record<string, string[]> = {};
    for (const key of Object.keys(schemaMap.domains)) {
      const [domainName, subdomain] = key.split('/');
      if (domainName) {
        if (!domains[domainName]) {
          domains[domainName] = [];
        }
        if (subdomain && !domains[domainName].includes(subdomain)) {
          domains[domainName].push(subdomain);
        }
      }
    }

    const rpcMethods = Object.entries(schemaMap.rpc).map(([method, rpc]) => ({
      method,
      direction: rpc.direction,
    }));

    // Build entry points
    const entryPoints: Record<string, string> = {};
    for (const [key, domain] of Object.entries(schemaMap.domains)) {
      if (domain.entryPoints.length > 0) {
        entryPoints[key] = domain.entryPoints[0] ?? '';
      }
    }

    const index: SkillSchemaIndex = {
      version: schemaMap.version,
      namespace: schemaMap.namespace,
      summary: {
        types: Object.keys(schemaMap.types).length,
        domains: Object.keys(domains).length,
        subdomains: Object.keys(schemaMap.domains).length,
        rpcMethods: rpcMethods.length,
        factories: Object.keys(schemaMap.factories).length,
      },
      domains,
      rpcMethods,
      entryPoints,
    };

    return {
      path: `${this.outputDir}/data/schema-index.json`,
      content: JSON.stringify(index, null, 2),
      type: 'json',
    };
  }

  private generateDomainJson(domain: McpDomain, schemaMap: SchemaMap): SkillGeneratedFile {
    const domainLower = domain.toLowerCase();

    // Filter types for this domain
    const types: Record<string, SkillTypeInfo> = {};
    for (const [name, type] of Object.entries(schemaMap.types)) {
      if (type.domain === domain) {
        types[name] = {
          kind: type.kind,
          domain: type.domain,
          subdomain: type.subdomain,
          namespace: type.namespace,
          purpose: type.purpose,
          extends: type.extends,
          implements: type.implements,
          properties: type.properties,
          usedBy: type.usedBy,
          uses: type.uses,
          discriminator: type.discriminator,
        };
      }
    }

    // Filter factories for this domain
    const factories: Record<string, SkillFactoryInfo> = {};
    for (const [name, factory] of Object.entries(schemaMap.factories)) {
      if (factory.domain === domain) {
        factories[name] = {
          interface: factory.interface,
          discriminator: factory.discriminator,
          mappings: factory.mappings,
        };
      }
    }

    const data: SkillDomainData = {
      version: schemaMap.version,
      domain,
      types,
      factories,
    };

    return {
      path: `${this.outputDir}/data/schema-${domainLower}.json`,
      content: JSON.stringify(data, null, 2),
      type: 'json',
    };
  }

  // ============================================================================
  // Search Scripts Generation
  // ============================================================================

  private generateSearchTypesScript(): SkillGeneratedFile {
    const script = `#!/bin/bash
# Search for types by name or property
# Usage: ./search-types.sh <pattern> [domain]
# Output: Matching type names and locations

SKILL_DIR="$(cd "$(dirname "\${BASH_SOURCE[0]}")/.." && pwd)"
PATTERN="$1"
DOMAIN="\${2:-all}"

if [ -z "$PATTERN" ]; then
  echo "Usage: ./search-types.sh <pattern> [domain]"
  echo "  domain: all, common, server, client"
  exit 1
fi

search_file() {
  local file="$1"
  if [ -f "$file" ]; then
    jq -r ".types | to_entries[] | select(.key | test(\\"$PATTERN\\"; \\"i\\")) | \\"\\(.key) (\\(.value.domain)/\\(.value.subdomain))\\"" "$file" 2>/dev/null
  fi
}

if [ "$DOMAIN" = "all" ]; then
  for f in "$SKILL_DIR"/data/schema-*.json; do
    if [ "$(basename "$f")" != "schema-index.json" ]; then
      search_file "$f"
    fi
  done
else
  search_file "$SKILL_DIR/data/schema-$DOMAIN.json"
fi
`;

    return {
      path: `${this.outputDir}/scripts/search-types.sh`,
      content: script,
      type: 'script',
    };
  }

  private generateGetTypeScript(): SkillGeneratedFile {
    const script = `#!/bin/bash
# Get full details for a specific type
# Usage: ./get-type.sh <TypeName>
# Output: JSON with type details, relationships, usage

SKILL_DIR="$(cd "$(dirname "\${BASH_SOURCE[0]}")/.." && pwd)"
TYPE_NAME="$1"

if [ -z "$TYPE_NAME" ]; then
  echo "Usage: ./get-type.sh <TypeName>"
  exit 1
fi

for f in "$SKILL_DIR"/data/schema-*.json; do
  if [ "$(basename "$f")" != "schema-index.json" ]; then
    result=$(jq -r ".types.\\"$TYPE_NAME\\" // empty" "$f" 2>/dev/null)
    if [ -n "$result" ]; then
      echo "$result" | jq .
      exit 0
    fi
  fi
done

echo "Type '$TYPE_NAME' not found"
exit 1
`;

    return {
      path: `${this.outputDir}/scripts/get-type.sh`,
      content: script,
      type: 'script',
    };
  }

  private generateFindRpcScript(): SkillGeneratedFile {
    const script = `#!/bin/bash
# Find RPC method details
# Usage: ./find-rpc.sh <method-pattern>
# Output: Request/Result types for matching methods

SKILL_DIR="$(cd "$(dirname "\${BASH_SOURCE[0]}")/.." && pwd)"
PATTERN="$1"

if [ -z "$PATTERN" ]; then
  echo "Usage: ./find-rpc.sh <method-pattern>"
  exit 1
fi

jq -r ".rpcMethods[] | select(.method | test(\\"$PATTERN\\"; \\"i\\")) | \\"\\(.method): \\(.direction)\\"" "$SKILL_DIR/data/schema-index.json" 2>/dev/null

# Also search for full details in domain files
for f in "$SKILL_DIR"/data/schema-*.json; do
  if [ "$(basename "$f")" != "schema-index.json" ]; then
    jq -r ".types | to_entries[] | select(.key | endswith(\\"Request\\")) | select(.value.discriminator.value | test(\\"$PATTERN\\"; \\"i\\") // false) | \\"\\(.value.discriminator.value): \\(.key) → \\(.key | sub(\\"Request$\\"; \\"Result\\"))\\"" "$f" 2>/dev/null
  fi
done
`;

    return {
      path: `${this.outputDir}/scripts/find-rpc.sh`,
      content: script,
      type: 'script',
    };
  }

  // ============================================================================
  // Helper Methods
  // ============================================================================

  private getDomainStats(schemaMap: SchemaMap): Array<{ name: string; types: number; purpose: string }> {
    const domainPurposes: Record<string, string> = {
      Common: 'Protocol base, JSON-RPC, content blocks',
      Server: 'Tools, resources, prompts, logging',
      Client: 'Sampling, elicitation, roots, tasks',
    };

    const stats = new Map<string, number>();

    for (const type of Object.values(schemaMap.types)) {
      const count = stats.get(type.domain) ?? 0;
      stats.set(type.domain, count + 1);
    }

    return ['Common', 'Server', 'Client'].map((domain) => ({
      name: domain,
      types: stats.get(domain) ?? 0,
      purpose: domainPurposes[domain] ?? '',
    }));
  }

  private getSubdomainsForDomain(
    domain: McpDomain,
    schemaMap: SchemaMap
  ): SkillSubdomainSection[] {
    const subdomains = new Map<string, SkillTypeTableEntry[]>();
    const relationships = new Map<string, Set<string>>();

    // Group types by subdomain
    for (const [name, type] of Object.entries(schemaMap.types)) {
      if (type.domain !== domain) {
        continue;
      }

      if (!subdomains.has(type.subdomain)) {
        subdomains.set(type.subdomain, []);
        relationships.set(type.subdomain, new Set());
      }

      subdomains.get(type.subdomain)!.push({
        name,
        purpose: extractFirstSentence(type.purpose),
        keyProperties: formatKeyProperties(type.properties),
      });

      // Track relationships
      const rels = relationships.get(type.subdomain)!;
      if (type.extends) {
        rels.add(`\`${name}\` extends \`${type.extends}\``);
      }
      for (const impl of type.implements) {
        rels.add(`\`${name}\` implements \`${impl}\``);
      }
    }

    // Convert to array and sort
    const sections: SkillSubdomainSection[] = [];
    for (const [name, types] of subdomains) {
      sections.push({
        name,
        types: types.sort((a, b) => a.name.localeCompare(b.name)),
        relationships: Array.from(relationships.get(name) ?? []).slice(0, 5),
      });
    }

    return sections.sort((a, b) => a.name.localeCompare(b.name));
  }
}

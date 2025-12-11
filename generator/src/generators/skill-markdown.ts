/**
 * MCP PHP Schema Generator - Skill Markdown Helpers
 *
 * Utility functions for generating markdown content for the Claude Code skill.
 */

import type {
  SkillTypeTableEntry,
  SkillSubdomainSection,
  SkillRpcEntry,
  SkillFactoryEntry,
} from '../types/skill-types.js';

// ============================================================================
// Table Generation
// ============================================================================

/**
 * Generates a markdown table from headers and rows.
 */
export function generateMarkdownTable(
  headers: readonly string[],
  rows: readonly (readonly string[])[]
): string {
  const lines: string[] = [];

  // Header row
  lines.push(`| ${headers.join(' | ')} |`);

  // Separator row
  lines.push(`| ${headers.map(() => '---').join(' | ')} |`);

  // Data rows
  for (const row of rows) {
    // Escape pipe characters in cell values
    const escapedRow = row.map((cell) => cell.replace(/\|/g, '\\|'));
    lines.push(`| ${escapedRow.join(' | ')} |`);
  }

  return lines.join('\n');
}

/**
 * Generates a types table for a subdomain section.
 */
export function generateTypesTable(types: readonly SkillTypeTableEntry[]): string {
  const headers = ['Type', 'Purpose', 'Key Properties'];
  const rows = types.map((t) => [t.name, t.purpose, t.keyProperties]);
  return generateMarkdownTable(headers, rows);
}

/**
 * Generates an RPC methods table.
 */
export function generateRpcTable(methods: readonly SkillRpcEntry[]): string {
  const headers = ['Method', 'Direction', 'Request', 'Result'];
  const rows = methods.map((m) => [
    `\`${m.method}\``,
    m.direction,
    m.request,
    m.result,
  ]);
  return generateMarkdownTable(headers, rows);
}

/**
 * Generates a factory mappings table.
 */
export function generateFactoryMappingsTable(
  mappings: readonly { value: string; type: string }[]
): string {
  const headers = ['Value', 'Type'];
  const rows = mappings.map((m) => [`\`${m.value}\``, m.type]);
  return generateMarkdownTable(headers, rows);
}

// ============================================================================
// Section Generation
// ============================================================================

/**
 * Generates a subdomain section with types and relationships.
 */
export function generateSubdomainSection(section: SkillSubdomainSection): string {
  const lines: string[] = [];

  lines.push(`## ${section.name}`);
  lines.push('');
  lines.push('### Types');
  lines.push('');
  lines.push(generateTypesTable(section.types));
  lines.push('');

  if (section.relationships.length > 0) {
    lines.push('### Relationships');
    lines.push('');
    for (const rel of section.relationships) {
      lines.push(`- ${rel}`);
    }
    lines.push('');
  }

  return lines.join('\n');
}

/**
 * Generates a factory section.
 */
export function generateFactorySection(factory: SkillFactoryEntry): string {
  const lines: string[] = [];

  lines.push(`### ${factory.name}`);
  lines.push('');
  lines.push(`- **Interface:** \`${factory.interface}\``);
  lines.push(`- **Discriminator:** \`${factory.discriminator}\``);
  lines.push('');
  lines.push('**Mappings:**');
  lines.push('');
  lines.push(generateFactoryMappingsTable(factory.mappings));
  lines.push('');

  return lines.join('\n');
}

// ============================================================================
// Content Generation
// ============================================================================

/**
 * Generates table of contents from headings.
 */
export function generateTableOfContents(
  sections: readonly { name: string; count: number }[]
): string {
  const lines: string[] = [];

  lines.push('## Contents');
  lines.push('');

  for (const section of sections) {
    const anchor = section.name.toLowerCase().replace(/\s+/g, '-');
    lines.push(`- [${section.name}](#${anchor}) (${section.count} types)`);
  }

  lines.push('');

  return lines.join('\n');
}

/**
 * Generates a domain overview table.
 */
export function generateDomainOverviewTable(
  domains: readonly { name: string; types: number; purpose: string }[]
): string {
  const headers = ['Domain', 'Types', 'Purpose'];
  const rows = domains.map((d) => [d.name, String(d.types), d.purpose]);
  return generateMarkdownTable(headers, rows);
}

// ============================================================================
// Utility Functions
// ============================================================================

/**
 * Truncates a string to a maximum length with ellipsis.
 */
export function truncate(str: string, maxLength: number): string {
  if (str.length <= maxLength) {
    return str;
  }
  return str.substring(0, maxLength - 3) + '...';
}

/**
 * Converts a property record to a key properties string.
 */
export function formatKeyProperties(
  properties: Record<string, string>,
  maxProps = 3
): string {
  const entries = Object.entries(properties);
  if (entries.length === 0) {
    return '-';
  }

  const selected = entries.slice(0, maxProps);
  const formatted = selected.map(([name, type]) => {
    const isOptional = type.endsWith('?');
    const cleanType = isOptional ? type.slice(0, -1) : type;
    return `${name}${isOptional ? '?' : ''}: ${truncate(cleanType, 15)}`;
  });

  if (entries.length > maxProps) {
    formatted.push(`+${entries.length - maxProps} more`);
  }

  return formatted.join(', ');
}

/**
 * Extracts the first sentence from a description.
 */
export function extractFirstSentence(text: string, maxLength = 60): string {
  const firstSentence = text.split(/[.\n]/)[0]?.trim() ?? text;
  return truncate(firstSentence, maxLength);
}

/**
 * Generates markdown frontmatter.
 */
export function generateFrontmatter(metadata: Record<string, string>): string {
  const lines: string[] = ['---'];
  for (const [key, value] of Object.entries(metadata)) {
    lines.push(`${key}: ${value}`);
  }
  lines.push('---');
  return lines.join('\n');
}

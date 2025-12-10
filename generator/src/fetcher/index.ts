/**
 * MCP PHP Schema Generator - Schema Fetcher
 *
 * Fetches TypeScript schema files from GitHub.
 * Schema is always fetched from the official MCP repository.
 */

import { mkdir, readFile, writeFile } from 'fs/promises';
import { dirname, join } from 'path';
import type { GeneratorConfig, SchemaSource } from '../types/index.js';
import { getSchemaGitHubUrl } from '../config/index.js';

/**
 * Result of fetching the schema.
 */
export interface FetchResult {
  readonly content: string;
  readonly source: string;
  readonly cached: boolean;
}

/**
 * Cache directory for downloaded schemas.
 */
const CACHE_DIR = '.cache/schemas';

/**
 * Fetches the TypeScript schema from GitHub with caching support.
 */
export async function fetchSchema(config: GeneratorConfig): Promise<FetchResult> {
  const url = getSchemaGitHubUrl(config);
  const cacheFile = getCacheFilePath(config.schema);

  // Try to use cached version first (if not forcing refresh)
  try {
    const cached = await readFile(cacheFile, 'utf-8');
    return {
      content: cached,
      source: `cached: ${cacheFile}`,
      cached: true,
    };
  } catch {
    // Cache miss, fetch from GitHub
  }

  // Fetch from GitHub
  const response = await fetch(url);

  if (!response.ok) {
    throw new Error(`Failed to fetch schema from GitHub: ${response.status} ${response.statusText}`);
  }

  const content = await response.text();

  // Cache the result
  await cacheSchema(cacheFile, content);

  return {
    content,
    source: url,
    cached: false,
  };
}

/**
 * Gets the cache file path for a schema version.
 */
function getCacheFilePath(source: SchemaSource): string {
  const repoName = source.repository.replace('/', '_');
  return join(CACHE_DIR, `${repoName}_${source.version}_schema.ts`);
}

/**
 * Caches the schema content to disk.
 */
async function cacheSchema(cacheFile: string, content: string): Promise<void> {
  try {
    await mkdir(dirname(cacheFile), { recursive: true });
    await writeFile(cacheFile, content, 'utf-8');
  } catch {
    // Caching is best-effort, don't fail if it doesn't work
  }
}

/**
 * Clears the schema cache.
 */
export async function clearCache(): Promise<void> {
  const { rm } = await import('fs/promises');
  try {
    await rm(CACHE_DIR, { recursive: true, force: true });
  } catch {
    // Ignore errors when clearing cache
  }
}

/**
 * Forces a fresh fetch from GitHub, bypassing cache.
 */
export async function fetchSchemaFresh(config: GeneratorConfig): Promise<FetchResult> {
  const url = getSchemaGitHubUrl(config);
  const response = await fetch(url);

  if (!response.ok) {
    throw new Error(`Failed to fetch schema from GitHub: ${response.status} ${response.statusText}`);
  }

  const content = await response.text();
  const cacheFile = getCacheFilePath(config.schema);
  await cacheSchema(cacheFile, content);

  return {
    content,
    source: url,
    cached: false,
  };
}

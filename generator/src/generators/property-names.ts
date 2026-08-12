/** MCP-reserved metadata prefix whose final segment is safe to expose ergonomically. */
const MCP_META_PREFIX = 'io.modelcontextprotocol/';

/**
 * Maps an exact wire key to a legal PHP member name.
 *
 * The MCP-owned metadata prefix is omitted because it is reserved by the
 * protocol. Other namespaces remain part of the member name so third-party
 * keys do not casually collide with MCP keys.
 */
export function getPhpPropertyName(wireName: string): string {
  let candidate = wireName.startsWith(MCP_META_PREFIX)
    ? wireName.slice(MCP_META_PREFIX.length)
    : wireName;

  // Preserve the package's existing ergonomic mapping for JSON Schema keys.
  if (candidate.startsWith('$')) {
    candidate = candidate.slice(1);
  }

  candidate = candidate.replace(/[^a-zA-Z0-9_]/g, '_').replace(/_+/g, '_');

  if (candidate === '' || /^[0-9]/.test(candidate) || candidate === 'this') {
    candidate = `_${candidate}`;
  }

  return candidate;
}

/** Rejects two exact wire keys that would expose the same PHP member. */
export function assertNoPhpPropertyNameCollisions(
  ownerName: string,
  wireNames: readonly string[]
): void {
  const claimedNames = new Map<string, string>();

  for (const wireName of wireNames) {
    const phpName = getPhpPropertyName(wireName);
    const existingWireName = claimedNames.get(phpName);

    if (existingWireName !== undefined && existingWireName !== wireName) {
      throw new Error(
        `${ownerName} maps wire keys "${existingWireName}" and "${wireName}" ` +
          `to the same PHP member $${phpName}`
      );
    }

    claimedNames.set(phpName, wireName);
  }
}

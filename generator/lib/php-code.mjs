export function phpString(value) {
  return `'${value.replaceAll('\\', '\\\\').replaceAll("'", "\\'")}'`;
}

export function phpLiteral(value, depth = 0) {
  if (value === null) return 'null';
  if (value === true) return 'true';
  if (value === false) return 'false';
  if (typeof value === 'number') return Number.isInteger(value) ? String(value) : String(value);
  if (typeof value === 'string') return phpString(value);

  const indent = '    '.repeat(depth);
  const childIndent = '    '.repeat(depth + 1);
  if (Array.isArray(value)) {
    if (value.length === 0) return '[]';
    return `[\n${value.map((item) => `${childIndent}${phpLiteral(item, depth + 1)},`).join('\n')}\n${indent}]`;
  }

  const entries = Object.entries(value);
  if (entries.length === 0) return '[]';
  return `[\n${entries
    .map(([key, item]) => `${childIndent}${phpString(key)} => ${phpLiteral(item, depth + 1)},`)
    .join('\n')}\n${indent}]`;
}

export function phpFile(namespace, body) {
  return `<?php\n\n/**\n * This file is generated. Do not edit it directly.\n */\n\ndeclare(strict_types=1);\n\nnamespace ${namespace};\n\n${body.trim()}\n`;
}

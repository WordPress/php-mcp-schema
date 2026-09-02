<?php

declare(strict_types=1);

use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$targetBytes = 8 * 1024 * 1024;
$prefix = '{"name":"memory","inputSchema":{"type":"object","payload":[';
$suffix = ']}}';
$token = '1234567,';
$repetitions = intdiv($targetBytes - strlen($prefix) - strlen($suffix), strlen($token)) + 1;
$json = $prefix . rtrim(str_repeat($token, $repetitions), ',') . $suffix;

if (strlen($json) < $targetBytes) {
    throw new RuntimeException('The memory reproduction payload is smaller than 8 MiB.');
}

$schema = Schemas::create()->forVersion(Schemas::V2025_11_25);
$tool = $schema->fromJson(Tool::class, $json);
$payload = $tool->getInputSchema()->payload;

if (! is_array($payload) || count($payload) !== $repetitions) {
    throw new RuntimeException('The memory reproduction payload did not round-trip.');
}

printf(
    "input_bytes=%d values=%d peak_bytes=%d memory_limit=%s\n",
    strlen($json),
    $repetitions,
    memory_get_peak_usage(true),
    (string) ini_get('memory_limit')
);

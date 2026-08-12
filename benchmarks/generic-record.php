<?php

declare(strict_types=1);

use WP\McpSchema\Schemas;

require dirname(__DIR__) . '/vendor/autoload.php';

$iterations = 10000;
if (isset($argv[1])) {
    if (!ctype_digit($argv[1]) || (int) $argv[1] < 1) {
        fwrite(STDERR, "Usage: php benchmarks/generic-record.php [positive-iterations]\n");
        exit(1);
    }
    $iterations = (int) $argv[1];
}

$memoryBeforeCatalogs = memory_get_usage();
$legacySchema = Schemas::v20251125();
$memoryAfterLegacyCatalog = memory_get_usage();
$modernSchema = Schemas::v20260728();
$memoryAfterModernCatalog = memory_get_usage();

$type = $modernSchema->callToolResult();
$wire = [
    'resultType' => 'complete',
    'content' => [
        ['type' => 'text', 'text' => 'Benchmark result'],
    ],
    'structuredContent' => ['source' => 'benchmark'],
];

// Load the runtime path before measuring repeated hydrate/serialize cycles.
$type->fromArray($wire)->toArray();

$startedAt = hrtime(true);
$roundTrip = [];
for ($index = 0; $index < $iterations; $index++) {
    $roundTrip = $type->fromArray($wire)->toArray();
}
$elapsedNanoseconds = hrtime(true) - $startedAt;

if ($roundTrip !== $wire) {
    throw new RuntimeException('The benchmark payload did not round trip exactly.');
}

$elapsedMilliseconds = $elapsedNanoseconds / 1000000;
$operationsPerSecond = $iterations / ($elapsedNanoseconds / 1000000000);

echo 'PHP: ' . PHP_VERSION . "\n";
echo 'Iterations: ' . $iterations . "\n";
echo 'Catalog load order: 2025-11-25, 2026-07-28' . "\n";
echo 'First catalog incremental memory: ' .
    ($memoryAfterLegacyCatalog - $memoryBeforeCatalogs) . " bytes\n";
echo 'Second catalog incremental memory: ' .
    ($memoryAfterModernCatalog - $memoryAfterLegacyCatalog) . " bytes\n";
echo 'Hydrate + toArray elapsed: ' . number_format($elapsedMilliseconds, 2, '.', '') . " ms\n";
echo 'Hydrate + toArray throughput: ' . number_format($operationsPerSecond, 0, '.', '') . " operations/second\n";
echo 'Peak memory: ' . memory_get_peak_usage() . " bytes\n";

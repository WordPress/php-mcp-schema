<?php

declare(strict_types=1);

use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

$autoload = $argv[1] ?? '';
if ($autoload === '' || ! is_file($autoload)) {
    fwrite(STDERR, "Usage: php measure.php /path/to/vendor/autoload.php\n");
    exit(2);
}

$start = hrtime(true);
require $autoload;
$autoloadNanoseconds = hrtime(true) - $start;

$start = hrtime(true);
$schemas = Schemas::create();
$providerNanoseconds = hrtime(true) - $start;

$start = hrtime(true);
$v2025 = $schemas->forVersion(Schemas::V2025_11_25);
$select2025Nanoseconds = hrtime(true) - $start;

$start = hrtime(true);
$v2026 = $schemas->forVersion(Schemas::V2026_07_28);
$select2026Nanoseconds = hrtime(true) - $start;

$start = hrtime(true);
$schemas->forVersion(Schemas::V2026_07_28);
$warmSelectNanoseconds = hrtime(true) - $start;

$iterations = 1000;
$start = hrtime(true);
for ($index = 0; $index < $iterations; ++$index) {
    $v2026->fromArray(Tool::class, array(
        'name'        => 'measure',
        'inputSchema' => array('type' => 'object'),
    ));
}
$constructionNanoseconds = hrtime(true) - $start;

echo json_encode(array(
    'php'                     => PHP_VERSION,
    'opcacheLoaded'           => extension_loaded('Zend OPcache'),
    'opcacheCliEnabled'       => filter_var(ini_get('opcache.enable_cli'), FILTER_VALIDATE_BOOLEAN),
    'autoloadMicroseconds'    => $autoloadNanoseconds / 1000,
    'providerMicroseconds'    => $providerNanoseconds / 1000,
    'select2025Microseconds'  => $select2025Nanoseconds / 1000,
    'select2026Microseconds'  => $select2026Nanoseconds / 1000,
    'warmSelectMicroseconds'  => $warmSelectNanoseconds / 1000,
    'constructions'           => $iterations,
    'constructionMicroseconds'=> $constructionNanoseconds / 1000,
    'constructionsPerSecond'  => $iterations / ($constructionNanoseconds / 1000000000),
    'includedFiles'           => count(get_included_files()),
    'memoryBytes'             => memory_get_usage(true),
    'peakMemoryBytes'         => memory_get_peak_usage(true),
), JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES), "\n";

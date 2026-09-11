<?php

declare(strict_types=1);

use WP\McpSchema\Record\DiscoverRequest;
use WP\McpSchema\Record\PingRequest;
use WP\McpSchema\Record\Tool;
use WP\McpSchema\Schemas;

$autoload = $argv[1] ?? '';
if ($autoload === '' || ! is_file($autoload)) {
    fwrite(STDERR, "Usage: php smoke.php /path/to/vendor/autoload.php\n");
    exit(2);
}
require $autoload;

$schemas = Schemas::create();
$v2025  = $schemas->forVersion(Schemas::V2025_11_25);
$v2026  = $schemas->forVersion(Schemas::V2026_07_28);

$ping = $v2025->fromJson(
    PingRequest::class,
    '{"jsonrpc":"2.0","id":1,"method":"ping"}'
);
$discover = $v2026->fromArray(DiscoverRequest::class, array(
    'jsonrpc' => '2.0',
    'id'      => 2,
    'method'  => 'server/discover',
    'params'  => array(
        '_meta' => array(
            'io.modelcontextprotocol/protocolVersion'    => Schemas::V2026_07_28,
            'io.modelcontextprotocol/clientCapabilities' => array(),
        ),
    ),
));
$tool = $v2026->fromArray(Tool::class, array(
    'name'        => 'artifact-smoke',
    'inputSchema' => array('type' => 'object'),
));

echo json_encode(array(
    'versions' => Schemas::supportedVersions(),
    'ping'     => get_class($ping),
    'discover' => get_class($discover),
    'tool'     => $tool,
), JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES), "\n";

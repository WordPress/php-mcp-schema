<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Server\Lifecycle\Union\ServerResultInterface;

/**
 * A result that indicates success but carries no data.
 *
 * This class is a wrapper for {@see Result} that implements union interfaces.
 * In TypeScript, this is defined as: `type EmptyResult = Result`
 *
 * PHP requires actual classes for union interface implementation, so this wrapper
 * provides type-safe compatibility with:
 * - {@see ServerResultInterface}
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class EmptyResult extends Result implements ServerResultInterface
{
    // Inherits all functionality from Result.
    // This wrapper exists solely to implement union interfaces for type safety.
}

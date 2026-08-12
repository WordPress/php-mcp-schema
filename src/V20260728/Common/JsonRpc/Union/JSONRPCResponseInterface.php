<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\JsonRpc\Union;

use WP\McpSchema\V20260728\Common\JsonRpc\Union\JSONRPCMessageInterface;

/**
 * A response to a request, containing either the result or error.
 *
 * Union type members:
 * - JSONRPCResultResponse
 * - JSONRPCErrorResponse
 *
 * @mcp-domain Common
 * @mcp-subdomain JsonRpc
 * @mcp-version 2026-07-28
 */
interface JSONRPCResponseInterface extends JSONRPCMessageInterface
{
}

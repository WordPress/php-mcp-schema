<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common;

/**
 * MCP Protocol Constants.
 *
 * Contains all exported constants from the MCP TypeScript schema including:
 * - Protocol version constants
 * - JSON-RPC version
 * - Standard JSON-RPC error codes
 * - MCP-specific error codes
 *
 * @since 2026-07-28
 *
 * @mcp-version 2026-07-28
 */
final class McpConstants
{
    // Protocol constants
    public const LATEST_PROTOCOL_VERSION = '2026-07-28';
    public const JSONRPC_VERSION = '2.0';

    // Error codes
    public const PARSE_ERROR = -32700;
    public const INVALID_REQUEST = -32600;
    public const METHOD_NOT_FOUND = -32601;
    public const INVALID_PARAMS = -32602;
    public const INTERNAL_ERROR = -32603;

    /**
     * Error code returned when the HTTP headers of a request do not match the
     * corresponding values in the request body, or required headers are
     * missing or malformed.
     */
    public const HEADER_MISMATCH = -32020;

    /**
     * Error code returned when a server requires a client capability that was
     * not declared in the request's `clientCapabilities`.
     */
    public const MISSING_REQUIRED_CLIENT_CAPABILITY = -32021;

    /**
     * Error code returned when the request's protocol version is not supported
     * by the server.
     */
    public const UNSUPPORTED_PROTOCOL_VERSION = -32022;

    /**
     * Returns all error codes defined in this class.
     *
     * @return int[]
     */
    public static function getErrorCodes(): array
    {
        return [
            self::PARSE_ERROR,
            self::INVALID_REQUEST,
            self::METHOD_NOT_FOUND,
            self::INVALID_PARAMS,
            self::INTERNAL_ERROR,
            self::HEADER_MISMATCH,
            self::MISSING_REQUIRED_CLIENT_CAPABILITY,
            self::UNSUPPORTED_PROTOCOL_VERSION,
        ];
    }

    /**
     * Returns error code names mapped to their values.
     *
     * @return array<string, int>
     */
    public static function getErrorCodeNames(): array
    {
        return [
            'PARSE_ERROR' => self::PARSE_ERROR,
            'INVALID_REQUEST' => self::INVALID_REQUEST,
            'METHOD_NOT_FOUND' => self::METHOD_NOT_FOUND,
            'INVALID_PARAMS' => self::INVALID_PARAMS,
            'INTERNAL_ERROR' => self::INTERNAL_ERROR,
            'HEADER_MISMATCH' => self::HEADER_MISMATCH,
            'MISSING_REQUIRED_CLIENT_CAPABILITY' => self::MISSING_REQUIRED_CLIENT_CAPABILITY,
            'UNSUPPORTED_PROTOCOL_VERSION' => self::UNSUPPORTED_PROTOCOL_VERSION,
        ];
    }

    /**
     * Checks if the given error code is valid.
     *
     * @param int $code
     * @return bool
     */
    public static function isValidErrorCode(int $code): bool
    {
        return in_array($code, self::getErrorCodes(), true);
    }

    /**
     * Gets the constant name for an error code.
     *
     * @param int $code
     * @return string|null The constant name, or null if not found
     */
    public static function getErrorCodeName(int $code): ?string
    {
        $flipped = array_flip(self::getErrorCodeNames());
        return $flipped[$code] ?? null;
    }

    /**
     * Checks if an error code is a standard JSON-RPC error.
     *
     * Standard JSON-RPC errors are in the range -32700 to -32600.
     *
     * @param int $code
     * @return bool
     */
    public static function isStandardJsonRpcError(int $code): bool
    {
        return $code >= -32700 && $code <= -32600;
    }
}

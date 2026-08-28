<?php

declare(strict_types=1);

namespace WP\McpSchema\Internal;

use WP\McpSchema\Exception\InvalidJsonException;

/**
 * Identity-preserving JSON decoder with lexical native-integer protection.
 *
 * @internal
 */
final class JsonDecoder
{
    public const MAX_DEPTH = 512;

    /**
     * @return mixed
     */
    public function decode(string $json)
    {
        $this->assertNativeIntegers($json);

        try {
            return json_decode($json, false, self::MAX_DEPTH, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new InvalidJsonException('Invalid JSON: ' . $exception->getMessage(), 0, $exception);
        }
    }

    private function assertNativeIntegers(string $json): void
    {
        $length   = strlen($json);
        $inString = false;
        $escaped  = false;

        for ($index = 0; $index < $length; ++$index) {
            $character = $json[$index];
            if ($inString) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($character === '\\') {
                    $escaped = true;
                } elseif ($character === '"') {
                    $inString = false;
                }
                continue;
            }
            if ($character === '"') {
                $inString = true;
                continue;
            }
            if ($character !== '-' && ($character < '0' || $character > '9')) {
                continue;
            }

            $remaining = substr($json, $index);
            if (! preg_match('/^-?(?:0|[1-9][0-9]*)(?:\.[0-9]+)?(?:[eE][+-]?[0-9]+)?/', $remaining, $matches)) {
                continue;
            }
            $token = $matches[0];
            $index += strlen($token) - 1;
            if (strpos($token, '.') !== false || stripos($token, 'e') !== false) {
                continue;
            }
            if (! $this->isNativeInteger($token)) {
                throw new InvalidJsonException(sprintf('JSON integer %s exceeds the native PHP integer range.', $token));
            }
        }
    }

    private function isNativeInteger(string $token): bool
    {
        $negative = $token[0] === '-';
        $digits   = ltrim($token, '-');
        $digits   = ltrim($digits, '0');
        if ($digits === '') {
            return true;
        }

        $limit = $negative ? ltrim((string) PHP_INT_MIN, '-') : (string) PHP_INT_MAX;
        if (strlen($digits) !== strlen($limit)) {
            return strlen($digits) < strlen($limit);
        }

        return strcmp($digits, $limit) <= 0;
    }
}

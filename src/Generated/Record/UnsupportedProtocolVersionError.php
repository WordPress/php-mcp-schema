<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class UnsupportedProtocolVersionError extends \WP\McpSchema\Record
{
    public const DEFINITION = 'UnsupportedProtocolVersionError';

    /**
     * @return \stdClass
     */
    public function getError(): \stdClass
    {
        /** @var \stdClass $value */
        $value = $this->declaredValue('error');

        return $value;
    }

    /**
     * @return float|int|null|string
     */
    public function getId()
    {
        /** @var float|int|null|string $value */
        $value = $this->declaredValue('id');

        return $value;
    }

    /**
     * @return '2.0'
     */
    public function getJsonrpc(): string
    {
        /** @var '2.0' $value */
        $value = $this->declaredValue('jsonrpc');

        return $value;
    }
}

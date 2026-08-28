<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class HeaderMismatchError extends \WP\McpSchema\Record
{
    public const DEFINITION = 'HeaderMismatchError';

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
     * @return int|null|string
     */
    public function getId()
    {
        /** @var int|null|string $value */
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

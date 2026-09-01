<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class ClientResult extends \WP\McpSchema\Record
{
    public const DEFINITION = 'ClientResult';

    /**
     * @return \WP\McpSchema\Record\ResultMetaObject|null
     */
    public function getMeta(): ?\WP\McpSchema\Record\ResultMetaObject
    {
        /** @var \WP\McpSchema\Record\ResultMetaObject|null $value */
        $value = $this->declaredValue('_meta');

        return $value;
    }

    /**
     * @return string
     */
    public function getResultType(): string
    {
        /** @var string $value */
        $value = $this->declaredValue('resultType');

        return $value;
    }
}

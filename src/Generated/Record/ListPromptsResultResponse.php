<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class ListPromptsResultResponse extends \WP\McpSchema\Record
{
    public const DEFINITION = 'ListPromptsResultResponse';

    /**
     * @return int|string
     */
    public function getId()
    {
        /** @var int|string $value */
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

    /**
     * @return \WP\McpSchema\Record\ListPromptsResult
     */
    public function getResult(): \WP\McpSchema\Record\ListPromptsResult
    {
        /** @var \WP\McpSchema\Record\ListPromptsResult $value */
        $value = $this->declaredValue('result');

        return $value;
    }
}

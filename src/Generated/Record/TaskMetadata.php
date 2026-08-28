<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class TaskMetadata extends \WP\McpSchema\Record
{
    public const DEFINITION = 'TaskMetadata';

    /**
     * @return int|null
     */
    public function getTtl(): ?int
    {
        /** @var int|null $value */
        $value = $this->declaredValue('ttl');

        return $value;
    }
}

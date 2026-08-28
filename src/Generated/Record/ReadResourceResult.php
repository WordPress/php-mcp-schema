<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class ReadResourceResult extends \WP\McpSchema\Record implements \WP\McpSchema\Contract\ServerResult
{
    public const DEFINITION = 'ReadResourceResult';

    /**
     * @return \WP\McpSchema\Record\ResultMetaObject|\stdClass|null
     */
    public function getMeta()
    {
        /** @var \WP\McpSchema\Record\ResultMetaObject|\stdClass|null $value */
        $value = $this->declaredValue('_meta');

        return $value;
    }

    /**
     * @return 'private'|'public'|null
     */
    public function getCacheScope(): ?string
    {
        /** @var 'private'|'public'|null $value */
        $value = $this->declaredValue('cacheScope');

        return $value;
    }

    /**
     * @return array<int, \WP\McpSchema\Record\BlobResourceContents|\WP\McpSchema\Record\TextResourceContents>
     */
    public function getContents(): array
    {
        /** @var array<int, \WP\McpSchema\Record\BlobResourceContents|\WP\McpSchema\Record\TextResourceContents> $value */
        $value = $this->declaredValue('contents');

        return $value;
    }

    /**
     * @return null|string
     */
    public function getResultType(): ?string
    {
        /** @var null|string $value */
        $value = $this->declaredValue('resultType');

        return $value;
    }

    /**
     * @return int|null
     */
    public function getTtlMs(): ?int
    {
        /** @var int|null $value */
        $value = $this->declaredValue('ttlMs');

        return $value;
    }
}

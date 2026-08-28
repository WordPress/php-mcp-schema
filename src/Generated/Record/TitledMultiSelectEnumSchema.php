<?php

/**
 * This file is generated. Do not edit it directly.
 */

declare(strict_types=1);

namespace WP\McpSchema\Record;

final class TitledMultiSelectEnumSchema extends \WP\McpSchema\Record implements \WP\McpSchema\Contract\EnumSchema, \WP\McpSchema\Contract\MultiSelectEnumSchema, \WP\McpSchema\Contract\PrimitiveSchemaDefinition
{
    public const DEFINITION = 'TitledMultiSelectEnumSchema';

    /**
     * @return array<int, string>|null
     */
    public function getDefault(): ?array
    {
        /** @var array<int, string>|null $value */
        $value = $this->declaredValue('default');

        return $value;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        /** @var null|string $value */
        $value = $this->declaredValue('description');

        return $value;
    }

    /**
     * @return \stdClass
     */
    public function getItems(): \stdClass
    {
        /** @var \stdClass $value */
        $value = $this->declaredValue('items');

        return $value;
    }

    /**
     * @return int|null
     */
    public function getMaxItems(): ?int
    {
        /** @var int|null $value */
        $value = $this->declaredValue('maxItems');

        return $value;
    }

    /**
     * @return int|null
     */
    public function getMinItems(): ?int
    {
        /** @var int|null $value */
        $value = $this->declaredValue('minItems');

        return $value;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        /** @var null|string $value */
        $value = $this->declaredValue('title');

        return $value;
    }

    /**
     * @return 'array'
     */
    public function getType(): string
    {
        /** @var 'array' $value */
        $value = $this->declaredValue('type');

        return $value;
    }
}

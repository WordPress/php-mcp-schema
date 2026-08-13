<?php

declare(strict_types=1);

namespace WP\McpSchema\Server\Resources\DTO;

use WP\McpSchema\Compatibility\DescriptorBackedDto;

/** Descriptor-backed compatibility facade for the legacy resources-list hook. */
final class Resource extends DescriptorBackedDto
{
    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            self::hydrate('Resource', $data, [['_meta'], ['annotations']]),
            'Resource'
        );
    }

    public function getName(): string
    {
        return $this->stringValue('name');
    }

    public function getTitle(): ?string
    {
        return $this->nullableStringValue('title');
    }

    public function getUri(): string
    {
        return $this->stringValue('uri');
    }

    public function getDescription(): ?string
    {
        return $this->nullableStringValue('description');
    }

    public function getMimeType(): ?string
    {
        return $this->nullableStringValue('mimeType');
    }

    /** @return array<string, mixed>|null */
    public function getAnnotations(): ?array
    {
        /** @var array<string, mixed>|null $annotations */
        $annotations = $this->nullableArrayValue('annotations');
        return $annotations;
    }

    public function getSize(): ?int
    {
        return $this->nullableIntValue('size');
    }

    /** @return array<string, mixed>|null */
    public function get_meta(): ?array
    {
        /** @var array<string, mixed>|null $meta */
        $meta = $this->nullableArrayValue('_meta');
        return $meta;
    }

    /** @return list<array<string, mixed>>|null */
    public function getIcons(): ?array
    {
        /** @var list<array<string, mixed>>|null $icons */
        $icons = $this->nullableArrayValue('icons');
        return $icons;
    }
}

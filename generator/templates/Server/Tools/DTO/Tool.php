<?php

declare(strict_types=1);

namespace WP\McpSchema\Server\Tools\DTO;

use WP\McpSchema\Compatibility\DescriptorBackedDto;

/** Descriptor-backed compatibility facade for the legacy tools-list hook. */
final class Tool extends DescriptorBackedDto
{
    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            self::hydrate(
                'Tool',
                $data,
                [
                    ['_meta'],
                    ['annotations'],
                    ['execution'],
                    ['inputSchema'],
                    ['inputSchema', '$defs'],
                    ['inputSchema', 'definitions'],
                    ['inputSchema', 'patternProperties'],
                    ['inputSchema', 'properties'],
                    ['outputSchema'],
                    ['outputSchema', '$defs'],
                    ['outputSchema', 'definitions'],
                    ['outputSchema', 'patternProperties'],
                    ['outputSchema', 'properties'],
                ]
            ),
            'Tool'
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

    public function getDescription(): ?string
    {
        return $this->nullableStringValue('description');
    }

    /** @return array<string, mixed> */
    public function getInputSchema(): array
    {
        return $this->nullableArrayValue('inputSchema') ?? [];
    }

    /** @return array<string, mixed>|null */
    public function getExecution(): ?array
    {
        /** @var array<string, mixed>|null $execution */
        $execution = $this->nullableArrayValue('execution');
        return $execution;
    }

    /** @return array<string, mixed>|null */
    public function getOutputSchema(): ?array
    {
        /** @var array<string, mixed>|null $schema */
        $schema = $this->nullableArrayValue('outputSchema');
        return $schema;
    }

    /** @return array<string, mixed>|null */
    public function getAnnotations(): ?array
    {
        /** @var array<string, mixed>|null $annotations */
        $annotations = $this->nullableArrayValue('annotations');
        return $annotations;
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

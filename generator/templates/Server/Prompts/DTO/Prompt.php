<?php

declare(strict_types=1);

namespace WP\McpSchema\Server\Prompts\DTO;

use WP\McpSchema\Compatibility\DescriptorBackedDto;

/** Descriptor-backed compatibility facade for the legacy prompts-list hook and builder. */
final class Prompt extends DescriptorBackedDto
{
    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        if (isset($data['arguments']) && is_array($data['arguments'])) {
            $data['arguments'] = array_map(
                static function ($argument) {
                    return $argument instanceof PromptArgument ? $argument->toArray() : $argument;
                },
                $data['arguments']
            );
        }

        return new self(self::hydrate('Prompt', $data, [['_meta']]), 'Prompt');
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

    /** @return list<PromptArgument>|null */
    public function getArguments(): ?array
    {
        $records = $this->nullableRecordListValue('arguments');
        if ($records === null) {
            return null;
        }
        return array_map(
            static function ($record): PromptArgument {
                return PromptArgument::fromRecord($record);
            },
            $records
        );
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

<?php

declare(strict_types=1);

namespace WP\McpSchema\Server\Prompts\DTO;

use WP\McpSchema\Compatibility\DescriptorBackedDto;
use WP\McpSchema\Contract\Record;

/** Descriptor-backed compatibility facade for a legacy prompt argument. */
final class PromptArgument extends DescriptorBackedDto
{
    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(self::hydrate('PromptArgument', $data), 'PromptArgument');
    }

    /** @param Record<array<string, mixed>, array<string, mixed>> $record */
    public static function fromRecord(Record $record): self
    {
        return new self($record, 'PromptArgument');
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

    public function getRequired(): ?bool
    {
        return $this->nullableBoolValue('required');
    }
}

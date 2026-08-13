<?php

declare(strict_types=1);

namespace WP\McpSchema\Common\Protocol\DTO;

use WP\McpSchema\Compatibility\DescriptorBackedDto;

/** Descriptor-backed compatibility facade for the legacy initialize hook. */
final class InitializeResult extends DescriptorBackedDto
{
    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            self::hydrate(
                'InitializeResult',
                $data,
                [
                    ['_meta'],
                    ['capabilities'],
                    ['capabilities', 'completions'],
                    ['capabilities', 'experimental'],
                    ['capabilities', 'logging'],
                    ['capabilities', 'prompts'],
                    ['capabilities', 'resources'],
                    ['capabilities', 'tasks'],
                    ['capabilities', 'tools'],
                    ['serverInfo'],
                ]
            ),
            'InitializeResult'
        );
    }

    public function getProtocolVersion(): string
    {
        return $this->stringValue('protocolVersion');
    }

    /** @return array<string, mixed> */
    public function getCapabilities(): array
    {
        return $this->nullableArrayValue('capabilities') ?? [];
    }

    /** @return array<string, mixed> */
    public function getServerInfo(): array
    {
        return $this->nullableArrayValue('serverInfo') ?? [];
    }

    public function getInstructions(): ?string
    {
        return $this->nullableStringValue('instructions');
    }

    /** @return array<string, mixed>|null */
    public function get_meta(): ?array
    {
        /** @var array<string, mixed>|null $meta */
        $meta = $this->nullableArrayValue('_meta');
        return $meta;
    }
}

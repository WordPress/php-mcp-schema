<?php

declare(strict_types=1);

namespace WP\McpSchema\Contract;

interface RevisionSchema
{
    /** @return Type<array<string, mixed>, array<string, mixed>> */
    public function type(string $name): Type;

    public function hasType(string $name): bool;

    /** @return array<int, string> */
    public function types(): array;

    public function revision(): string;

    public function fingerprint(): string;
}

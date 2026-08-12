<?php

declare(strict_types=1);

namespace WP\McpSchema\Runtime;

use InvalidArgumentException;

final class ValidationException extends InvalidArgumentException
{
    private string $revision;

    private string $typeName;

    private string $wirePath;

    public function __construct(
        string $revision,
        string $typeName,
        string $wirePath,
        string $problem
    ) {
        parent::__construct(sprintf(
            '%s %s at %s: %s',
            $revision,
            $typeName,
            $wirePath,
            $problem
        ));
        $this->revision = $revision;
        $this->typeName = $typeName;
        $this->wirePath = $wirePath;
    }

    public function revision(): string
    {
        return $this->revision;
    }

    public function typeName(): string
    {
        return $this->typeName;
    }

    public function wirePath(): string
    {
        return $this->wirePath;
    }
}

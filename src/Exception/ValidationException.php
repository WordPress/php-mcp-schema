<?php

declare(strict_types=1);

namespace WP\McpSchema\Exception;

final class ValidationException extends SchemaException
{
    /** @var string */
    private $pointer;

    public function __construct(string $pointer, string $message)
    {
        $this->pointer = $pointer;
        parent::__construct(sprintf('%s: %s', $pointer === '' ? '/' : $pointer, $message));
    }

    public function getPointer(): string
    {
        return $this->pointer;
    }
}

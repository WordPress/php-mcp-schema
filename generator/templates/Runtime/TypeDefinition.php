<?php

declare(strict_types=1);

namespace WP\McpSchema\Runtime;

use JsonException;
use stdClass;
use WP\McpSchema\Contract\Record;
use WP\McpSchema\Contract\Type;

/** @implements Type<array<string, mixed>, array<string, mixed>> */
final class TypeDefinition implements Type
{
    private GenericRevisionSchema $schema;

    private string $name;

    private string $fingerprint;

    public function __construct(GenericRevisionSchema $schema, string $name, string $fingerprint)
    {
        $this->schema = $schema;
        $this->name = $name;
        $this->fingerprint = $fingerprint;
    }

    /**
     * @param array<string, mixed> $data
     * @return Record<array<string, mixed>, array<string, mixed>>
     */
    public function fromArray(array $data): Record
    {
        /** @var Record<array<string, mixed>, array<string, mixed>> $record */
        $record = $this->schema->hydrate($this->name, $data);
        return $record;
    }

    /**
     * @param array<string, mixed>|stdClass $data
     * @return Record<array<string, mixed>, array<string, mixed>>
     */
    public function fromValue($data): Record
    {
        /** @var Record<array<string, mixed>, array<string, mixed>> $record */
        $record = $this->schema->hydrate($this->name, $data);
        return $record;
    }

    /** @return Record<array<string, mixed>, array<string, mixed>> */
    public function fromJson(string $json): Record
    {
        try {
            $decoded = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ValidationException(
                $this->revision(),
                $this->name,
                '$',
                'invalid JSON: ' . $exception->getMessage()
            );
        }

        /** @var Record<array<string, mixed>, array<string, mixed>> $record */
        $record = $this->schema->hydrate($this->name, $decoded);
        return $record;
    }

    /** @param array<string, mixed> $data */
    public function validate(array $data): void
    {
        $this->schema->hydrate($this->name, $data);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function revision(): string
    {
        return $this->schema->revision();
    }

    public function fingerprint(): string
    {
        return $this->fingerprint;
    }
}

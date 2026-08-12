<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Lifecycle\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * Present if the client supports elicitation from the server.
 *
 * @mcp-domain Client
 * @mcp-subdomain Lifecycle
 * @mcp-version 2026-07-28
 */
class ClientCapabilitiesElicitation extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $form;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $url;

    /**
     * @param array<string, mixed>|null $form
     * @param array<string, mixed>|null $url
     */
    public function __construct(
        ?array $form = null,
        ?array $url = null
    ) {
        $this->form = $form;
        $this->url = $url;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     form?: array<string, mixed>|null,
     *     url?: array<string, mixed>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::asArrayOrNull($data['form'] ?? null),
            self::asArrayOrNull($data['url'] ?? null)
        );
    }

    /**
     * Converts the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->form !== null) {
            $result['form'] = $this->form;
        }
        if ($this->url !== null) {
            $result['url'] = $this->url;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getForm(): ?array
    {
        return $this->form;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getUrl(): ?array
    {
        return $this->url;
    }
}

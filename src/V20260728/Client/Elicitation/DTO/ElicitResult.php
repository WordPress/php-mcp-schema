<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Elicitation\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Protocol\Union\InputResponseInterface;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * The result returned by the client for an {@link ElicitRequest| elicitation/create} request.
 *
 * @since 2025-06-18
 * @last-updated 2026-07-28 (removed properties: _meta)
 *
 * @mcp-domain Client
 * @mcp-subdomain Elicitation
 * @mcp-version 2026-07-28
 */
class ElicitResult extends AbstractDataTransferObject implements InputResponseInterface
{
    use ValidatesRequiredFields;

    /**
     * The user action in response to the elicitation.
     * - `"accept"`: User submitted the form/confirmed the action
     * - `"decline"`: User explicitly declined the action
     * - `"cancel"`: User dismissed without making an explicit choice
     *
     * @since 2025-06-18
     *
     * @var 'accept'|'decline'|'cancel'
     */
    protected string $action;

    /**
     * The submitted form data, only present when action is `"accept"` and mode was `"form"`.
     * Contains values matching the requested schema.
     * Omitted for out-of-band mode responses.
     *
     * @since 2025-06-18
     *
     * @var array<string, string>|null
     */
    protected ?array $content;

    /**
     * @param 'accept'|'decline'|'cancel' $action @since 2025-06-18
     * @param array<string, string>|null $content @since 2025-06-18
     */
    public function __construct(
        string $action,
        ?array $content = null
    ) {
        $this->action = $action;
        $this->content = $content;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     action: 'accept'|'decline'|'cancel',
     *     content?: array<string, string>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['action']);

        /** @var 'accept'|'decline'|'cancel' $action */
        $action = self::asString($data['action']);

        return new self(
            $action,
            self::asStringMapOrNull($data['content'] ?? null)
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

        $result['action'] = $this->action;
        if ($this->content !== null) {
            $result['content'] = $this->content;
        }

        return $result;
    }

    /**
     * @return 'accept'|'decline'|'cancel'
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array<string, string>|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }
}

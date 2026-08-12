<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Client\Elicitation\DTO;

use WP\McpSchema\V20260728\Client\Elicitation\Union\ElicitRequestParamsInterface;
use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * The parameters for a request to elicit information from the user via a URL in the client.
 *
 * @since 2025-11-25
 * @last-updated 2026-07-28 (removed properties: _meta, elicitationId, task)
 *
 * @mcp-domain Client
 * @mcp-subdomain Elicitation
 * @mcp-version 2026-07-28
 */
class ElicitRequestURLParams extends AbstractDataTransferObject implements ElicitRequestParamsInterface
{
    use ValidatesRequiredFields;

    public const MODE = 'url';

    public const DISCRIMINATOR_FIELD = 'mode';
    public const DISCRIMINATOR_VALUE = 'url';

    /**
     * The elicitation mode.
     *
     * @since 2025-11-25
     *
     * @var 'url'
     */
    protected string $mode;

    /**
     * The message to present to the user explaining why the interaction is needed.
     *
     * @since 2025-11-25
     *
     * @var string
     */
    protected string $message;

    /**
     * The URL that the user should navigate to.
     *
     * @since 2025-11-25
     *
     * @var string
     */
    protected string $url;

    /**
     * @param string $message @since 2025-11-25
     * @param string $url @since 2025-11-25
     */
    public function __construct(
        string $message,
        string $url
    ) {
        $this->mode = self::MODE;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     mode: 'url',
     *     message: string,
     *     url: string
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        self::assertRequired($data, ['message', 'url']);

        return new self(
            self::asString($data['message']),
            self::asString($data['url'])
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

        $result['mode'] = $this->mode;
        $result['message'] = $this->message;
        $result['url'] = $this->url;

        return $result;
    }

    /**
     * @return 'url'
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}

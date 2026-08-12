<?php

declare(strict_types=1);

namespace WP\McpSchema\V20260728\Common\Protocol\DTO;

use WP\McpSchema\V20260728\Common\AbstractDataTransferObject;
use WP\McpSchema\V20260728\Common\Traits\ValidatesRequiredFields;

/**
 * The set of notification types a client may opt in to on a
 * {@link SubscriptionsListenRequest | subscriptions/listen} request.
 *
 * Each notification type is **opt-in**; the server **MUST NOT** send
 * notification types the client has not explicitly requested here.
 *
 * @since 2026-07-28
 *
 * @mcp-domain Common
 * @mcp-subdomain Protocol
 * @mcp-version 2026-07-28
 */
class SubscriptionFilter extends AbstractDataTransferObject
{
    use ValidatesRequiredFields;

    /**
     * If true, receive {@link ToolListChangedNotification | notifications/tools/list_changed}.
     *
     * @since 2026-07-28
     *
     * @var bool|null
     */
    protected ?bool $toolsListChanged;

    /**
     * If true, receive {@link PromptListChangedNotification | notifications/prompts/list_changed}.
     *
     * @since 2026-07-28
     *
     * @var bool|null
     */
    protected ?bool $promptsListChanged;

    /**
     * If true, receive {@link ResourceListChangedNotification | notifications/resources/list_changed}.
     *
     * @since 2026-07-28
     *
     * @var bool|null
     */
    protected ?bool $resourcesListChanged;

    /**
     * Subscribe to {@link ResourceUpdatedNotification | notifications/resources/updated} for these resource URIs.
     * Replaces the former `resources/subscribe` RPC.
     *
     * @since 2026-07-28
     *
     * @var array<string>|null
     */
    protected ?array $resourceSubscriptions;

    /**
     * @param bool|null $toolsListChanged @since 2026-07-28
     * @param bool|null $promptsListChanged @since 2026-07-28
     * @param bool|null $resourcesListChanged @since 2026-07-28
     * @param array<string>|null $resourceSubscriptions @since 2026-07-28
     */
    public function __construct(
        ?bool $toolsListChanged = null,
        ?bool $promptsListChanged = null,
        ?bool $resourcesListChanged = null,
        ?array $resourceSubscriptions = null
    ) {
        $this->toolsListChanged = $toolsListChanged;
        $this->promptsListChanged = $promptsListChanged;
        $this->resourcesListChanged = $resourcesListChanged;
        $this->resourceSubscriptions = $resourceSubscriptions;
    }

    /**
     * Creates an instance from an array.
     *
     * @param array{
     *     toolsListChanged?: bool|null,
     *     promptsListChanged?: bool|null,
     *     resourcesListChanged?: bool|null,
     *     resourceSubscriptions?: array<string>|null
     * } $data
     * @phpstan-param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::asBoolOrNull($data['toolsListChanged'] ?? null),
            self::asBoolOrNull($data['promptsListChanged'] ?? null),
            self::asBoolOrNull($data['resourcesListChanged'] ?? null),
            self::asStringArrayOrNull($data['resourceSubscriptions'] ?? null)
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

        if ($this->toolsListChanged !== null) {
            $result['toolsListChanged'] = $this->toolsListChanged;
        }
        if ($this->promptsListChanged !== null) {
            $result['promptsListChanged'] = $this->promptsListChanged;
        }
        if ($this->resourcesListChanged !== null) {
            $result['resourcesListChanged'] = $this->resourcesListChanged;
        }
        if ($this->resourceSubscriptions !== null) {
            $result['resourceSubscriptions'] = $this->resourceSubscriptions;
        }

        return $result;
    }

    /**
     * @return bool|null
     */
    public function getToolsListChanged(): ?bool
    {
        return $this->toolsListChanged;
    }

    /**
     * @return bool|null
     */
    public function getPromptsListChanged(): ?bool
    {
        return $this->promptsListChanged;
    }

    /**
     * @return bool|null
     */
    public function getResourcesListChanged(): ?bool
    {
        return $this->resourcesListChanged;
    }

    /**
     * @return array<string>|null
     */
    public function getResourceSubscriptions(): ?array
    {
        return $this->resourceSubscriptions;
    }
}

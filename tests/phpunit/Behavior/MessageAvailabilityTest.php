<?php

declare(strict_types=1);

namespace WP\McpSchema\Tests\Behavior;

use PHPUnit\Framework\TestCase;
use WP\McpSchema\Schemas;

final class MessageAvailabilityTest extends TestCase
{
    public function test_directional_message_availability_is_revision_exact(): void
    {
        $schemas = Schemas::create();
        $v2025   = $schemas->forVersion(Schemas::V2025_11_25);
        $v2026   = $schemas->forVersion(Schemas::V2026_07_28);

        self::assertTrue($v2025->allowsClientRequest('initialize'));
        self::assertTrue($v2025->allowsClientRequest('ping'));
        self::assertTrue($v2025->allowsServerRequest('sampling/createMessage'));
        self::assertFalse($v2025->allowsClientRequest('server/discover'));
        self::assertFalse($v2025->allowsEmbeddedInput('sampling/createMessage'));

        self::assertFalse($v2026->allowsClientRequest('initialize'));
        self::assertFalse($v2026->allowsClientRequest('ping'));
        self::assertTrue($v2026->allowsClientRequest('server/discover'));
        self::assertFalse($v2026->allowsServerRequest('sampling/createMessage'));
        self::assertTrue($v2026->allowsEmbeddedInput('sampling/createMessage'));
        self::assertTrue($v2026->allowsServerNotification('notifications/subscriptions/acknowledged'));
    }
}

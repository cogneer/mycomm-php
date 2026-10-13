<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.disconnected`: The number was disconnected (owner disconnect, SIM retirement, ops
 * force-release, account deletion or a stale release). `data.reason` is the `disconnect_reason`.
 */
final class ChannelDisconnected extends Event
{
    public const TYPE = 'channel.disconnected';

    public readonly Models\WebhookChannelDisconnectedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelDisconnectedData::fromArray($data['data']);
    }
}

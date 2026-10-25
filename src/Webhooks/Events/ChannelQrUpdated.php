<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.qr_updated`: The WhatsApp QR code to scan was regenerated while the number connects; each one
 * stops working at `data.expires_at` (about 40 s). Opt-in: list it in the endpoint's `events`.
 */
final class ChannelQrUpdated extends Event
{
    public const TYPE = 'channel.qr_updated';

    public readonly Models\WebhookChannelQrUpdatedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelQrUpdatedData::fromArray($data['data']);
    }
}

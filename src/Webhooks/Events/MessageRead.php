<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `message.read`: The message moved to `read`. Emitted by channels with read receipts, never for SMS.
 */
final class MessageRead extends Event
{
    public const TYPE = 'message.read';

    public readonly Models\WebhookMessageData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageData::fromArray($data['data']);
    }
}

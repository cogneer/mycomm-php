<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `message.expired`: The message moved to `expired`.
 */
final class MessageExpired extends Event
{
    public const TYPE = 'message.expired';

    public readonly Models\WebhookMessageData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageData::fromArray($data['data']);
    }
}

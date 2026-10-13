<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `message.sent`: The message moved to `sent`, including phone-typed outbound and an `UNCONFIRMED`
 * resolved by the phone.
 */
final class MessageSent extends Event
{
    public const TYPE = 'message.sent';

    public readonly Models\WebhookMessageData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageData::fromArray($data['data']);
    }
}

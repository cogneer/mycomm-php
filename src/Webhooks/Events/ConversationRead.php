<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `conversation.read`: The conversation was marked read (portal, API or automation).
 */
final class ConversationRead extends Event
{
    public const TYPE = 'conversation.read';

    public readonly Models\WebhookConversationData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookConversationData::fromArray($data['data']);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `conversation.unread`: The conversation was marked unread.
 */
final class ConversationUnread extends Event
{
    public const TYPE = 'conversation.unread';

    public readonly Models\WebhookConversationData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookConversationData::fromArray($data['data']);
    }
}

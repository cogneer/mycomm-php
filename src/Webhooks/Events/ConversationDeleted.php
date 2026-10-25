<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `conversation.deleted`: The conversation was permanently deleted, after its messages.
 */
final class ConversationDeleted extends Event
{
    public const TYPE = 'conversation.deleted';

    public readonly Models\WebhookConversationDeletedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookConversationDeletedData::fromArray($data['data']);
    }
}

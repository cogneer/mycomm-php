<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `conversation.handed_over`: The conversation moved to another line (`POST
 * /v1/conversations/{id}/handover`) with its messages; the contact's next replies land on `to_line_id`.
 * The envelope `line_id` is the target line. When the target already had a conversation with the contact
 * the two merged, and `conversation_id` is that conversation.
 */
final class ConversationHandedOver extends Event
{
    public const TYPE = 'conversation.handed_over';

    public readonly Models\WebhookConversationHandedOverData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookConversationHandedOverData::fromArray($data['data']);
    }
}

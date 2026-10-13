<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookMessageReceivedData extends Model
{
    public readonly Message $message;

    public readonly WebhookConversationRef $conversation;

    public readonly bool $contact_created;

    protected function hydrate(array $data): void
    {
        $this->message = Message::fromArray($data['message']);
        $this->conversation = WebhookConversationRef::fromArray($data['conversation']);
        $this->contact_created = $data['contact_created'];
    }
}

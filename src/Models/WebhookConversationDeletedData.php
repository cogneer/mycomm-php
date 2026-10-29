<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookConversationDeletedData extends Model
{
    public readonly string $conversation_id;

    public readonly string $line_id;

    public readonly string $contact_id;

    public readonly WebhookActor $actor;

    protected function hydrate(array $data): void
    {
        $this->conversation_id = $data['conversation_id'];
        $this->line_id = $data['line_id'];
        $this->contact_id = $data['contact_id'];
        $this->actor = WebhookActor::fromArray($data['actor']);
    }
}

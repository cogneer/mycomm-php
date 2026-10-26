<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookConversationData extends Model
{
    public readonly Conversation $conversation;

    public readonly WebhookActor $actor;

    protected function hydrate(array $data): void
    {
        $this->conversation = Conversation::fromArray($data['conversation']);
        $this->actor = WebhookActor::fromArray($data['actor']);
    }
}

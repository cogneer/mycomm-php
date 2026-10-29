<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookMessageData extends Model
{
    public readonly Message $message;

    public readonly WebhookConversationRef $conversation;

    /**
     * The length of `message.sender_attempts`: 1 for a message that stayed on its first sender, more after a retry or a failover, 0 for inbound.
     */
    public readonly int $sender_attempt_count;

    protected function hydrate(array $data): void
    {
        $this->message = Message::fromArray($data['message']);
        $this->conversation = WebhookConversationRef::fromArray($data['conversation']);
        $this->sender_attempt_count = $data['sender_attempt_count'];
    }
}

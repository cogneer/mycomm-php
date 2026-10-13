<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookMessageCancelledData extends Model
{
    public readonly Message $message;

    public readonly WebhookConversationRef $conversation;

    /**
     * `api`, `portal`, `campaign_cancelled`, `opted_out`, `contact_blocked`, `account_deleted` (may gain values).
     */
    public readonly ?string $reason;

    protected function hydrate(array $data): void
    {
        $this->message = Message::fromArray($data['message']);
        $this->conversation = WebhookConversationRef::fromArray($data['conversation']);
        $this->reason = $data['reason'] ?? null;
    }
}

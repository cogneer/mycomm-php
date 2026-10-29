<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Payload of conversation.handed_over.
 */
final class WebhookConversationHandedOverData extends Model
{
    public readonly string $conversation_id;

    public readonly string $from_line_id;

    public readonly string $to_line_id;

    protected function hydrate(array $data): void
    {
        $this->conversation_id = $data['conversation_id'];
        $this->from_line_id = $data['from_line_id'];
        $this->to_line_id = $data['to_line_id'];
    }
}

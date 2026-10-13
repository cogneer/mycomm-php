<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * `created` is true for the message that created the conversation.
 */
final class WebhookConversationRef extends Model
{
    public readonly ?string $id;

    public readonly bool $created;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->created = $data['created'];
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class AutomationReplyPreview extends Model
{
    /**
     * The final text, template variables filled in for the sender.
     */
    public readonly string $body;

    /**
     * SMS parts the reply would take.
     */
    public readonly int $segments;

    protected function hydrate(array $data): void
    {
        $this->body = $data['body'];
        $this->segments = $data['segments'];
    }
}

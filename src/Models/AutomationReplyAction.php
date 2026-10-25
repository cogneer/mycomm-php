<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Sends a reply, either a literal `body` (at most 1,600 characters and 10 SMS parts) or a live
 * `template_id`. Not both.
 */
final class AutomationReplyAction extends Model
{
    public readonly string $type;

    public readonly ?string $body;

    public readonly ?string $template_id;

    protected function hydrate(array $data): void
    {
        $this->type = $data['type'];
        $this->body = $data['body'] ?? null;
        $this->template_id = $data['template_id'] ?? null;
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookActor extends Model
{
    public readonly string $type;

    public readonly ?string $id;

    protected function hydrate(array $data): void
    {
        $this->type = $data['type'];
        $this->id = $data['id'] ?? null;
    }
}

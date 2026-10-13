<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookChannelRef extends Model
{
    public readonly string $id;

    public readonly string $type;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
    }
}

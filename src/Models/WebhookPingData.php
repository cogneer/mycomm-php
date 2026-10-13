<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookPingData extends Model
{
    public readonly string $endpoint_id;

    public readonly string $message;

    protected function hydrate(array $data): void
    {
        $this->endpoint_id = $data['endpoint_id'];
        $this->message = $data['message'];
    }
}

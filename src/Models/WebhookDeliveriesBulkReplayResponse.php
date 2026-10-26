<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookDeliveriesBulkReplayResponse extends Model
{
    public readonly int $replayed;

    protected function hydrate(array $data): void
    {
        $this->replayed = $data['replayed'];
    }
}

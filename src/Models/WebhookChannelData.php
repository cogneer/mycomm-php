<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookChannelData extends Model
{
    public readonly Channel $channel;

    protected function hydrate(array $data): void
    {
        $this->channel = Channel::fromArray($data['channel']);
    }
}

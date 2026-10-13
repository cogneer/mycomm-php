<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class WebhookChannelDisconnectedData extends Model
{
    public readonly Channel $channel;

    /**
     * The channel's `disconnect_reason`.
     */
    public readonly Enums\ChannelDisconnectReason|string $reason;

    protected function hydrate(array $data): void
    {
        $this->channel = Channel::fromArray($data['channel']);
        $this->reason = self::enum(Enums\ChannelDisconnectReason::class, $data['reason']);
    }
}

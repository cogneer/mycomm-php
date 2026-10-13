<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class WebhookChannelVerificationFailedData extends Model
{
    public readonly Channel $channel;

    /**
     * The verification's `failure_reason`; later also `qr_expired` and `token_invalid`.
     */
    public readonly Enums\ChannelVerificationFailureReason|string $reason;

    protected function hydrate(array $data): void
    {
        $this->channel = Channel::fromArray($data['channel']);
        $this->reason = self::enum(Enums\ChannelVerificationFailureReason::class, $data['reason']);
    }
}

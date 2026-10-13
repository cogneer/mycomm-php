<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class WebhookChannelPausedData extends Model
{
    public readonly Channel $channel;

    /**
     * The channel's `pause_reason`.
     */
    public readonly Enums\ChannelPauseReason|string $reason;

    /**
     * The failure code that dominated the streak; only for `auto:failure_streak`, else `null`.
     */
    public readonly ?string $dominant_code;

    /**
     * How many sends failed in a row; only for `auto:failure_streak`, else `null`.
     */
    public readonly ?int $streak;

    protected function hydrate(array $data): void
    {
        $this->channel = Channel::fromArray($data['channel']);
        $this->reason = self::enum(Enums\ChannelPauseReason::class, $data['reason']);
        $this->dominant_code = $data['dominant_code'] ?? null;
        $this->streak = $data['streak'] ?? null;
    }
}

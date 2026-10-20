<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

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

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

/**
 * One day. With `group_by=none` only `date` and the counters are present. Grouped rows add the group keys:
 * `channel_id` (channel, inbox), `inbox_id` (inbox) and `channel_type`; keys not part of the grouping are
 * `null`.
 */
final class DailyStatsRow extends Model
{
    public readonly string $date;

    public readonly ?string $channel_id;

    public readonly ?string $inbox_id;

    public readonly Enums\ChannelType|string|null $channel_type;

    public readonly int $sent_messages;

    public readonly int $sent_segments;

    public readonly int $delivered;

    public readonly int $read;

    public readonly int $failed;

    public readonly int $expired;

    public readonly int $cancelled;

    public readonly int $received;

    public readonly int $media_sent;

    public readonly int $media_received;

    public readonly int $clicks;

    public readonly int $opt_outs;

    public readonly int $opt_ins;

    protected function hydrate(array $data): void
    {
        $this->date = $data['date'];
        $this->channel_id = $data['channel_id'] ?? null;
        $this->inbox_id = $data['inbox_id'] ?? null;
        $this->channel_type = isset($data['channel_type']) ? self::enum(Enums\ChannelType::class, $data['channel_type']) : null;
        $this->sent_messages = $data['sent_messages'];
        $this->sent_segments = $data['sent_segments'];
        $this->delivered = $data['delivered'];
        $this->read = $data['read'];
        $this->failed = $data['failed'];
        $this->expired = $data['expired'];
        $this->cancelled = $data['cancelled'];
        $this->received = $data['received'];
        $this->media_sent = $data['media_sent'];
        $this->media_received = $data['media_received'];
        $this->clicks = $data['clicks'];
        $this->opt_outs = $data['opt_outs'];
        $this->opt_ins = $data['opt_ins'];
    }
}

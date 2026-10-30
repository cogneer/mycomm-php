<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class UsageRow extends Model
{
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

    public readonly string $line_id;

    public readonly string $account_id;

    /**
     * Your own reference for the line.
     */
    public readonly string $external_ref;

    public readonly string $name;

    /**
     * @var array<string, array<string, mixed>>
     */
    public readonly array $by_channel_type;

    /**
     * The line's senders that are switched on and active now.
     */
    public readonly int $channels_active;

    protected function hydrate(array $data): void
    {
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
        $this->line_id = $data['line_id'];
        $this->account_id = $data['account_id'];
        $this->external_ref = $data['external_ref'];
        $this->name = $data['name'];
        $this->by_channel_type = $data['by_channel_type'];
        $this->channels_active = $data['channels_active'];
    }
}

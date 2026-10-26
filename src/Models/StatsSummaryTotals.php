<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class StatsSummaryTotals extends Model
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
    }
}

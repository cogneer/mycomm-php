<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * Payload of line.alarm: a new alarm episode about the line or one of its senders.
 */
final class WebhookLineAlarmData extends Model
{
    public readonly string $alarm_id;

    public readonly Enums\AlarmKind|string $kind;

    public readonly string $line_id;

    /**
     * The sender the alarm is about; null for an alarm about the line itself.
     */
    public readonly ?string $channel_id;

    /**
     * How many times it happened when the episode began (for `failover` and `unconfirmed`, the messages).
     */
    public readonly int $count;

    public readonly DateTimeImmutable $first_at;

    /**
     * Details of the kind, for example `{percent}` for `budget_80` or `{channel_type, allowance, used}` for `sponsor_quota_low`. Clients must ignore keys they do not know.
     *
     * @var array<string, mixed>
     */
    public readonly array $data;

    protected function hydrate(array $data): void
    {
        $this->alarm_id = $data['alarm_id'];
        $this->kind = self::enum(Enums\AlarmKind::class, $data['kind']);
        $this->line_id = $data['line_id'];
        $this->channel_id = $data['channel_id'] ?? null;
        $this->count = $data['count'];
        $this->first_at = self::dateTime($data['first_at']);
        $this->data = $data['data'];
    }
}

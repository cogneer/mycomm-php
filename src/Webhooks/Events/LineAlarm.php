<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.alarm`: Something about the line needs attention, sent once per episode: while the condition lasts
 * it only counts, and once it has cleared the next occurrence is a new episode. `kind`: `failover`
 * (messages moved to another sender; ends after an hour without another move), `no_sender` (no sender can
 * send; ends when one can), `pool_member_blocked` (a pool member was paused or disconnected; ends when it
 * is resumed), `budget_80` (a sender used `alarm_thresholds.budget_percent` of its hourly limit; ends
 * below 60 %), `self_loop` (a sender sent `alarm_thresholds.self_loop_per_hour` messages to the account's
 * own numbers within an hour; ends after an hour without one), `sponsor_quota_low` (80 % of the sponsored
 * allowance is used; ends with the next cycle) or `unconfirmed` (messages an unpaired phone never
 * confirmed were failed or sent again, per `on_unconfirmed`; ends after 24 hours without one). An alarm
 * about a sender (`channel_id` set, as for `pool_member_blocked` and `budget_80`) is sent once for each
 * line holding it. The events that already tell of a change (`line.failover`, `line.no_sender`,
 * `line.quota_low`) are still sent.
 */
final class LineAlarm extends Event
{
    public const TYPE = 'line.alarm';

    public readonly Models\WebhookLineAlarmData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineAlarmData::fromArray($data['data']);
    }
}

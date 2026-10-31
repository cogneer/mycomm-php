<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * What an app, a partner or the owner sends from and receives into, through its ordered sender list.
 */
final class Line extends Model
{
    public readonly string $id;

    public readonly Enums\LineKind|string $kind;

    public readonly string $name;

    public readonly Enums\LineSendMode|string $send_mode;

    /**
     * Keep each contact on the sender that first sent to it while that sender is healthy (default `true`). While it is not, the contact's messages go through the line's normal order and return to it once it is healthy again.
     */
    public readonly bool $sticky_sender;

    public readonly LineFailoverSeconds $failover_seconds;

    public readonly Enums\LinePrefer|string $prefer;

    public readonly LineCrossTypeFallback $cross_type_fallback;

    /**
     * How long a reply on a channel this line wrote from comes back to this line.
     */
    public readonly int $reply_window_days;

    /**
     * What happens to a message a revoked phone may or may not have sent: `fail` (default) or `resend` through the next sender, accepting a possible duplicate.
     */
    public readonly Enums\LineOnUnconfirmed|string $on_unconfirmed;

    public readonly LineAlarmThresholds $alarm_thresholds;

    /**
     * Whether the line's conversations show in the portal's conversation view.
     */
    public readonly bool $show_in_portal;

    /**
     * @var list<LineSender>
     */
    public readonly array $senders;

    public readonly LineSettings $settings;

    public readonly int $open_conversations;

    /**
     * Open conversations with unread messages.
     */
    public readonly int $unread_conversations;

    public readonly ?DateTimeImmutable $archived_at;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->kind = self::enum(Enums\LineKind::class, $data['kind']);
        $this->name = $data['name'];
        $this->send_mode = self::enum(Enums\LineSendMode::class, $data['send_mode']);
        $this->sticky_sender = $data['sticky_sender'];
        $this->failover_seconds = LineFailoverSeconds::fromArray($data['failover_seconds']);
        $this->prefer = self::enum(Enums\LinePrefer::class, $data['prefer']);
        $this->cross_type_fallback = LineCrossTypeFallback::fromArray($data['cross_type_fallback']);
        $this->reply_window_days = $data['reply_window_days'];
        $this->on_unconfirmed = self::enum(Enums\LineOnUnconfirmed::class, $data['on_unconfirmed']);
        $this->alarm_thresholds = LineAlarmThresholds::fromArray($data['alarm_thresholds']);
        $this->show_in_portal = $data['show_in_portal'];
        $this->senders = array_map(static fn (array $item): LineSender => LineSender::fromArray($item), array_values($data['senders']));
        $this->settings = LineSettings::fromArray($data['settings']);
        $this->open_conversations = $data['open_conversations'];
        $this->unread_conversations = $data['unread_conversations'];
        $this->archived_at = isset($data['archived_at']) ? self::dateTime($data['archived_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}

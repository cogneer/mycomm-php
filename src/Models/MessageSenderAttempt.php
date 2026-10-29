<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * One stretch of a message on one sender. `ended_at` is `null` while it is still on that sender.
 */
final class MessageSenderAttempt extends Model
{
    public readonly string $channel_id;

    /**
     * `queued` and `leased` while open; `sent`, `failed`, `expired`, `cancelled` or `reassigned` (moved to the next sender) once ended. May gain values.
     */
    public readonly Enums\MessageSenderAttemptOutcome|string $outcome;

    /**
     * The failure code (`failed`, or `queued` after a failure that will be retried), or why it moved (`reassigned`): `sender_removed`, `sender_unhealthy`, `no_sender_available`, `send_failed:<code>`.
     */
    public readonly ?string $reason;

    public readonly DateTimeImmutable $started_at;

    public readonly ?DateTimeImmutable $ended_at;

    protected function hydrate(array $data): void
    {
        $this->channel_id = $data['channel_id'];
        $this->outcome = self::enum(Enums\MessageSenderAttemptOutcome::class, $data['outcome']);
        $this->reason = $data['reason'] ?? null;
        $this->started_at = self::dateTime($data['started_at']);
        $this->ended_at = isset($data['ended_at']) ? self::dateTime($data['ended_at']) : null;
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * Payload of line.quota_low and line.quota_exhausted: the sponsored allowance of one channel type for this
 * cycle.
 */
final class WebhookLineQuotaData extends Model
{
    public readonly string $line_id;

    /**
     * The partner's own reference for the line.
     */
    public readonly ?string $external_ref;

    /**
     * For example `android_sim`.
     */
    public readonly string $channel_type;

    public readonly int $allowance;

    public readonly int $used;

    public readonly DateTimeImmutable $cycle_ends_at;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->external_ref = $data['external_ref'] ?? null;
        $this->channel_type = $data['channel_type'];
        $this->allowance = $data['allowance'];
        $this->used = $data['used'];
        $this->cycle_ends_at = self::dateTime($data['cycle_ends_at']);
    }
}

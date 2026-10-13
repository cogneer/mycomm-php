<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * The stored rate policy with the preset recipient cap mirrored in.
 */
final class ChannelRatePolicy extends Model
{
    /**
     * `sms_v1` for `android_sim`; read-only.
     */
    public readonly string $schema;

    public readonly ChannelRateInterval $interval_ms;

    public readonly ChannelRateCaps $caps;

    public readonly ChannelRateWarmup $warmup;

    public readonly ?ChannelActiveHours $active_hours;

    public readonly ?ChannelBulkHours $bulk_hours;

    public readonly ?DateTimeImmutable $acknowledged_risk_at;

    protected function hydrate(array $data): void
    {
        $this->schema = $data['schema'];
        $this->interval_ms = ChannelRateInterval::fromArray($data['interval_ms']);
        $this->caps = ChannelRateCaps::fromArray($data['caps']);
        $this->warmup = ChannelRateWarmup::fromArray($data['warmup']);
        $this->active_hours = isset($data['active_hours']) ? ChannelActiveHours::fromArray($data['active_hours']) : null;
        $this->bulk_hours = isset($data['bulk_hours']) ? ChannelBulkHours::fromArray($data['bulk_hours']) : null;
        $this->acknowledged_risk_at = isset($data['acknowledged_risk_at']) ? self::dateTime($data['acknowledged_risk_at']) : null;
    }
}

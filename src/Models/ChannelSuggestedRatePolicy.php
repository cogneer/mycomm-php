<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * The platform's suggestion for this number; the policy without `acknowledged_risk_at`.
 */
final class ChannelSuggestedRatePolicy extends Model
{
    public readonly string $schema;

    public readonly ChannelRateInterval $interval_ms;

    public readonly ChannelRateCaps $caps;

    public readonly ChannelRateWarmup $warmup;

    public readonly ?ChannelActiveHours $active_hours;

    public readonly ?ChannelBulkHours $bulk_hours;

    protected function hydrate(array $data): void
    {
        $this->schema = $data['schema'];
        $this->interval_ms = ChannelRateInterval::fromArray($data['interval_ms']);
        $this->caps = ChannelRateCaps::fromArray($data['caps']);
        $this->warmup = ChannelRateWarmup::fromArray($data['warmup']);
        $this->active_hours = isset($data['active_hours']) ? ChannelActiveHours::fromArray($data['active_hours']) : null;
        $this->bulk_hours = isset($data['bulk_hours']) ? ChannelBulkHours::fromArray($data['bulk_hours']) : null;
    }
}

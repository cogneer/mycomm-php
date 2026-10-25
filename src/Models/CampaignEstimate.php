<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * The review figures of a campaign; nothing is saved.
 */
final class CampaignEstimate extends Model
{
    public readonly int $recipients;

    /**
     * @var array<string, int>
     */
    public readonly array $skipped;

    public readonly CampaignEstimateSegmentsPerMessage $segments_per_message;

    public readonly int $total_segments;

    public readonly DateTimeImmutable $start_at;

    public readonly ?DateTimeImmutable $eta_at;

    public readonly ?bool $eta_beyond_60_days;

    /**
     * @var list<Warning>
     */
    public readonly array $warnings;

    /**
     * Recipients that lack a variable without fallback; `first` lists at most 20.
     */
    public readonly ?CampaignEstimateMissingVariables $missing_variables;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $risk;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $consent;

    public readonly ?int $landlines;

    protected function hydrate(array $data): void
    {
        $this->recipients = $data['recipients'];
        $this->skipped = $data['skipped'];
        $this->segments_per_message = CampaignEstimateSegmentsPerMessage::fromArray($data['segments_per_message']);
        $this->total_segments = $data['total_segments'];
        $this->start_at = self::dateTime($data['start_at']);
        $this->eta_at = isset($data['eta_at']) ? self::dateTime($data['eta_at']) : null;
        $this->eta_beyond_60_days = $data['eta_beyond_60_days'] ?? null;
        $this->warnings = array_map(static fn (array $item): Warning => Warning::fromArray($item), array_values($data['warnings']));
        $this->missing_variables = isset($data['missing_variables']) ? CampaignEstimateMissingVariables::fromArray($data['missing_variables']) : null;
        $this->risk = $data['risk'] ?? null;
        $this->consent = $data['consent'] ?? null;
        $this->landlines = $data['landlines'] ?? null;
    }
}

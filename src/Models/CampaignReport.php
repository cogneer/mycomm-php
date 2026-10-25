<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class CampaignReport extends Model
{
    public readonly string $campaign_id;

    public readonly Enums\CampaignStatus|string $status;

    /**
     * `campaigns.stats`, as on the campaign resource.
     */
    public readonly CampaignReportStats $stats;

    public readonly CampaignReportRates $rates;

    /**
     * Failure code to count.
     *
     * @var array<string, int>
     */
    public readonly array $failures_by_code;

    /**
     * @var list<CampaignReportTimeline>
     */
    public readonly array $timeline;

    /**
     * @var list<CampaignReportLink>
     */
    public readonly array $links;

    public readonly ?DateTimeImmutable $started_at;

    public readonly ?DateTimeImmutable $completed_at;

    protected function hydrate(array $data): void
    {
        $this->campaign_id = $data['campaign_id'];
        $this->status = self::enum(Enums\CampaignStatus::class, $data['status']);
        $this->stats = CampaignReportStats::fromArray($data['stats']);
        $this->rates = CampaignReportRates::fromArray($data['rates']);
        $this->failures_by_code = $data['failures_by_code'];
        $this->timeline = array_map(static fn (array $item): CampaignReportTimeline => CampaignReportTimeline::fromArray($item), array_values($data['timeline']));
        $this->links = array_map(static fn (array $item): CampaignReportLink => CampaignReportLink::fromArray($item), array_values($data['links']));
        $this->started_at = isset($data['started_at']) ? self::dateTime($data['started_at']) : null;
        $this->completed_at = isset($data['completed_at']) ? self::dateTime($data['completed_at']) : null;
    }
}

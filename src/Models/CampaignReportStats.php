<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * `campaigns.stats`, as on the campaign resource.
 */
final class CampaignReportStats extends Model
{
    public readonly int $total;

    public readonly int $sent;

    public readonly int $delivered;

    public readonly int $failed;

    public readonly ?int $replies;

    public readonly ?int $opt_outs;

    public readonly ?int $clicks;

    public readonly ?int $unique_clicks;

    /**
     * @var array<string, int>|null
     */
    public readonly ?array $skipped;

    protected function hydrate(array $data): void
    {
        $this->total = $data['total'];
        $this->sent = $data['sent'];
        $this->delivered = $data['delivered'];
        $this->failed = $data['failed'];
        $this->replies = $data['replies'] ?? null;
        $this->opt_outs = $data['opt_outs'] ?? null;
        $this->clicks = $data['clicks'] ?? null;
        $this->unique_clicks = $data['unique_clicks'] ?? null;
        $this->skipped = $data['skipped'] ?? null;
    }
}

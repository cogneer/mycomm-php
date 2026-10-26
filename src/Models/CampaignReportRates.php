<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class CampaignReportRates extends Model
{
    public readonly ?float $delivery_rate;

    public readonly ?float $failure_rate;

    public readonly ?float $reply_rate;

    public readonly ?float $opt_out_rate;

    public readonly ?float $click_rate;

    protected function hydrate(array $data): void
    {
        $this->delivery_rate = $data['delivery_rate'] ?? null;
        $this->failure_rate = $data['failure_rate'] ?? null;
        $this->reply_rate = $data['reply_rate'] ?? null;
        $this->opt_out_rate = $data['opt_out_rate'] ?? null;
        $this->click_rate = $data['click_rate'] ?? null;
    }
}

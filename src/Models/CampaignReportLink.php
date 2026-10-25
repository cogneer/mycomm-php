<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class CampaignReportLink extends Model
{
    public readonly string $target_url;

    /**
     * Human clicks on the campaign's short links to this URL.
     */
    public readonly int $clicks;

    /**
     * Recipients who clicked it at least once (bot clicks excluded).
     */
    public readonly int $unique_clicks;

    protected function hydrate(array $data): void
    {
        $this->target_url = $data['target_url'];
        $this->clicks = $data['clicks'];
        $this->unique_clicks = $data['unique_clicks'];
    }
}

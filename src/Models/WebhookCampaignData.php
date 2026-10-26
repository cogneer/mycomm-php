<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Data of campaign.started and campaign.completed.
 */
final class WebhookCampaignData extends Model
{
    public readonly Campaign $campaign;

    protected function hydrate(array $data): void
    {
        $this->campaign = Campaign::fromArray($data['campaign']);
    }
}

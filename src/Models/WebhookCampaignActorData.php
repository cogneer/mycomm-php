<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Data of campaign.scheduled, unscheduled, paused, resumed and cancelled.
 */
final class WebhookCampaignActorData extends Model
{
    public readonly Campaign $campaign;

    public readonly CampaignActor $actor;

    protected function hydrate(array $data): void
    {
        $this->campaign = Campaign::fromArray($data['campaign']);
        $this->actor = CampaignActor::fromArray($data['actor']);
    }
}

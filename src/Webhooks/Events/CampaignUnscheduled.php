<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `campaign.unscheduled`: A scheduled campaign went back to `draft` (`scheduled_at` cleared). `data.actor`
 * is who did it. `data.campaign` is the campaign resource as it is when the event is delivered.
 */
final class CampaignUnscheduled extends Event
{
    public const TYPE = 'campaign.unscheduled';

    public readonly Models\WebhookCampaignActorData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookCampaignActorData::fromArray($data['data']);
    }
}

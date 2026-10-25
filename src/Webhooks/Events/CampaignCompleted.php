<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `campaign.completed`: Every recipient of the campaign is final. `data.campaign` carries the final stats.
 * `data.campaign` is the campaign resource as it is when the event is delivered.
 */
final class CampaignCompleted extends Event
{
    public const TYPE = 'campaign.completed';

    public readonly Models\WebhookCampaignData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookCampaignData::fromArray($data['data']);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `campaign.scheduled`: A draft campaign was scheduled, or a scheduled campaign moved to a new time.
 * `data.actor` is who did it. `data.campaign` is the campaign resource as it is when the event is
 * delivered.
 */
final class CampaignScheduled extends Event
{
    public const TYPE = 'campaign.scheduled';

    public readonly Models\WebhookCampaignActorData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookCampaignActorData::fromArray($data['data']);
    }
}

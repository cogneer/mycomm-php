<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `campaign.paused`: A running campaign was paused; queued campaign messages are held. `data.actor` is who
 * did it (`system` for an automatic pause). `data.campaign` is the campaign resource as it is when the
 * event is delivered.
 */
final class CampaignPaused extends Event
{
    public const TYPE = 'campaign.paused';

    public readonly Models\WebhookCampaignActorData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookCampaignActorData::fromArray($data['data']);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `campaign.started`: A campaign entered `running` for the first time; a later entry from `paused` is
 * `campaign.resumed`. `data.campaign` is the campaign resource as it is when the event is delivered.
 */
final class CampaignStarted extends Event
{
    public const TYPE = 'campaign.started';

    public readonly Models\WebhookCampaignData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookCampaignData::fromArray($data['data']);
    }
}

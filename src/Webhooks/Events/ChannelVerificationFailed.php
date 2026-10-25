<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.verification_failed`: Verification failed after 3 attempts, or at once for `address_in_use` and
 * `declined_on_device`. `data.reason` is the `failure_reason` of the verification.
 */
final class ChannelVerificationFailed extends Event
{
    public const TYPE = 'channel.verification_failed';

    public readonly Models\WebhookChannelVerificationFailedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelVerificationFailedData::fromArray($data['data']);
    }
}

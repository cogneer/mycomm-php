<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;
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

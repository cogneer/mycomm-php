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
 * `channel.resumed`: A paused channel is `active` again.
 */
final class ChannelResumed extends Event
{
    public const TYPE = 'channel.resumed';

    public readonly Models\WebhookChannelData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelData::fromArray($data['data']);
    }
}

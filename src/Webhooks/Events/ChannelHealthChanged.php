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
 * `channel.health_changed`: The SIM safety band of the channel changed. `data.health` is the `health`
 * block of the channel resource.
 */
final class ChannelHealthChanged extends Event
{
    public const TYPE = 'channel.health_changed';

    public readonly Models\WebhookChannelHealthChangedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelHealthChangedData::fromArray($data['data']);
    }
}

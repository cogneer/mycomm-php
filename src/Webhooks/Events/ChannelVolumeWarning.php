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
 * `channel.volume_warning`: 80 % of the effective daily cap was used. Sending is not blocked; further
 * messages wait.
 */
final class ChannelVolumeWarning extends Event
{
    public const TYPE = 'channel.volume_warning';

    public readonly Models\WebhookChannelVolumeWarningData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelVolumeWarningData::fromArray($data['data']);
    }
}

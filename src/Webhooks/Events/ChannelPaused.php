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
 * `channel.paused`: The channel was paused. `data.reason` is the `pause_reason`; `dominant_code` and
 * `streak` are set only for `auto:failure_streak`.
 */
final class ChannelPaused extends Event
{
    public const TYPE = 'channel.paused';

    public readonly Models\WebhookChannelPausedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelPausedData::fromArray($data['data']);
    }
}

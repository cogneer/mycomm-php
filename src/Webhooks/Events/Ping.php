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
 * `ping`: Sent by the endpoint test action only; not subscribable.
 */
final class Ping extends Event
{
    public const TYPE = 'ping';

    public readonly Models\WebhookPingData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookPingData::fromArray($data['data']);
    }
}

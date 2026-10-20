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
 * `message.queued`: A message was accepted, or a manual retry re-queued it. Opt-in: list it in the
 * endpoint's `events` (03 section 5.2, 5.7).
 */
final class MessageQueued extends Event
{
    public const TYPE = 'message.queued';

    public readonly Models\WebhookMessageData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageData::fromArray($data['data']);
    }
}

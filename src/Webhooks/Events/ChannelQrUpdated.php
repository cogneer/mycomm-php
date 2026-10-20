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
 * `channel.qr_updated`: The WhatsApp QR code to scan was regenerated while the number connects; each one
 * stops working at `data.expires_at` (about 40 s). Opt-in: list it in the endpoint's `events` (03 section
 * 5.2, 5.7).
 */
final class ChannelQrUpdated extends Event
{
    public const TYPE = 'channel.qr_updated';

    public readonly Models\WebhookChannelQrUpdatedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelQrUpdatedData::fromArray($data['data']);
    }
}

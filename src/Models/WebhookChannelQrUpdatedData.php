<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

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
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class WebhookChannelQrUpdatedData extends Model
{
    public readonly string $channel_id;

    /**
     * The QR code as a base64 PNG, to show to the person who scans it; `null` when the provider sent none.
     */
    public readonly ?string $qr_png_base64;

    public readonly ?DateTimeImmutable $expires_at;

    protected function hydrate(array $data): void
    {
        $this->channel_id = $data['channel_id'];
        $this->qr_png_base64 = $data['qr_png_base64'] ?? null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
    }
}

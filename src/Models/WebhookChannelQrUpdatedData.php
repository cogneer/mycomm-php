<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

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

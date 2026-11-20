<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * Where linking a number stands. `state` is a link state while linking runs or after it stopped:
 * `connecting` (the server is getting ready, or could not be asked; see `refusal`), `awaiting_link` (the
 * phone must type `pairing_code` or scan `qr_png_base64`), `passkey_required`, `failed`, `expired` (try
 * again with `POST /v1/channels/{id}/connect`). Otherwise it is the number's own state: `warming_up`,
 * `connected`, `limited`, `paused`, `server_problem`, `needs_relink` (link it again), `banned`,
 * `disconnected`. Every key is always present; ignore unknown `state` and `refusal.code` values.
 */
final class ChannelConnectState extends Model
{
    public readonly Enums\ChannelLinkState|string $state;

    /**
     * The method of the running or last attempt; null once the number is linked.
     */
    public readonly Enums\ChannelLinkMethod|string|null $method;

    /**
     * The code to type on the phone (WhatsApp, Linked devices, Link with phone number).
     */
    public readonly ?string $pairing_code;

    /**
     * The QR to scan, a PNG in base64. It changes every 20 seconds or so; poll for the new one.
     */
    public readonly ?string $qr_png_base64;

    /**
     * When the QR runs out.
     */
    public readonly ?DateTimeImmutable $expires_at;

    /**
     * Why linking does not go on, or null.
     */
    public readonly ?ChannelConnectStateRefusal $refusal;

    protected function hydrate(array $data): void
    {
        $this->state = self::enum(Enums\ChannelLinkState::class, $data['state']);
        $this->method = isset($data['method']) ? self::enum(Enums\ChannelLinkMethod::class, $data['method']) : null;
        $this->pairing_code = $data['pairing_code'] ?? null;
        $this->qr_png_base64 = $data['qr_png_base64'] ?? null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->refusal = isset($data['refusal']) ? ChannelConnectStateRefusal::fromArray($data['refusal']) : null;
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class ChannelConnectStateRefusal extends Model
{
    /**
     * `wrong_number`: the phone linked another number than the one entered. `number_in_use`: the linked number is already connected on MyComm. `expired`: the code or QR ran out. `passkey_required`: the number needs a passkey,…
     */
    public readonly Enums\ChannelLinkRefusalCode|string $code;

    public readonly string $message;

    /**
     * When to poll again (`server_unavailable`).
     */
    public readonly ?int $retry_after_seconds;

    /**
     * `number_in_use` only: the linked number is your own number that needs to be linked again; link it again with `POST /v1/channels/{channel_id}/connect` `{action: relink}`. Absent for any other holder, and for a channel th…
     */
    public readonly ?string $channel_id;

    protected function hydrate(array $data): void
    {
        $this->code = self::enum(Enums\ChannelLinkRefusalCode::class, $data['code']);
        $this->message = $data['message'];
        $this->retry_after_seconds = $data['retry_after_seconds'] ?? null;
        $this->channel_id = $data['channel_id'] ?? null;
    }
}

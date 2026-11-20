<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

/**
 * How the phone links the number.
 */
final class ChannelConnectInput extends Input
{
    /**
     * @param  Enums\ChannelLinkMethod|string  $method  `pairing_code`: a code to type on the phone (needs `phone`); `qr_scan`: a QR to scan with it.
     * @param  string|Omit|null  $phone  The WhatsApp number to link, in E.164 (`+923001234567`). Required for `pairing_code`; anything else is `422 INVALID_NUMBER`.
     */
    public function __construct(
        public readonly Enums\ChannelLinkMethod|string $method,
        public readonly string|Omit|null $phone = Omit::Value,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'method' => $this->method,
            'phone' => $this->phone,
        ]);
    }
}

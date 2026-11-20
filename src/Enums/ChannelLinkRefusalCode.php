<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `wrong_number`: the phone linked another number than the one entered. `number_in_use`: the linked number
 * is already connected on MyComm. `expired`: the code or QR ran out. `passkey_required`: the number needs
 * a passkey, which this server cannot do. `failed`: linking did not finish. `phone_changed`: replaced by
 * an attempt for another number. `server_unavailable`: the server could not be asked; the rest is the last
 * known state.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelLinkRefusalCode: string
{
    case WrongNumber = 'wrong_number';
    case NumberInUse = 'number_in_use';
    case Expired = 'expired';
    case PasskeyRequired = 'passkey_required';
    case Failed = 'failed';
    case PhoneChanged = 'phone_changed';
    case ServerUnavailable = 'server_unavailable';
}

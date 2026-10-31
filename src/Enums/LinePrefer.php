<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The channel type the line sends with first (default `auto`): `sms`, `whatsapp`, or `auto` = the type of
 * the channel the contact last wrote to on this line while that message is inside the type's service
 * window (WhatsApp 24 h; SMS the line's `reply_window_days`), else the type of the first sender. When the
 * preferred type has no healthy sender, or cannot reach the contact, the next type sends. A reply in a
 * conversation (and a campaign through its channel) tries the type of that channel first, whatever the
 * line prefers.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LinePrefer: string
{
    case Auto = 'auto';
    case Sms = 'sms';
    case Whatsapp = 'whatsapp';
}

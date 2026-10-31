<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The channel type to try first when the line picks the sender; beats the line's `prefer`. When that type
 * has no healthy sender or cannot reach the recipient, the line's next type sends.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessagePrefer: string
{
    case Sms = 'sms';
    case Whatsapp = 'whatsapp';
}

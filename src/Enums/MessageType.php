<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * How the message travels. `sms` and `mms` are the SMS channel; the others belong to channels that are not
 * available yet. The values are generated from the platform; a new one may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageType: string
{
    case Sms = 'sms';
    case Mms = 'mms';
    case Whatsapp = 'whatsapp';
    case Telegram = 'telegram';
}

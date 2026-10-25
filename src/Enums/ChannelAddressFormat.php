<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelAddressFormat: string
{
    case E164 = 'e164';
    case TelegramUserId = 'telegram_user_id';
}

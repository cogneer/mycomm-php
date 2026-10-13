<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum StatsGroupBy: string
{
    case None = 'none';
    case Channel = 'channel';
    case Inbox = 'inbox';
    case ChannelType = 'channel_type';
}

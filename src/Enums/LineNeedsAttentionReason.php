<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineNeedsAttentionReason: string
{
    case DeviceOffline = 'device_offline';
    case SimMissing = 'sim_missing';
    case SessionLoggedOut = 'session_logged_out';
    case NoSender = 'no_sender';
    case QuotaExhausted = 'quota_exhausted';
    case NumberDisputed = 'number_disputed';
    case Quarantined = 'quarantined';
}

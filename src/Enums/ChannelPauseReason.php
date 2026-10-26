<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Set while `paused`.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelPauseReason: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case AccountSuspended = 'account_suspended';
    case AutoHealthCritical = 'auto:health_critical';
    case AutoQuarantine = 'auto:quarantine';
    case AutoQuota = 'auto:quota';
    case AutoSimMissing = 'auto:sim_missing';
    case DeviceRevoked = 'device_revoked';
    case AutoFailureStreak = 'auto:failure_streak';
    case AutoSessionLost = 'auto:session_lost';
    case AutoProviderBanned = 'auto:provider_banned';
    case AutoCredentialsInvalid = 'auto:credentials_invalid';
}

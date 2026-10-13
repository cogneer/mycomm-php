<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Set when `disconnected`.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelDisconnectReason: string
{
    case Owner = 'owner';
    case AdminForceRelease = 'admin_force_release';
    case AccountDeleted = 'account_deleted';
    case DeclinedOnDevice = 'declined_on_device';
    case SimRetired = 'sim_retired';
    case DeviceRevoked = 'device_revoked';
    case SimRemoved = 'sim_removed';
    case SessionLost = 'session_lost';
    case ProviderBanned = 'provider_banned';
    case CredentialsInvalid = 'credentials_invalid';
}

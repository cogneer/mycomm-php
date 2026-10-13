<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelVerificationFailureReason: string
{
    case Timeout = 'timeout';
    case DeviceOffline = 'device_offline';
    case SendFailed = 'send_failed';
    case SimMissing = 'sim_missing';
    case AddressInUse = 'address_in_use';
    case CodeMismatch = 'code_mismatch';
    case NumberMismatch = 'number_mismatch';
    case DeclinedOnDevice = 'declined_on_device';
    case QrExpired = 'qr_expired';
    case TokenInvalid = 'token_invalid';
    case DeviceSimLimit = 'device_sim_limit';
    case CountryNotEnabled = 'country_not_enabled';
}

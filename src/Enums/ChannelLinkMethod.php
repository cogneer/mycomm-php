<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `pairing_code`: a code to type on the phone (needs `phone`); `qr_scan`: a QR to scan with it.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelLinkMethod: string
{
    case PairingCode = 'pairing_code';
    case QrScan = 'qr_scan';
}

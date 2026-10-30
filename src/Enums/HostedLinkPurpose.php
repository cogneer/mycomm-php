<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum HostedLinkPurpose: string
{
    case Connect = 'connect';
    case AddChannel = 'add_channel';
    case ReplaceDevice = 'replace_device';
    case Manage = 'manage';
    case Claim = 'claim';
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `partner` with the partner key, `owner` by a person of the account, `system` by MyComm.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineDetachedBy: string
{
    case Partner = 'partner';
    case Owner = 'owner';
    case System = 'system';
}

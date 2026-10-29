<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * What happens to a message a revoked phone may or may not have sent: `fail` (default) or `resend` through
 * the next sender, accepting a possible duplicate.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineOnUnconfirmed: string
{
    case Fail = 'fail';
    case Resend = 'resend';
}

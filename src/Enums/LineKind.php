<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `direct`: the account's own line, where unclaimed replies land (one per account). `app`: a line an app
 * or integration sends through. `partner`: a line a partner created for a client.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineKind: string
{
    case Direct = 'direct';
    case App = 'app';
    case Partner = 'partner';
}

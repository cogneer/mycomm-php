<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `direct`: a number's own line (one per number, made when it verifies), where replies no other line
 * claims land; it sends only through that number. `app`: an integration line an app or the portal sends
 * through, over one or more numbers. `partner`: an integration line a partner created for a client.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineKind: string
{
    case Direct = 'direct';
    case App = 'app';
    case Partner = 'partner';
}

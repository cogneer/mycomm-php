<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `ordered`: the first healthy sender by position sends. `pool` is reserved for spreading sends over
 * several senders.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineSendMode: string
{
    case Ordered = 'ordered';
    case Pool = 'pool';
}

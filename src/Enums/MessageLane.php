<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The sending priority: `high` goes first, `normal` is the default, `bulk` waits for quiet capacity and
 * requires a `category`.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageLane: string
{
    case High = 'high';
    case Normal = 'normal';
    case Bulk = 'bulk';
}

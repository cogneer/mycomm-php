<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Where an outbound message is: `queued` (accepted, may be held, see `hold_reason`), `leased` (a phone has
 * it), `sent`, `delivered`, `read`, `failed` (see `failure`), `expired`, `cancelled`. An inbound message
 * is `received`. A new value may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageStatus: string
{
    case Queued = 'queued';
    case Leased = 'leased';
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Read = 'read';
    case Failed = 'failed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Received = 'received';
}

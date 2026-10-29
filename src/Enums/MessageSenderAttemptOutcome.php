<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `queued` and `leased` while open; `sent`, `failed`, `expired`, `cancelled` or `reassigned` (moved to the
 * next sender) once ended. May gain values.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageSenderAttemptOutcome: string
{
    case Queued = 'queued';
    case Leased = 'leased';
    case Sent = 'sent';
    case Failed = 'failed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Reassigned = 'reassigned';
}

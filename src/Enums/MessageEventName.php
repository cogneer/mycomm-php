<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * What happened to a message, as recorded in its history. A new value may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageEventName: string
{
    case Accepted = 'accepted';
    case Held = 'held';
    case Leased = 'leased';
    case Released = 'released';
    case Orphaned = 'orphaned';
    case CancelRequested = 'cancel_requested';
    case CancelFailed = 'cancel_failed';
    case Sent = 'sent';
    case DlrPart = 'dlr_part';
    case Delivered = 'delivered';
    case Read = 'read';
    case Failed = 'failed';
    case RetryScheduled = 'retry_scheduled';
    case LateSent = 'late_sent';
    case LateDelivered = 'late_delivered';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Received = 'received';
    case ProviderAck = 'provider_ack';
    case ProviderCallStarted = 'provider_call_started';
    case Edited = 'edited';
    case DeletedRemote = 'deleted_remote';
}

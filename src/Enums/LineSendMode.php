<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * How the line picks the sender of a message that names no channel. `ordered`: the first healthy sender by
 * position; the primary is always at position 0. `pool`: the healthy `primary` and `pool` senders share
 * the sending, the one with the most of its hourly budget left first (ties by position); `backup` senders
 * send only when no pool sender is healthy. A message sent with `channel_id` or `from` always uses that
 * channel.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum LineSendMode: string
{
    case Ordered = 'ordered';
    case Pool = 'pool';
}

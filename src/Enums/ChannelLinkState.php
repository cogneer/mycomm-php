<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelLinkState: string
{
    case Connecting = 'connecting';
    case AwaitingLink = 'awaiting_link';
    case PasskeyRequired = 'passkey_required';
    case Failed = 'failed';
    case Expired = 'expired';
    case Connected = 'connected';
    case WarmingUp = 'warming_up';
    case Limited = 'limited';
    case Paused = 'paused';
    case ServerProblem = 'server_problem';
    case NeedsRelink = 'needs_relink';
    case Banned = 'banned';
    case Disconnected = 'disconnected';
}

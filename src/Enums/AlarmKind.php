<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum AlarmKind: string
{
    case Failover = 'failover';
    case NoSender = 'no_sender';
    case PoolMemberBlocked = 'pool_member_blocked';
    case Budget80 = 'budget_80';
    case SelfLoop = 'self_loop';
    case SponsorQuotaLow = 'sponsor_quota_low';
    case Unconfirmed = 'unconfirmed';
}

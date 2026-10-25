<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `applied` would run the actions; `skipped:*` is the guard that would hold the reply back (a paused
 * conversation is `skipped:loop_guard`); `no_match` runs nothing.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum AutomationDryRunOutcome: string
{
    case Applied = 'applied';
    case SkippedCooldown = 'skipped:cooldown';
    case SkippedChannelCap = 'skipped:channel_cap';
    case SkippedLoopGuard = 'skipped:loop_guard';
    case NoMatch = 'no_match';
}

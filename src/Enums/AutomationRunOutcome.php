<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum AutomationRunOutcome: string
{
    case Applied = 'applied';
    case SkippedCooldown = 'skipped:cooldown';
    case SkippedChannelCap = 'skipped:channel_cap';
    case SkippedLoopGuard = 'skipped:loop_guard';
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The latest progress step.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelVerificationStep: string
{
    case Started = 'started';
    case AwaitingConfirmation = 'awaiting_confirmation';
    case Confirmed = 'confirmed';
    case CodeSent = 'code_sent';
    case Verified = 'verified';
    case Failed = 'failed';
}

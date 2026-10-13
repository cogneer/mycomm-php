<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Risk of the stored policy against the suggestion.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ChannelRatePolicyRisk: string
{
    case Green = 'green';
    case Amber = 'amber';
    case Red = 'red';
}

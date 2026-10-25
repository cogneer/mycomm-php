<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `transactional` for messages the contact expects (codes, receipts); `marketing` for promotions, which
 * are held outside bulk hours and checked against opt-outs.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageCategory: string
{
    case Transactional = 'transactional';
    case Marketing = 'marketing';
}

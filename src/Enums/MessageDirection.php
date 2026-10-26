<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * `outbound` is a message you or an automation sent; `inbound` is one the contact sent. The values are
 * generated from the platform; a new one may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageDirection: string
{
    case Outbound = 'outbound';
    case Inbound = 'inbound';
}

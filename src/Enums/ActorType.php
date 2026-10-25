<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Who did it: a `user` in the portal, an `api_key`, a `partner_key`, an `automation` rule, a `campaign`,
 * the `system`, or a `device`. A new value may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ActorType: string
{
    case User = 'user';
    case ApiKey = 'api_key';
    case PartnerKey = 'partner_key';
    case Automation = 'automation';
    case Campaign = 'campaign';
    case System = 'system';
    case Device = 'device';
    case Admin = 'admin';
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum CampaignRecipientSkipReason: string
{
    case Suppressed = 'suppressed';
    case Duplicate = 'duplicate';
    case InvalidNumber = 'invalid_number';
    case NotMobile = 'not_mobile';
    case ForeignNumber = 'foreign_number';
    case Blocked = 'blocked';
    case NoIdentity = 'no_identity';
    case NoIdentityForChannel = 'no_identity_for_channel';
    case NoPriorContact = 'no_prior_contact';
    case NotReachable = 'not_reachable';
    case NoConsent = 'no_consent';
    case BodyTooLong = 'body_too_long';
}

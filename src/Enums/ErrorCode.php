<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Stable UPPER_SNAKE_CASE error code. Clients must tolerate unknown codes.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ErrorCode: string
{
    case MalformedRequest = 'MALFORMED_REQUEST';
    case AccountContextRequired = 'ACCOUNT_CONTEXT_REQUIRED';
    case InvalidCursor = 'INVALID_CURSOR';
    case Unauthenticated = 'UNAUTHENTICATED';
    case WrongKeyKind = 'WRONG_KEY_KIND';
    case AbilityMissing = 'ABILITY_MISSING';
    case IpNotAllowed = 'IP_NOT_ALLOWED';
    case AccountContextInvalid = 'ACCOUNT_CONTEXT_INVALID';
    case AccountSuspended = 'ACCOUNT_SUSPENDED';
    case AccountPendingDeletion = 'ACCOUNT_PENDING_DELETION';
    case PartnerAccessRevoked = 'PARTNER_ACCESS_REVOKED';
    case PartnerKeyRestricted = 'PARTNER_KEY_RESTRICTED';
    case AppConnectNotAllowed = 'APP_CONNECT_NOT_ALLOWED';
    case NotFound = 'NOT_FOUND';
    case DeviceRevoked = 'DEVICE_REVOKED';
    case MethodNotAllowed = 'METHOD_NOT_ALLOWED';
    case IdempotencyKeyReused = 'IDEMPOTENCY_KEY_REUSED';
    case IdempotencyInProgress = 'IDEMPOTENCY_IN_PROGRESS';
    case ReferenceIdExists = 'REFERENCE_ID_EXISTS';
    case InvalidState = 'INVALID_STATE';
    case NotCancellable = 'NOT_CANCELLABLE';
    case NotRetryable = 'NOT_RETRYABLE';
    case RetryLimitReached = 'RETRY_LIMIT_REACHED';
    case NameTaken = 'NAME_TAKEN';
    case IdentityTaken = 'IDENTITY_TAKEN';
    case PartnerHasClients = 'PARTNER_HAS_CLIENTS';
    case ExternalRefTaken = 'EXTERNAL_REF_TAKEN';
    case SuspendedBySupport = 'SUSPENDED_BY_SUPPORT';
    case ChannelAddressInUse = 'CHANNEL_ADDRESS_IN_USE';
    case DeviceInUse = 'DEVICE_IN_USE';
    case DeclinedOnDevice = 'DECLINED_ON_DEVICE';
    case SimAlreadyConnected = 'SIM_ALREADY_CONNECTED';
    case PayloadTooLarge = 'PAYLOAD_TOO_LARGE';
    case ValidationFailed = 'VALIDATION_FAILED';
    case InvalidNumber = 'INVALID_NUMBER';
    case InvalidAddress = 'INVALID_ADDRESS';
    case NotMobile = 'NOT_MOBILE';
    case DestinationNotAllowed = 'DESTINATION_NOT_ALLOWED';
    case RecipientIsChannel = 'RECIPIENT_IS_CHANNEL';
    case BodyTooLong = 'BODY_TOO_LONG';
    case BodyTooLongForBulk = 'BODY_TOO_LONG_FOR_BULK';
    case BodyEmpty = 'BODY_EMPTY';
    case CategoryRequired = 'CATEGORY_REQUIRED';
    case CategoryMismatch = 'CATEGORY_MISMATCH';
    case RecipientOptedOut = 'RECIPIENT_OPTED_OUT';
    case MarketingNotAllowed = 'MARKETING_NOT_ALLOWED';
    case ConsentProvenanceMissing = 'CONSENT_PROVENANCE_MISSING';
    case ContentProhibited = 'CONTENT_PROHIBITED';
    case SendAfterTooFar = 'SEND_AFTER_TOO_FAR';
    case LinkBlocked = 'LINK_BLOCKED';
    case LinkPublicShortener = 'LINK_PUBLIC_SHORTENER';
    case TooManyLinks = 'TOO_MANY_LINKS';
    case ContactBlocked = 'CONTACT_BLOCKED';
    case ChannelInactive = 'CHANNEL_INACTIVE';
    case InboxArchived = 'INBOX_ARCHIVED';
    case ChannelRequired = 'CHANNEL_REQUIRED';
    case ChannelInboxMismatch = 'CHANNEL_INBOX_MISMATCH';
    case FromAmbiguous = 'FROM_AMBIGUOUS';
    case ContentConflict = 'CONTENT_CONFLICT';
    case ChannelCapabilityUnsupported = 'CHANNEL_CAPABILITY_UNSUPPORTED';
    case ChannelCannotInitiate = 'CHANNEL_CANNOT_INITIATE';
    case NoIdentityForChannel = 'NO_IDENTITY_FOR_CHANNEL';
    case ConversationWindowClosed = 'CONVERSATION_WINDOW_CLOSED';
    case IdentityTypeUnavailable = 'IDENTITY_TYPE_UNAVAILABLE';
    case ChannelTypeNotAllowed = 'CHANNEL_TYPE_NOT_ALLOWED';
    case ChannelTokenInvalid = 'CHANNEL_TOKEN_INVALID';
    case MediaTypeUnsupported = 'MEDIA_TYPE_UNSUPPORTED';
    case MediaTooLarge = 'MEDIA_TOO_LARGE';
    case UnsupportedMediaType = 'UNSUPPORTED_MEDIA_TYPE';
    case MediaCountExceeded = 'MEDIA_COUNT_EXCEEDED';
    case MediaNotFound = 'MEDIA_NOT_FOUND';
    case TemplateInvalid = 'TEMPLATE_INVALID';
    case TemplateArchived = 'TEMPLATE_ARCHIVED';
    case FilterInvalid = 'FILTER_INVALID';
    case ConsentNoteRequired = 'CONSENT_NOTE_REQUIRED';
    case ConsentAssertionRequired = 'CONSENT_ASSERTION_REQUIRED';
    case RiskAcknowledgementRequired = 'RISK_ACKNOWLEDGEMENT_REQUIRED';
    case AbovePlatformCeiling = 'ABOVE_PLATFORM_CEILING';
    case NewAccountLimit = 'NEW_ACCOUNT_LIMIT';
    case AccountThrottled = 'ACCOUNT_THROTTLED';
    case AddressRequired = 'ADDRESS_REQUIRED';
    case CountryNotEnabled = 'COUNTRY_NOT_ENABLED';
    case DeviceSimLimit = 'DEVICE_SIM_LIMIT';
    case ConfirmationNotFound = 'CONFIRMATION_NOT_FOUND';
    case ConfirmationExpired = 'CONFIRMATION_EXPIRED';
    case VerificationNotFound = 'VERIFICATION_NOT_FOUND';
    case VerificationExpired = 'VERIFICATION_EXPIRED';
    case VerificationLimitReached = 'VERIFICATION_LIMIT_REACHED';
    case SimNotPresent = 'SIM_NOT_PRESENT';
    case DeviceNotActive = 'DEVICE_NOT_ACTIVE';
    case PairingCodeLimitReached = 'PAIRING_CODE_LIMIT_REACHED';
    case PairingCodeInvalid = 'PAIRING_CODE_INVALID';
    case PairingCodeExpired = 'PAIRING_CODE_EXPIRED';
    case PairingCodeUsed = 'PAIRING_CODE_USED';
    case PairingSessionNotFound = 'PAIRING_SESSION_NOT_FOUND';
    case PairingSessionExpired = 'PAIRING_SESSION_EXPIRED';
    case BatchTooLarge = 'BATCH_TOO_LARGE';
    case AudienceEmpty = 'AUDIENCE_EMPTY';
    case RangeTooLarge = 'RANGE_TOO_LARGE';
    case UpgradeRequired = 'UPGRADE_REQUIRED';
    case RateLimited = 'RATE_LIMITED';
    case InternalError = 'INTERNAL_ERROR';
    case ServiceUnavailable = 'SERVICE_UNAVAILABLE';
}

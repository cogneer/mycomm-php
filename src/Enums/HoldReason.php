<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Why a queued message is waiting: the schedule (`scheduled`, `quiet_hours`, `bulk_hours`), a limit
 * (`frequency_cap`, `content_cap`, `recipient_cap`, `new_recipient_cap`, `account_limit`,
 * `carrier_cooldown`), the phone (`device_offline`, `device_paused`, `device_hot`), the channel or
 * campaign being paused (`channel_paused`, `campaign_paused`), a file still being processed
 * (`media_processing`), a link needing approval (`link_restricted`) or a platform hold (`platform_hold`).
 * A new value may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum HoldReason: string
{
    case Scheduled = 'scheduled';
    case QuietHours = 'quiet_hours';
    case FrequencyCap = 'frequency_cap';
    case ChannelPaused = 'channel_paused';
    case DeviceOffline = 'device_offline';
    case DevicePaused = 'device_paused';
    case CampaignPaused = 'campaign_paused';
    case MediaProcessing = 'media_processing';
    case ContentCap = 'content_cap';
    case RecipientCap = 'recipient_cap';
    case NewRecipientCap = 'new_recipient_cap';
    case LinkRestricted = 'link_restricted';
    case PlatformHold = 'platform_hold';
    case AccountLimit = 'account_limit';
    case BulkHours = 'bulk_hours';
    case CarrierCooldown = 'carrier_cooldown';
    case DeviceHot = 'device_hot';
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

/**
 * Event type => event class.
 *
 * @internal
 */
final class EventMap
{
    /** @var array<string, class-string<Event>> */
    public const EVENTS = [
        'contact.created' => Events\ContactCreated::class,
        'contact.updated' => Events\ContactUpdated::class,
        'contact.deleted' => Events\ContactDeleted::class,
        'import.completed' => Events\ImportCompleted::class,
        'import.failed' => Events\ImportFailed::class,
        'export.completed' => Events\ExportCompleted::class,
        'channel.connected' => Events\ChannelConnected::class,
        'channel.verification_failed' => Events\ChannelVerificationFailed::class,
        'channel.disconnected' => Events\ChannelDisconnected::class,
        'channel.paused' => Events\ChannelPaused::class,
        'channel.resumed' => Events\ChannelResumed::class,
        'channel.health_changed' => Events\ChannelHealthChanged::class,
        'channel.volume_warning' => Events\ChannelVolumeWarning::class,
        'channel.qr_updated' => Events\ChannelQrUpdated::class,
        'device.paired' => Events\DevicePaired::class,
        'device.online' => Events\DeviceOnline::class,
        'device.degraded' => Events\DeviceDegraded::class,
        'device.offline' => Events\DeviceOffline::class,
        'device.revoked' => Events\DeviceRevoked::class,
        'account.suspended' => Events\AccountSuspended::class,
        'account.reactivated' => Events\AccountReactivated::class,
        'account.deletion_scheduled' => Events\AccountDeletionScheduled::class,
        'message.queued' => Events\MessageQueued::class,
        'message.sent' => Events\MessageSent::class,
        'message.delivered' => Events\MessageDelivered::class,
        'message.read' => Events\MessageRead::class,
        'message.failed' => Events\MessageFailed::class,
        'message.expired' => Events\MessageExpired::class,
        'message.cancelled' => Events\MessageCancelled::class,
        'message.received' => Events\MessageReceived::class,
        'conversation.read' => Events\ConversationRead::class,
        'conversation.unread' => Events\ConversationUnread::class,
        'conversation.archived' => Events\ConversationArchived::class,
        'conversation.unarchived' => Events\ConversationUnarchived::class,
        'conversation.deleted' => Events\ConversationDeleted::class,
        'contact.opted_out' => Events\ContactOptedOut::class,
        'contact.opted_in' => Events\ContactOptedIn::class,
        'ping' => Events\Ping::class,
    ];
}

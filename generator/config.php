<?php

declare(strict_types=1);

/*
 * Choices the specs cannot express. Everything else is derived from spec/*.yaml.
 */
return [
    // Spec name => bundled document. The first one carries the webhook events.
    'specs' => [
        'public' => 'spec/public.yaml',
        'partner' => 'spec/partner.yaml',
    ],

    // An inline list of values becomes an enum named after its schema and property. Where several
    // positions carry the same list, or the derived name reads badly, name the enum here. Positions that
    // share a name must list the same values: the generator stops when they drift apart.
    'enums' => [
        'ChannelTypeInfo.type' => 'ChannelType',
        'InboxChannel.type' => 'ChannelType',
        'DailyStatsRow.channel_type' => 'ChannelType',
        'ChannelTypeInfo.address_format' => 'ChannelAddressFormat',
        'ChannelTypeInfo.connect_methods[]' => 'ChannelConnectMethod',
        'ChannelTypeInfo.content_types[]' => 'ChannelContentType',
        'ChannelTypeInfo.delivery_states[]' => 'ChannelDeliveryState',
        'WebhookChannelDisconnectedData.reason' => 'ChannelDisconnectReason',
        'WebhookChannelPausedData.reason' => 'ChannelPauseReason',
        'WebhookChannelVerificationFailedData.reason' => 'ChannelVerificationFailureReason',
        'WebhookChannelHealthChangedData.previous_band' => 'ChannelHealthBand',
        'SendMessageRequest.category' => 'MessageCategory',
        'SendBatchItem.category' => 'MessageCategory',
        'ConversationReplyRequest.category' => 'MessageCategory',
        'SendMessageRequest.lane' => 'MessageLane',
        'SendBatchItem.lane' => 'MessageLane',
        'ConversationReplyRequest.lane' => 'MessageLane',
        'ContactFieldsCreate.type' => 'ContactFieldType',
        'ConsentEvent.event' => 'ConsentEventType',
        'DeviceHealthNetwork.type' => 'DeviceNetworkType',
        'DeviceHealthPower.power_mode' => 'DevicePowerMode',
        'WebhookDeviceRevokedData.reason' => 'DeviceRevokedReason',
        'WebhookDeviceStateData.previous_state' => 'DeviceConnectionState',
        'StatsDailyResponse.group_by' => 'StatsGroupBy',
        'WebhookAccount.type' => 'AccountType',
        'WebhookAccount.status' => 'AccountStatus',
        'ManagedAccount.status' => 'AccountStatus',
    ],

    // An answer described inline in the spec becomes a model named after its operation
    // (`ApiKeysCreateResponse`). Better names, and one class for answers of the same shape, go here.
    'models' => [
        'ApiKeysCreateResponse' => 'CreatedApiKey',
        'PartnerAccountKeysCreateResponse' => 'CreatedApiKey',
        'WebhookEndpointsCreateResponse' => 'CreatedWebhookEndpoint',
        'WebhookEndpointsRotateSecretResponse' => 'RotatedWebhookSecret',
        'WebhookEndpointsTestResponse' => 'WebhookTestResult',
    ],

    // Fields the API returns as null although the spec says they never are, found by running the SDK
    // against the real API. Each entry is a bug in the spec: fix it in the backend's openapi/ fragments,
    // sync, and the generator will ask you to remove the entry here.
    'nullable' => [
        'WebhookConsentData.contact_id' => 'contact.opted_out / contact.opted_in for an address that belongs to no contact (ConsentPayload sets it to null).',
    ],

    // An operation that accepts JSON and multipart bodies becomes two methods: the JSON one keeps the
    // operation's name, the multipart one is named here.
    'multipart' => [
        'imports.create' => 'createFromFile',
    ],
];

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Concerns;

use MyComm\Http\Connection;
use MyComm\Resources;

/**
 * The top-level resources of the API.
 *
 * @internal
 */
trait ProvidesResources
{
    public readonly Resources\Account $account;

    public readonly Resources\ApiKeys $apiKeys;

    public readonly Resources\AutomationRules $automationRules;

    public readonly Resources\AutomationRuns $automationRuns;

    public readonly Resources\Campaigns $campaigns;

    public readonly Resources\Channels $channels;

    public readonly Resources\ConnectSessions $connectSessions;

    public readonly Resources\ContactFields $contactFields;

    public readonly Resources\Contacts $contacts;

    public readonly Resources\Conversations $conversations;

    public readonly Resources\Devices $devices;

    public readonly Resources\Exports $exports;

    public readonly Resources\Groups $groups;

    public readonly Resources\Imports $imports;

    public readonly Resources\Inboxes $inboxes;

    public readonly Resources\Media $media;

    public readonly Resources\Messages $messages;

    public readonly Resources\PairingCodes $pairingCodes;

    public readonly Resources\SmartLists $smartLists;

    public readonly Resources\Stats $stats;

    public readonly Resources\Suppressions $suppressions;

    public readonly Resources\ConsentEvents $consentEvents;

    public readonly Resources\Templates $templates;

    public readonly Resources\WebhookDeliveries $webhookDeliveries;

    public readonly Resources\WebhookEndpoints $webhookEndpoints;

    public readonly Resources\Partner $partner;

    private function bootResources(Connection $connection): void
    {
        $this->account = new Resources\Account($connection);
        $this->apiKeys = new Resources\ApiKeys($connection);
        $this->automationRules = new Resources\AutomationRules($connection);
        $this->automationRuns = new Resources\AutomationRuns($connection);
        $this->campaigns = new Resources\Campaigns($connection);
        $this->channels = new Resources\Channels($connection);
        $this->connectSessions = new Resources\ConnectSessions($connection);
        $this->contactFields = new Resources\ContactFields($connection);
        $this->contacts = new Resources\Contacts($connection);
        $this->conversations = new Resources\Conversations($connection);
        $this->devices = new Resources\Devices($connection);
        $this->exports = new Resources\Exports($connection);
        $this->groups = new Resources\Groups($connection);
        $this->imports = new Resources\Imports($connection);
        $this->inboxes = new Resources\Inboxes($connection);
        $this->media = new Resources\Media($connection);
        $this->messages = new Resources\Messages($connection);
        $this->pairingCodes = new Resources\PairingCodes($connection);
        $this->smartLists = new Resources\SmartLists($connection);
        $this->stats = new Resources\Stats($connection);
        $this->suppressions = new Resources\Suppressions($connection);
        $this->consentEvents = new Resources\ConsentEvents($connection);
        $this->templates = new Resources\Templates($connection);
        $this->webhookDeliveries = new Resources\WebhookDeliveries($connection);
        $this->webhookEndpoints = new Resources\WebhookEndpoints($connection);
        $this->partner = new Resources\Partner($connection);
    }
}

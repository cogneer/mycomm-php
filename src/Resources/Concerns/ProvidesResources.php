<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Concerns;

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

    public readonly Resources\Channels $channels;

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

    public readonly Resources\WebhookDeliveries $webhookDeliveries;

    public readonly Resources\WebhookEndpoints $webhookEndpoints;

    public readonly Resources\Partner $partner;

    private function bootResources(Connection $connection): void
    {
        $this->account = new Resources\Account($connection);
        $this->apiKeys = new Resources\ApiKeys($connection);
        $this->channels = new Resources\Channels($connection);
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
        $this->webhookDeliveries = new Resources\WebhookDeliveries($connection);
        $this->webhookEndpoints = new Resources\WebhookEndpoints($connection);
        $this->partner = new Resources\Partner($connection);
    }
}

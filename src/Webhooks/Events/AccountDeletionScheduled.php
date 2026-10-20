<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

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
use MyComm\Webhooks\Event;

/**
 * `account.deletion_scheduled`: The account is `pending_deletion`; `data.account.purge_at` is when it is
 * purged.
 */
final class AccountDeletionScheduled extends Event
{
    public const TYPE = 'account.deletion_scheduled';

    public readonly Models\WebhookAccountData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookAccountData::fromArray($data['data']);
    }
}

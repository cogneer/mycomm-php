<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

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
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class WebhookAccountData extends Model
{
    public readonly WebhookAccount $account;

    public readonly WebhookActor $actor;

    /**
     * The suspension reason, or `null`.
     */
    public readonly ?string $reason;

    protected function hydrate(array $data): void
    {
        $this->account = WebhookAccount::fromArray($data['account']);
        $this->actor = WebhookActor::fromArray($data['actor']);
        $this->reason = $data['reason'] ?? null;
    }
}

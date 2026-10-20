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

final class WebhookMessageCancelledData extends Model
{
    public readonly Message $message;

    public readonly WebhookConversationRef $conversation;

    /**
     * `api`, `portal`, `campaign_cancelled`, `opted_out`, `contact_blocked`, `account_deleted` (may gain values).
     */
    public readonly ?string $reason;

    protected function hydrate(array $data): void
    {
        $this->message = Message::fromArray($data['message']);
        $this->conversation = WebhookConversationRef::fromArray($data['conversation']);
        $this->reason = $data['reason'] ?? null;
    }
}

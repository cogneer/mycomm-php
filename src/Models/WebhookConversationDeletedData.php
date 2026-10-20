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

final class WebhookConversationDeletedData extends Model
{
    public readonly string $conversation_id;

    public readonly string $inbox_id;

    public readonly string $contact_id;

    public readonly WebhookActor $actor;

    protected function hydrate(array $data): void
    {
        $this->conversation_id = $data['conversation_id'];
        $this->inbox_id = $data['inbox_id'];
        $this->contact_id = $data['contact_id'];
        $this->actor = WebhookActor::fromArray($data['actor']);
    }
}

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

/**
 * `created` is true for the message that created the conversation.
 */
final class WebhookConversationRef extends Model
{
    public readonly ?string $id;

    public readonly bool $created;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->created = $data['created'];
    }
}

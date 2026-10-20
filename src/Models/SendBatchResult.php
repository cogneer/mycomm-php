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

final class SendBatchResult extends Model
{
    /**
     * Position of the item in `messages`.
     */
    public readonly int $index;

    public readonly Enums\SendBatchResultStatus|string $status;

    /**
     * The queued message (a preview in a dry run); null when rejected.
     *
     * @var Message|SendBatchPreview|array<string, mixed>|null
     */
    public readonly Message|SendBatchPreview|array|null $message;

    /**
     * @var list<SendMessageWarning>|null
     */
    public readonly ?array $warnings;

    public readonly ?SendBatchItemError $error;

    protected function hydrate(array $data): void
    {
        $this->index = $data['index'];
        $this->status = self::enum(Enums\SendBatchResultStatus::class, $data['status']);
        $this->message = isset($data['message']) ? self::oneOf($data['message'], [Message::class => ['id', 'account_id', 'channel_id', 'inbox_id', 'direction', 'type', 'origin', 'content_type', 'content', 'media', 'channel_meta', 'status', 'attempts', 'metadata', 'actor', 'created_at'], SendBatchPreview::class => []]) : null;
        $this->warnings = isset($data['warnings']) ? array_map(static fn (array $item): SendMessageWarning => SendMessageWarning::fromArray($item), array_values($data['warnings'])) : null;
        $this->error = isset($data['error']) ? SendBatchItemError::fromArray($data['error']) : null;
    }
}

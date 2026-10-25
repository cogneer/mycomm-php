<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * The would-be message of a dry run. `id`, `status`, `conversation_id` and the timestamps are `null`.
 */
final class SendMessageDryRunResponse extends Model
{
    public readonly bool $dry_run;

    public readonly null $id;

    public readonly null $status;

    public readonly null $conversation_id;

    /**
     * `null` when the number is new.
     */
    public readonly ?string $contact_id;

    public readonly ?string $encoding;

    public readonly ?int $segments;

    public readonly Enums\MessageType|string|null $type;

    public readonly Enums\HoldReason|string|null $hold_reason;

    public readonly ?DateTimeImmutable $expires_at;

    /**
     * @var list<SendMessageWarning>
     */
    public readonly array $warnings;

    protected function hydrate(array $data): void
    {
        $this->dry_run = $data['dry_run'];
        $this->id = null;
        $this->status = null;
        $this->conversation_id = null;
        $this->contact_id = $data['contact_id'] ?? null;
        $this->encoding = $data['encoding'] ?? null;
        $this->segments = $data['segments'] ?? null;
        $this->type = isset($data['type']) ? self::enum(Enums\MessageType::class, $data['type']) : null;
        $this->hold_reason = isset($data['hold_reason']) ? self::enum(Enums\HoldReason::class, $data['hold_reason']) : null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->warnings = array_map(static fn (array $item): SendMessageWarning => SendMessageWarning::fromArray($item), array_values($data['warnings']));
    }
}

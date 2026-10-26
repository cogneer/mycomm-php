<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * The would-be message of a dry-run item. `id` and `status` are null; nothing is stored.
 */
final class SendBatchPreview extends Model
{
    public readonly null $id;

    public readonly null $status;

    public readonly ?string $to;

    public readonly ?string $body;

    public readonly ?string $encoding;

    public readonly ?int $segments;

    public readonly Enums\MessageType|string|null $type;

    public readonly Enums\MessageCategory|string|null $category;

    public readonly Enums\MessageLane|string|null $lane;

    public readonly Enums\HoldReason|string|null $hold_reason;

    public readonly ?string $reference_id;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $metadata;

    protected function hydrate(array $data): void
    {
        $this->id = null;
        $this->status = null;
        $this->to = $data['to'] ?? null;
        $this->body = $data['body'] ?? null;
        $this->encoding = $data['encoding'] ?? null;
        $this->segments = $data['segments'] ?? null;
        $this->type = isset($data['type']) ? self::enum(Enums\MessageType::class, $data['type']) : null;
        $this->category = isset($data['category']) ? self::enum(Enums\MessageCategory::class, $data['category']) : null;
        $this->lane = isset($data['lane']) ? self::enum(Enums\MessageLane::class, $data['lane']) : null;
        $this->hold_reason = isset($data['hold_reason']) ? self::enum(Enums\HoldReason::class, $data['hold_reason']) : null;
        $this->reference_id = $data['reference_id'] ?? null;
        $this->metadata = $data['metadata'] ?? null;
    }
}

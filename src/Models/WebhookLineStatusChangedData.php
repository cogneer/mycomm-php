<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * Payload of line.connected, line.needs_attention and line.recovered: the partner line's status after the
 * change.
 */
final class WebhookLineStatusChangedData extends Model
{
    public readonly string $line_id;

    /**
     * The partner's own reference for the line.
     */
    public readonly ?string $external_ref;

    public readonly Enums\LineState|string $state;

    /**
     * @var list<Enums\LineNeedsAttentionReason|string>
     */
    public readonly array $needs_attention_reasons;

    public readonly Enums\LineState|string $previous_state;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->external_ref = $data['external_ref'] ?? null;
        $this->state = self::enum(Enums\LineState::class, $data['state']);
        $this->needs_attention_reasons = array_map(static fn (string $item): Enums\LineNeedsAttentionReason|string => self::enum(Enums\LineNeedsAttentionReason::class, $item), array_values($data['needs_attention_reasons']));
        $this->previous_state = self::enum(Enums\LineState::class, $data['previous_state']);
    }
}

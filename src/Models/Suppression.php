<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * A suppressed identity. It keeps the plain E.164 address, also after the contact is erased. `dnc` is an
 * account-uploaded do-not-contact list; `invalid` is two recipient-class failures in 30 days.
 */
final class Suppression extends Model
{
    public readonly string $id;

    /**
     * `phone` in v1.
     */
    public readonly string $identity_type;

    public readonly string $address;

    public readonly Enums\SuppressionReason|string $reason;

    /**
     * The opt-out word of a `keyword` suppression.
     */
    public readonly ?string $keyword;

    /**
     * The inbound message that carried the opt-out keyword.
     */
    public readonly ?string $source_message_id;

    public readonly ?string $note;

    /**
     * Who added the suppression.
     */
    public readonly SuppressionActor $actor;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->identity_type = $data['identity_type'];
        $this->address = $data['address'];
        $this->reason = self::enum(Enums\SuppressionReason::class, $data['reason']);
        $this->keyword = $data['keyword'] ?? null;
        $this->source_message_id = $data['source_message_id'] ?? null;
        $this->note = $data['note'] ?? null;
        $this->actor = SuppressionActor::fromArray($data['actor']);
        $this->created_at = self::dateTime($data['created_at']);
    }
}

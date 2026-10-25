<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * One recipient of a campaign; its status mirrors its message.
 */
final class CampaignRecipient extends Model
{
    public readonly string $id;

    public readonly ?string $contact_id;

    public readonly ?string $contact_identity_id;

    public readonly string $address;

    public readonly Enums\CampaignRecipientStatus|string $status;

    public readonly Enums\CampaignRecipientSkipReason|string|null $skip_reason;

    public readonly ?string $message_id;

    /**
     * The recipient's snapshot variables.
     *
     * @var array<string, mixed>
     */
    public readonly array $variables;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->contact_id = $data['contact_id'] ?? null;
        $this->contact_identity_id = $data['contact_identity_id'] ?? null;
        $this->address = $data['address'];
        $this->status = self::enum(Enums\CampaignRecipientStatus::class, $data['status']);
        $this->skip_reason = isset($data['skip_reason']) ? self::enum(Enums\CampaignRecipientSkipReason::class, $data['skip_reason']) : null;
        $this->message_id = $data['message_id'] ?? null;
        $this->variables = $data['variables'];
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}

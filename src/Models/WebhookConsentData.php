<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * Consent payload of contact.opted_out and contact.opted_in. `method` is `keyword`, `manual`, `api`,
 * `import` or `campaign_form`.
 */
final class WebhookConsentData extends Model
{
    public readonly ?string $contact_id;

    public readonly ?string $identity_type;

    public readonly ?string $address;

    public readonly ?string $method;

    public readonly ?string $keyword;

    public readonly ?string $message_id;

    public readonly ?string $suppression_id;

    public readonly WebhookActor $actor;

    public readonly DateTimeImmutable $occurred_at;

    protected function hydrate(array $data): void
    {
        $this->contact_id = $data['contact_id'] ?? null;
        $this->identity_type = $data['identity_type'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->method = $data['method'] ?? null;
        $this->keyword = $data['keyword'] ?? null;
        $this->message_id = $data['message_id'] ?? null;
        $this->suppression_id = $data['suppression_id'] ?? null;
        $this->actor = WebhookActor::fromArray($data['actor']);
        $this->occurred_at = self::dateTime($data['occurred_at']);
    }
}

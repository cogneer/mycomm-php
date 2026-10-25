<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * Click payload of link.clicked.
 */
final class WebhookLinkClickedData extends Model
{
    public readonly string $short_link_id;

    public readonly ?string $slug;

    public readonly ?string $target_url;

    /**
     * The message that carried the link; `null` for a link not made for a message.
     */
    public readonly ?string $message_id;

    /**
     * The campaign of that message, if any.
     */
    public readonly ?string $campaign_id;

    public readonly ?string $contact_id;

    public readonly DateTimeImmutable $clicked_at;

    /**
     * ISO 3166-1 alpha-2 country of the click, when known.
     */
    public readonly ?string $country_iso;

    /**
     * Human clicks on the link so far, this one included.
     */
    public readonly ?int $click_count;

    protected function hydrate(array $data): void
    {
        $this->short_link_id = $data['short_link_id'];
        $this->slug = $data['slug'] ?? null;
        $this->target_url = $data['target_url'] ?? null;
        $this->message_id = $data['message_id'] ?? null;
        $this->campaign_id = $data['campaign_id'] ?? null;
        $this->contact_id = $data['contact_id'] ?? null;
        $this->clicked_at = self::dateTime($data['clicked_at']);
        $this->country_iso = $data['country_iso'] ?? null;
        $this->click_count = $data['click_count'] ?? null;
    }
}

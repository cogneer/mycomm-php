<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `link.clicked`: A person clicked a short link; bot and link-preview clicks emit nothing. `inbox_id` and
 * `channel` are those of the message that carried the link. The data carries no IP address or user agent.
 * `slug`, `target_url`, `contact_id` and `click_count` are `null` when the link no longer exists (its
 * message was deleted or its contact erased).
 */
final class LinkClicked extends Event
{
    public const TYPE = 'link.clicked';

    public readonly Models\WebhookLinkClickedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLinkClickedData::fromArray($data['data']);
    }
}

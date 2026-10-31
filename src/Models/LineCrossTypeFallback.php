<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Resend a message that was not delivered after `after_seconds` through the line's next channel type (for
 * example WhatsApp first, SMS after two minutes). The resend is a new message whose `fallback_of` is the
 * original; a queued original is cancelled with reason `cross_type_fallback`. A message falls back at most
 * once, and only to a contact with a phone number. Only text messages fall back, and only those sent
 * within `after_seconds` + 1 hour (older ones are left as they are). It may double the cost of a message.
 */
final class LineCrossTypeFallback extends Model
{
    /**
     * Off by default.
     */
    public readonly bool $enabled;

    /**
     * How long the first type has to deliver (default 120).
     */
    public readonly int $after_seconds;

    protected function hydrate(array $data): void
    {
        $this->enabled = $data['enabled'];
        $this->after_seconds = $data['after_seconds'];
    }
}

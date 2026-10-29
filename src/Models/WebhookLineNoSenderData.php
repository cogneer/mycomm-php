<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Payload of line.no_sender.
 */
final class WebhookLineNoSenderData extends Model
{
    public readonly string $line_id;

    /**
     * Messages of the line waiting for a sender when the event was written.
     */
    public readonly int $held_count;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->held_count = $data['held_count'];
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Payload of line.failover.
 */
final class WebhookLineFailoverData extends Model
{
    public readonly string $line_id;

    public readonly string $from_channel_id;

    public readonly string $to_channel_id;

    /**
     * `sender_removed` (the sender left the line or was disconnected), `sender_unhealthy` (it stayed unhealthy past the lane limit), `no_sender_available` (the messages waited for any sender) or `send_failed:<failure_code>` (…
     */
    public readonly string $reason;

    public readonly int $message_count;

    /**
     * @var list<string>
     */
    public readonly array $message_ids;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->from_channel_id = $data['from_channel_id'];
        $this->to_channel_id = $data['to_channel_id'];
        $this->reason = $data['reason'];
        $this->message_count = $data['message_count'];
        $this->message_ids = $data['message_ids'];
    }
}

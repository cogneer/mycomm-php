<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Sender list payload of line.sender_changed.
 */
final class WebhookLineSenderChangedData extends Model
{
    public readonly string $line_id;

    /**
     * @var list<WebhookLineSender>
     */
    public readonly array $senders;

    /**
     * How many conversations lost their sticky sender with this change because their sender left the list or was disconnected; their next message goes through the line's normal order.
     */
    public readonly int $sticky_cleared_count;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->senders = array_map(static fn (array $item): WebhookLineSender => WebhookLineSender::fromArray($item), array_values($data['senders']));
        $this->sticky_cleared_count = $data['sticky_cleared_count'];
    }
}

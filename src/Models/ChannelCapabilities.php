<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * The resolved capabilities of the channel, the driver's defaults overlaid with what the channel learned.
 */
final class ChannelCapabilities extends Model
{
    /**
     * `null` for segmented channels (SMS).
     */
    public readonly ?int $text_max_chars;

    public readonly bool $segmented;

    public readonly ?int $max_segments;

    /**
     * Media kind (`image`) to its limits; `{}` when the channel cannot send media.
     *
     * @var array<string, ChannelMediaLimits>
     */
    public readonly array $media;

    public readonly int $max_media_items;

    public readonly bool $delivery_reports;

    public readonly bool $read_receipts;

    public readonly bool $typing;

    public readonly bool $reactions;

    public readonly bool $edit_delete;

    public readonly bool $buttons;

    public readonly bool $location;

    public readonly bool $business_initiated;

    public readonly ?int $session_window_seconds;

    public readonly bool $groups;

    protected function hydrate(array $data): void
    {
        $this->text_max_chars = $data['text_max_chars'] ?? null;
        $this->segmented = $data['segmented'];
        $this->max_segments = $data['max_segments'] ?? null;
        $this->media = array_map(static fn (array $item): ChannelMediaLimits => ChannelMediaLimits::fromArray($item), $data['media']);
        $this->max_media_items = $data['max_media_items'];
        $this->delivery_reports = $data['delivery_reports'];
        $this->read_receipts = $data['read_receipts'];
        $this->typing = $data['typing'];
        $this->reactions = $data['reactions'];
        $this->edit_delete = $data['edit_delete'];
        $this->buttons = $data['buttons'];
        $this->location = $data['location'];
        $this->business_initiated = $data['business_initiated'];
        $this->session_window_seconds = $data['session_window_seconds'] ?? null;
        $this->groups = $data['groups'];
    }
}

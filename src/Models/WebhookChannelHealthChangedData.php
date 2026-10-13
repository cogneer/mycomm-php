<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class WebhookChannelHealthChangedData extends Model
{
    public readonly string $channel_id;

    public readonly ChannelHealth $health;

    public readonly Enums\ChannelHealthBand|string|null $previous_band;

    protected function hydrate(array $data): void
    {
        $this->channel_id = $data['channel_id'];
        $this->health = ChannelHealth::fromArray($data['health']);
        $this->previous_band = isset($data['previous_band']) ? self::enum(Enums\ChannelHealthBand::class, $data['previous_band']) : null;
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class DeviceSim extends Model
{
    public readonly string $id;

    /**
     * `null` for an eSIM without a physical slot.
     */
    public readonly ?int $slot;

    public readonly int $subscription_id;

    public readonly ?string $carrier_name;

    public readonly ?string $country_iso;

    public readonly ?string $reported_number;

    public readonly bool $present;

    public readonly Enums\DeviceSimServiceState|string|null $service_state;

    /**
     * 0 to 4.
     */
    public readonly ?int $signal_level;

    public readonly ?string $channel_id;

    public readonly ?string $channel_status;

    public readonly ?DateTimeImmutable $first_seen_at;

    public readonly ?DateTimeImmutable $last_seen_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->slot = $data['slot'] ?? null;
        $this->subscription_id = $data['subscription_id'];
        $this->carrier_name = $data['carrier_name'] ?? null;
        $this->country_iso = $data['country_iso'] ?? null;
        $this->reported_number = $data['reported_number'] ?? null;
        $this->present = $data['present'];
        $this->service_state = isset($data['service_state']) ? self::enum(Enums\DeviceSimServiceState::class, $data['service_state']) : null;
        $this->signal_level = $data['signal_level'] ?? null;
        $this->channel_id = $data['channel_id'] ?? null;
        $this->channel_status = $data['channel_status'] ?? null;
        $this->first_seen_at = isset($data['first_seen_at']) ? self::dateTime($data['first_seen_at']) : null;
        $this->last_seen_at = isset($data['last_seen_at']) ? self::dateTime($data['last_seen_at']) : null;
    }
}

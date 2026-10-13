<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookDevicePairedData extends Model
{
    public readonly Device $device;

    public readonly string $pairing_code_id;

    protected function hydrate(array $data): void
    {
        $this->device = Device::fromArray($data['device']);
        $this->pairing_code_id = $data['pairing_code_id'];
    }
}

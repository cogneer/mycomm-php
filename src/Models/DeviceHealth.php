<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

/**
 * Subset of the last heartbeat. Blocks the phone has not reported yet are empty objects.
 */
final class DeviceHealth extends Model
{
    public readonly DeviceHealthPower $power;

    public readonly DeviceHealthNetwork $network;

    /**
     * Permission name (`send_sms`, `receive_sms`, `read_sms`, `receive_mms`, `read_phone_state`, `read_phone_numbers`, `post_notifications`) to granted.
     *
     * @var array<string, bool>
     */
    public readonly array $permissions;

    public readonly ?bool $battery_optimization_ignored;

    public readonly ?DateTimeImmutable $rcs_confirmed_off_at;

    public readonly DeviceHealthStorage $storage;

    protected function hydrate(array $data): void
    {
        $this->power = DeviceHealthPower::fromArray($data['power']);
        $this->network = DeviceHealthNetwork::fromArray($data['network']);
        $this->permissions = $data['permissions'];
        $this->battery_optimization_ignored = $data['battery_optimization_ignored'] ?? null;
        $this->rcs_confirmed_off_at = isset($data['rcs_confirmed_off_at']) ? self::dateTime($data['rcs_confirmed_off_at']) : null;
        $this->storage = DeviceHealthStorage::fromArray($data['storage']);
    }
}

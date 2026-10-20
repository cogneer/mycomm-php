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

final class Device extends Model
{
    public readonly string $id;

    public readonly ?string $name;

    public readonly Enums\DeviceStatus|string $status;

    public readonly ?string $manufacturer;

    public readonly ?string $model;

    public readonly ?string $os_version;

    public readonly ?string $app_version_name;

    public readonly ?int $app_version_code;

    public readonly bool $update_available;

    public readonly Enums\DeviceConnectionState|string $connection_state;

    /**
     * Derived from the last heartbeat, for example `permission_missing:SEND_SMS`, `battery_low`, `no_sim`.
     *
     * @var list<string>
     */
    public readonly array $connection_reasons;

    public readonly bool $paused;

    public readonly Enums\DevicePauseReason|string|null $pause_reason;

    public readonly bool $os_limit_raised;

    public readonly ?int $os_limit_value;

    public readonly ?DateTimeImmutable $os_limit_changed_at;

    public readonly DeviceHealth $health;

    /**
     * @var list<DeviceSim>
     */
    public readonly array $sims;

    public readonly ?DateTimeImmutable $paired_at;

    public readonly ?DateTimeImmutable $last_heartbeat_at;

    public readonly ?DateTimeImmutable $last_pull_at;

    public readonly ?DateTimeImmutable $last_report_at;

    public readonly ?DateTimeImmutable $revoked_at;

    public readonly Enums\DeviceRevokedReason|string|null $revoked_reason;

    public readonly ?DateTimeImmutable $drain_until;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'] ?? null;
        $this->status = self::enum(Enums\DeviceStatus::class, $data['status']);
        $this->manufacturer = $data['manufacturer'] ?? null;
        $this->model = $data['model'] ?? null;
        $this->os_version = $data['os_version'] ?? null;
        $this->app_version_name = $data['app_version_name'] ?? null;
        $this->app_version_code = $data['app_version_code'] ?? null;
        $this->update_available = $data['update_available'];
        $this->connection_state = self::enum(Enums\DeviceConnectionState::class, $data['connection_state']);
        $this->connection_reasons = $data['connection_reasons'];
        $this->paused = $data['paused'];
        $this->pause_reason = isset($data['pause_reason']) ? self::enum(Enums\DevicePauseReason::class, $data['pause_reason']) : null;
        $this->os_limit_raised = $data['os_limit_raised'];
        $this->os_limit_value = $data['os_limit_value'] ?? null;
        $this->os_limit_changed_at = isset($data['os_limit_changed_at']) ? self::dateTime($data['os_limit_changed_at']) : null;
        $this->health = DeviceHealth::fromArray($data['health']);
        $this->sims = array_map(static fn (array $item): DeviceSim => DeviceSim::fromArray($item), array_values($data['sims']));
        $this->paired_at = isset($data['paired_at']) ? self::dateTime($data['paired_at']) : null;
        $this->last_heartbeat_at = isset($data['last_heartbeat_at']) ? self::dateTime($data['last_heartbeat_at']) : null;
        $this->last_pull_at = isset($data['last_pull_at']) ? self::dateTime($data['last_pull_at']) : null;
        $this->last_report_at = isset($data['last_report_at']) ? self::dateTime($data['last_report_at']) : null;
        $this->revoked_at = isset($data['revoked_at']) ? self::dateTime($data['revoked_at']) : null;
        $this->revoked_reason = isset($data['revoked_reason']) ? self::enum(Enums\DeviceRevokedReason::class, $data['revoked_reason']) : null;
        $this->drain_until = isset($data['drain_until']) ? self::dateTime($data['drain_until']) : null;
    }
}

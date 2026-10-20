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
 * A managed account.
 */
final class ManagedAccount extends Model
{
    public readonly string $id;

    public readonly string $type;

    public readonly string $external_ref;

    public readonly string $name;

    public readonly Enums\AccountStatus|string $status;

    public readonly ?string $suspended_reason;

    public readonly string $country_iso;

    public readonly string $timezone;

    public readonly string $default_locale;

    public readonly ManagedAccountSettings $settings;

    public readonly ManagedAccountCounts $counts;

    public readonly ?DateTimeImmutable $deletion_requested_at;

    public readonly ?DateTimeImmutable $purge_at;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->external_ref = $data['external_ref'];
        $this->name = $data['name'];
        $this->status = self::enum(Enums\AccountStatus::class, $data['status']);
        $this->suspended_reason = $data['suspended_reason'] ?? null;
        $this->country_iso = $data['country_iso'];
        $this->timezone = $data['timezone'];
        $this->default_locale = $data['default_locale'];
        $this->settings = ManagedAccountSettings::fromArray($data['settings']);
        $this->counts = ManagedAccountCounts::fromArray($data['counts']);
        $this->deletion_requested_at = isset($data['deletion_requested_at']) ? self::dateTime($data['deletion_requested_at']) : null;
        $this->purge_at = isset($data['purge_at']) ? self::dateTime($data['purge_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class Account extends Model
{
    public readonly string $id;

    /**
     * `direct`, `managed` or `partner`.
     */
    public readonly string $type;

    public readonly string $name;

    /**
     * `active`, `suspended` or `pending_deletion`.
     */
    public readonly string $status;

    public readonly string $country_iso;

    public readonly string $timezone;

    /**
     * BCP 47, default `en-GB`.
     */
    public readonly string $default_locale;

    /**
     * `null` for direct accounts.
     */
    public readonly ?string $external_ref;

    public readonly AccountSettings $settings;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->country_iso = $data['country_iso'];
        $this->timezone = $data['timezone'];
        $this->default_locale = $data['default_locale'];
        $this->external_ref = $data['external_ref'] ?? null;
        $this->settings = AccountSettings::fromArray($data['settings']);
        $this->created_at = self::dateTime($data['created_at']);
    }
}

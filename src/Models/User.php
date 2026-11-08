<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class User extends Model
{
    public readonly string $id;

    public readonly string $name;

    /**
     * `active`, `suspended` or `pending_deletion`.
     */
    public readonly string $status;

    /**
     * ISO 3166-1 alpha-2, or `null` while the user has not set a country.
     */
    public readonly ?string $country_iso;

    /**
     * IANA time zone, or `null` while the user has not set one (MyComm then uses UTC).
     */
    public readonly ?string $timezone;

    /**
     * BCP 47, default `en-GB`.
     */
    public readonly string $default_locale;

    public readonly UserSettings $settings;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->country_iso = $data['country_iso'] ?? null;
        $this->timezone = $data['timezone'] ?? null;
        $this->default_locale = $data['default_locale'];
        $this->settings = UserSettings::fromArray($data['settings']);
        $this->created_at = self::dateTime($data['created_at']);
    }
}

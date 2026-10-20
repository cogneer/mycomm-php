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
 * The API-visible subset of the account settings: `opt_out`, `compliance`, `quiet_hours`, `frequency_cap`,
 * `links`, `default_expiry_minutes`, `sms`, `channels`, `whatsapp`, `retention`, `automation`, `alerts`,
 * `limits` and `support`. Unknown keys must be ignored.
 */
final class AccountSettings extends Model
{
    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $opt_out;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $compliance;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $quiet_hours;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $frequency_cap;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $links;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $default_expiry_minutes;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $sms;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $channels;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $whatsapp;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $retention;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $automation;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $alerts;

    /**
     * Read-only for account keys; only the partner may set it.
     *
     * @var array<string, mixed>|null
     */
    public readonly ?array $limits;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $support;

    protected function hydrate(array $data): void
    {
        $this->opt_out = $data['opt_out'] ?? null;
        $this->compliance = $data['compliance'] ?? null;
        $this->quiet_hours = $data['quiet_hours'] ?? null;
        $this->frequency_cap = $data['frequency_cap'] ?? null;
        $this->links = $data['links'] ?? null;
        $this->default_expiry_minutes = $data['default_expiry_minutes'] ?? null;
        $this->sms = $data['sms'] ?? null;
        $this->channels = $data['channels'] ?? null;
        $this->whatsapp = $data['whatsapp'] ?? null;
        $this->retention = $data['retention'] ?? null;
        $this->automation = $data['automation'] ?? null;
        $this->alerts = $data['alerts'] ?? null;
        $this->limits = $data['limits'] ?? null;
        $this->support = $data['support'] ?? null;
    }
}

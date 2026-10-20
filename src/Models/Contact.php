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

final class Contact extends Model
{
    public readonly string $id;

    public readonly ?string $first_name;

    public readonly ?string $last_name;

    public readonly ?string $display_name;

    public readonly ?string $email;

    public readonly ?string $timezone;

    public readonly ?string $language;

    /**
     * @var array<string, mixed>
     */
    public readonly array $custom_fields;

    /**
     * @var list<ContactIdentity>
     */
    public readonly array $identities;

    /**
     * @var list<string>
     */
    public readonly array $group_ids;

    public readonly Enums\ContactSource|string $source;

    public readonly ?ContactActor $actor;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->first_name = $data['first_name'] ?? null;
        $this->last_name = $data['last_name'] ?? null;
        $this->display_name = $data['display_name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->timezone = $data['timezone'] ?? null;
        $this->language = $data['language'] ?? null;
        $this->custom_fields = $data['custom_fields'];
        $this->identities = array_map(static fn (array $item): ContactIdentity => ContactIdentity::fromArray($item), array_values($data['identities']));
        $this->group_ids = $data['group_ids'];
        $this->source = self::enum(Enums\ContactSource::class, $data['source']);
        $this->actor = isset($data['actor']) ? ContactActor::fromArray($data['actor']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}

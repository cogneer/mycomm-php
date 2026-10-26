<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

final class ContactUpsert extends Input
{
    /**
     * @param  array{type: string, address: string}  $identity
     * @param  string|Omit|null  $timezone  IANA time zone.
     * @param  string|Omit|null  $language  BCP 47 tag.
     * @param  array<string, mixed>|Omit|null  $custom_fields  Merged key by key; a `null` value removes the key.
     * @param  list<string>|null  $add_to_group_ids  Adds these memberships; existing ones are kept.
     */
    public function __construct(
        public readonly array $identity,
        public readonly string|Omit|null $first_name = Omit::Value,
        public readonly string|Omit|null $last_name = Omit::Value,
        public readonly string|Omit|null $email = Omit::Value,
        public readonly string|Omit|null $timezone = Omit::Value,
        public readonly string|Omit|null $language = Omit::Value,
        public readonly array|Omit|null $custom_fields = Omit::Value,
        public readonly ?array $add_to_group_ids = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'identity' => Params::object($this->identity),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'custom_fields' => Params::object($this->custom_fields),
            'add_to_group_ids' => $this->add_to_group_ids ?? Omit::Value,
        ]);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

/**
 * What you pay for on a line. Every field is optional. `channel_fee_cover` `{type: n}` pays the monthly
 * fee of the first n numbers of that type on the line's sender list. `message_allowance` `{type: n}` pays
 * n messages (SMS: segments) of that type per cycle; a type it does not name is the user's own. At the
 * limit, `hold` (the default) makes further messages of that type wait with `hold_reason`
 * `sponsor_quota_exhausted` until the next cycle, and `allow_user_overage` lets the user's own bought
 * messages take over. You get `line.quota_low` at 80% and `line.quota_exhausted` at 100%, once per cycle
 * and type. A cycle is `cycle_days` long from when the sponsorship started.
 */
final class SponsorshipTerms extends Input
{
    /**
     * @param  array<string, mixed>|null  $channel_fee_cover
     * @param  array<string, mixed>|null  $message_allowance  At least 1 message per named type; leave a type out to sponsor none of it.
     */
    public function __construct(
        public readonly ?int $cycle_days = null,
        public readonly ?array $channel_fee_cover = null,
        public readonly ?array $message_allowance = null,
        public readonly Enums\SponsorshipOnExhausted|string|null $on_exhausted = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'cycle_days' => $this->cycle_days ?? Omit::Value,
            'channel_fee_cover' => Params::object($this->channel_fee_cover ?? Omit::Value),
            'message_allowance' => Params::object($this->message_allowance ?? Omit::Value),
            'on_exhausted' => $this->on_exhausted ?? Omit::Value,
        ]);
    }
}

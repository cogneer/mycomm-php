<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * What you pay for on a line. Every field is optional. `channel_fee_cover` `{type: n}` pays the monthly
 * fee of the first n numbers of that type on the line's sender list. `message_allowance` `{type: n}` pays
 * n messages (SMS: segments) of that type per cycle; a type it does not name is the user's own. At the
 * limit, `hold` (the default) makes further messages of that type wait with `hold_reason`
 * `sponsor_quota_exhausted` until the next cycle, and `allow_user_overage` lets the user's own bought
 * messages take over. You get `line.quota_low` at 80% and `line.quota_exhausted` at 100%, once per cycle
 * and type. A cycle is `cycle_days` long from when the sponsorship started.
 */
final class SponsorshipTerms extends Model
{
    public readonly ?int $cycle_days;

    /**
     * @var array<string, int>|null
     */
    public readonly ?array $channel_fee_cover;

    /**
     * At least 1 message per named type; leave a type out to sponsor none of it.
     *
     * @var array<string, int>|null
     */
    public readonly ?array $message_allowance;

    public readonly Enums\SponsorshipOnExhausted|string|null $on_exhausted;

    protected function hydrate(array $data): void
    {
        $this->cycle_days = $data['cycle_days'] ?? null;
        $this->channel_fee_cover = $data['channel_fee_cover'] ?? null;
        $this->message_allowance = $data['message_allowance'] ?? null;
        $this->on_exhausted = isset($data['on_exhausted']) ? self::enum(Enums\SponsorshipOnExhausted::class, $data['on_exhausted']) : null;
    }
}

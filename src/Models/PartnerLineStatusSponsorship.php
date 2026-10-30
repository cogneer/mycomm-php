<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * The messages you pay for on the line this cycle: `allowance` and `used` add up the channel types of
 * `message_allowance`, `by_type` has them per type. `used` may pass `allowance` when the user's own
 * messages took over (`allow_user_overage`).
 */
final class PartnerLineStatusSponsorship extends Model
{
    public readonly int $allowance;

    public readonly int $used;

    public readonly ?DateTimeImmutable $cycle_ends_at;

    public readonly Enums\SponsorshipOnExhausted|string $on_exhausted;

    /**
     * @var array<string, PartnerLineStatusSponsorshipByType>
     */
    public readonly array $by_type;

    protected function hydrate(array $data): void
    {
        $this->allowance = $data['allowance'];
        $this->used = $data['used'];
        $this->cycle_ends_at = isset($data['cycle_ends_at']) ? self::dateTime($data['cycle_ends_at']) : null;
        $this->on_exhausted = self::enum(Enums\SponsorshipOnExhausted::class, $data['on_exhausted']);
        $this->by_type = array_map(static fn (array $item): PartnerLineStatusSponsorshipByType => PartnerLineStatusSponsorshipByType::fromArray($item), $data['by_type']);
    }
}

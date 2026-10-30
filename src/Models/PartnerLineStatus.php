<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class PartnerLineStatus extends Model
{
    public readonly string $line_id;

    public readonly Enums\LineState|string $state;

    /**
     * Empty unless `needs_attention`. `device_offline`: every sender's phone is offline. `sim_missing`: a sender's SIM left its phone. `no_sender`: the line has no sender, or its messages wait for one. `quota_exhausted`: the…
     *
     * @var list<Enums\LineNeedsAttentionReason|string>
     */
    public readonly array $needs_attention_reasons;

    /**
     * The line's enabled senders by channel type, for every type the line may use. A detached line answers `not_connected` with `{}` here, no sponsorship and no times.
     *
     * @var array<string, PartnerLineStatusChannel>
     */
    public readonly array $channels;

    public readonly ?PartnerLineStatusSponsorship $sponsorship;

    public readonly ?DateTimeImmutable $last_sent_at;

    public readonly ?DateTimeImmutable $last_received_at;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->state = self::enum(Enums\LineState::class, $data['state']);
        $this->needs_attention_reasons = array_map(static fn (string $item): Enums\LineNeedsAttentionReason|string => self::enum(Enums\LineNeedsAttentionReason::class, $item), array_values($data['needs_attention_reasons']));
        $this->channels = array_map(static fn (array $item): PartnerLineStatusChannel => PartnerLineStatusChannel::fromArray($item), $data['channels']);
        $this->sponsorship = isset($data['sponsorship']) ? PartnerLineStatusSponsorship::fromArray($data['sponsorship']) : null;
        $this->last_sent_at = isset($data['last_sent_at']) ? self::dateTime($data['last_sent_at']) : null;
        $this->last_received_at = isset($data['last_received_at']) ? self::dateTime($data['last_received_at']) : null;
    }
}

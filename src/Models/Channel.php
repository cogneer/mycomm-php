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
 * A number (an `android_sim` channel in v1) with its full state.
 */
final class Channel extends Model
{
    public readonly string $id;

    public readonly Enums\ChannelType|string $type;

    public readonly string $name;

    /**
     * The number in E.164 form. Empty while a channel connected without a number waits for the inbound probe to name it.
     */
    public readonly string $address;

    /**
     * The number formatted for display, for example `+92 300 1234567`.
     */
    public readonly ?string $display_address;

    public readonly Enums\ChannelStatus|string $status;

    /**
     * Set while `paused`.
     */
    public readonly Enums\ChannelPauseReason|string|null $pause_reason;

    /**
     * When the current pause began.
     */
    public readonly ?DateTimeImmutable $paused_at;

    /**
     * Set when `disconnected`.
     */
    public readonly Enums\ChannelDisconnectReason|string|null $disconnect_reason;

    public readonly ?string $device_id;

    public readonly ?string $device_sim_id;

    /**
     * The channel's main inbox; `null` until the number is connected.
     */
    public readonly ?string $main_inbox_id;

    public readonly ChannelCapabilities $capabilities;

    /**
     * ISO 3166-1 alpha-2 codes; `null` means the channel's own country only.
     *
     * @var list<string>|null
     */
    public readonly ?array $allowed_destination_countries;

    public readonly bool $skip_business_senders;

    public readonly ChannelRatePolicy $rate_policy;

    public readonly Enums\ChannelRatePolicySource|string $rate_policy_source;

    /**
     * Risk of the stored policy against the suggestion.
     */
    public readonly Enums\ChannelRatePolicyRisk|string $rate_policy_risk;

    public readonly ChannelSuggestedRatePolicy $suggested_rate_policy;

    public readonly ChannelRatePolicyCeiling $rate_policy_ceiling;

    public readonly ChannelWarmup $warmup;

    public readonly ChannelHealth $health;

    public readonly ChannelQueue $queue;

    public readonly ChannelUsage $usage;

    public readonly int $reply_window_days;

    /**
     * Set when a push provider asked to slow down; always `null` for SMS.
     */
    public readonly ?DateTimeImmutable $throttled_until;

    /**
     * The latest verification of the number, or `null` before one exists.
     */
    public readonly ?ChannelVerification $verification;

    public readonly ?DateTimeImmutable $verified_at;

    public readonly ?DateTimeImmutable $connected_at;

    public readonly ?DateTimeImmutable $disconnected_at;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = self::enum(Enums\ChannelType::class, $data['type']);
        $this->name = $data['name'];
        $this->address = $data['address'];
        $this->display_address = $data['display_address'] ?? null;
        $this->status = self::enum(Enums\ChannelStatus::class, $data['status']);
        $this->pause_reason = isset($data['pause_reason']) ? self::enum(Enums\ChannelPauseReason::class, $data['pause_reason']) : null;
        $this->paused_at = isset($data['paused_at']) ? self::dateTime($data['paused_at']) : null;
        $this->disconnect_reason = isset($data['disconnect_reason']) ? self::enum(Enums\ChannelDisconnectReason::class, $data['disconnect_reason']) : null;
        $this->device_id = $data['device_id'] ?? null;
        $this->device_sim_id = $data['device_sim_id'] ?? null;
        $this->main_inbox_id = $data['main_inbox_id'] ?? null;
        $this->capabilities = ChannelCapabilities::fromArray($data['capabilities']);
        $this->allowed_destination_countries = $data['allowed_destination_countries'] ?? null;
        $this->skip_business_senders = $data['skip_business_senders'];
        $this->rate_policy = ChannelRatePolicy::fromArray($data['rate_policy']);
        $this->rate_policy_source = self::enum(Enums\ChannelRatePolicySource::class, $data['rate_policy_source']);
        $this->rate_policy_risk = self::enum(Enums\ChannelRatePolicyRisk::class, $data['rate_policy_risk']);
        $this->suggested_rate_policy = ChannelSuggestedRatePolicy::fromArray($data['suggested_rate_policy']);
        $this->rate_policy_ceiling = ChannelRatePolicyCeiling::fromArray($data['rate_policy_ceiling']);
        $this->warmup = ChannelWarmup::fromArray($data['warmup']);
        $this->health = ChannelHealth::fromArray($data['health']);
        $this->queue = ChannelQueue::fromArray($data['queue']);
        $this->usage = ChannelUsage::fromArray($data['usage']);
        $this->reply_window_days = $data['reply_window_days'];
        $this->throttled_until = isset($data['throttled_until']) ? self::dateTime($data['throttled_until']) : null;
        $this->verification = isset($data['verification']) ? ChannelVerification::fromArray($data['verification']) : null;
        $this->verified_at = isset($data['verified_at']) ? self::dateTime($data['verified_at']) : null;
        $this->connected_at = isset($data['connected_at']) ? self::dateTime($data['connected_at']) : null;
        $this->disconnected_at = isset($data['disconnected_at']) ? self::dateTime($data['disconnected_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * The campaign resource. `channel_id` is read-only, derived from `inbox_id`.
 */
final class Campaign extends Model
{
    public readonly string $id;

    public readonly string $name;

    public readonly string $inbox_id;

    public readonly ?string $channel_id;

    public readonly Enums\CampaignStatus|string $status;

    public readonly ?string $body;

    public readonly ?string $template_id;

    public readonly ?string $media_id;

    /**
     * @var array<string, string|int|float|bool>
     */
    public readonly array $variables;

    public readonly CampaignAudience $audience;

    public readonly CampaignExclude $exclude;

    public readonly Enums\CampaignCategory|string $category;

    public readonly CampaignCompliance $compliance;

    public readonly bool $append_opt_out_text;

    public readonly bool $shorten_links;

    public readonly bool $track_clicks;

    public readonly ?DateTimeImmutable $scheduled_at;

    public readonly ?DateTimeImmutable $started_at;

    public readonly ?DateTimeImmutable $paused_at;

    public readonly ?DateTimeImmutable $completed_at;

    public readonly ?DateTimeImmutable $cancelled_at;

    public readonly ?DateTimeImmutable $eta_at;

    /**
     * `campaigns.stats`; `skipped` counts rows by skip reason.
     */
    public readonly CampaignStats $stats;

    public readonly CampaignActor $actor;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->inbox_id = $data['inbox_id'];
        $this->channel_id = $data['channel_id'] ?? null;
        $this->status = self::enum(Enums\CampaignStatus::class, $data['status']);
        $this->body = $data['body'] ?? null;
        $this->template_id = $data['template_id'] ?? null;
        $this->media_id = $data['media_id'] ?? null;
        $this->variables = $data['variables'];
        $this->audience = CampaignAudience::fromArray($data['audience']);
        $this->exclude = CampaignExclude::fromArray($data['exclude']);
        $this->category = self::enum(Enums\CampaignCategory::class, $data['category']);
        $this->compliance = CampaignCompliance::fromArray($data['compliance']);
        $this->append_opt_out_text = $data['append_opt_out_text'];
        $this->shorten_links = $data['shorten_links'];
        $this->track_clicks = $data['track_clicks'];
        $this->scheduled_at = isset($data['scheduled_at']) ? self::dateTime($data['scheduled_at']) : null;
        $this->started_at = isset($data['started_at']) ? self::dateTime($data['started_at']) : null;
        $this->paused_at = isset($data['paused_at']) ? self::dateTime($data['paused_at']) : null;
        $this->completed_at = isset($data['completed_at']) ? self::dateTime($data['completed_at']) : null;
        $this->cancelled_at = isset($data['cancelled_at']) ? self::dateTime($data['cancelled_at']) : null;
        $this->eta_at = isset($data['eta_at']) ? self::dateTime($data['eta_at']) : null;
        $this->stats = CampaignStats::fromArray($data['stats']);
        $this->actor = CampaignActor::fromArray($data['actor']);
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * A reusable message body. `channel_type`, `language`, `provider_name`, `provider_status` and `components`
 * are read-only seams for provider-approved templates; they are `null` on every v1 template.
 */
final class TemplateSaved extends Model
{
    public readonly string $id;

    public readonly string $name;

    public readonly string $body;

    public readonly Enums\TemplateCategory|string $category;

    public readonly ?string $media_id;

    /**
     * The variable names of the body, in order of first use.
     *
     * @var list<string>
     */
    public readonly array $variables;

    public readonly ?string $channel_type;

    public readonly ?string $language;

    public readonly ?string $provider_name;

    public readonly ?string $provider_status;

    /**
     * @var list<mixed>|null
     */
    public readonly ?array $components;

    public readonly ?DateTimeImmutable $archived_at;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    /**
     * Non-blocking notices, such as `UNKNOWN_VARIABLE` (with `variable`) or `MARKETING_MULTIPLE_LINKS`.
     *
     * @var list<TemplateSavedWarning>
     */
    public readonly array $warnings;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->body = $data['body'];
        $this->category = self::enum(Enums\TemplateCategory::class, $data['category']);
        $this->media_id = $data['media_id'] ?? null;
        $this->variables = $data['variables'];
        $this->channel_type = $data['channel_type'] ?? null;
        $this->language = $data['language'] ?? null;
        $this->provider_name = $data['provider_name'] ?? null;
        $this->provider_status = $data['provider_status'] ?? null;
        $this->components = $data['components'] ?? null;
        $this->archived_at = isset($data['archived_at']) ? self::dateTime($data['archived_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
        $this->warnings = array_map(static fn (array $item): TemplateSavedWarning => TemplateSavedWarning::fromArray($item), array_values($data['warnings']));
    }
}

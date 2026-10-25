<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * What a keyword rule matches; an empty object for the away message.
 */
final class AutomationRuleMatch extends Model
{
    public readonly Enums\AutomationRuleMatchMode|string|null $mode;

    /**
     * @var list<string>|null
     */
    public readonly ?array $keywords;

    public readonly ?bool $case_sensitive;

    protected function hydrate(array $data): void
    {
        $this->mode = isset($data['mode']) ? self::enum(Enums\AutomationRuleMatchMode::class, $data['mode']) : null;
        $this->keywords = $data['keywords'] ?? null;
        $this->case_sensitive = $data['case_sensitive'] ?? null;
    }
}

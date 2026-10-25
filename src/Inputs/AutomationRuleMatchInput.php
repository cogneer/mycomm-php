<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

/**
 * Required for a keyword rule; ignored for the away message. 1 to 20 keywords of at most 30 characters.
 * Opt-out and opt-in keywords are reserved.
 */
final class AutomationRuleMatchInput extends Input
{
    /**
     * @param  list<string>  $keywords
     */
    public function __construct(
        public readonly Enums\AutomationRuleMatchInputMode|string $mode,
        public readonly array $keywords,
        public readonly ?bool $case_sensitive = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'mode' => $this->mode,
            'keywords' => $this->keywords,
            'case_sensitive' => $this->case_sensitive ?? Omit::Value,
        ]);
    }
}

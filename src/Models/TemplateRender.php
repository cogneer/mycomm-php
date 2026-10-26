<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class TemplateRender extends Model
{
    public readonly string $body;

    public readonly Enums\TemplateRenderEncoding|string $encoding;

    public readonly int $segments;

    public readonly int $characters;

    /**
     * @var list<string>
     */
    public readonly array $missing_variables;

    /**
     * @var list<string>
     */
    public readonly array $fallbacks_used;

    /**
     * @var list<string>
     */
    public readonly array $non_gsm_characters;

    protected function hydrate(array $data): void
    {
        $this->body = $data['body'];
        $this->encoding = self::enum(Enums\TemplateRenderEncoding::class, $data['encoding']);
        $this->segments = $data['segments'];
        $this->characters = $data['characters'];
        $this->missing_variables = $data['missing_variables'];
        $this->fallbacks_used = $data['fallbacks_used'];
        $this->non_gsm_characters = $data['non_gsm_characters'];
    }
}

<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * All rules of an inbox ordered by position. Not paginated.
 */
final class AutomationRuleList extends Model
{
    /**
     * @var list<AutomationRule>
     */
    public readonly array $data;

    protected function hydrate(array $data): void
    {
        $this->data = array_map(static fn (array $item): AutomationRule => AutomationRule::fromArray($item), array_values($data['data']));
    }
}

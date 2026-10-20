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
 * The filter DSL of 02 section 9: `match` (`all` or `any`) over `conditions`, each a condition `{field,
 * op, value}` (`value` absent for operators that take none) or a group `{match, conditions}` of plain
 * conditions (one level of nesting). At most 20 conditions and 5 groups. Fields are `first_name`,
 * `last_name`, `display_name`, `email`, `custom.<key>`, `group`, `identity.country`, `identity.type`,
 * `source`, `created_at`, `last_inbound_at`, `last_outbound_at`, `opted_out` and `campaign`, with the
 * operators and values of 02 section 9.2. Group and campaign ids must belong to the account.
 */
final class SmartListFilter extends Model
{
    public readonly Enums\SmartListFilterMatch|string $match;

    /**
     * @var list<array<string, mixed>>
     */
    public readonly array $conditions;

    protected function hydrate(array $data): void
    {
        $this->match = self::enum(Enums\SmartListFilterMatch::class, $data['match']);
        $this->conditions = $data['conditions'];
    }
}

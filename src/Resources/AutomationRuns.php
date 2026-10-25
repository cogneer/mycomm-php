<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeInterface;
use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class AutomationRuns
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List automation runs.
     *
     * Requires `automation:read`. The automation log: one run per evaluation that chose a rule, newest first
     * (`sort=-created_at`, ties by `id`), with cursor pagination. `inbox_id` is the rule's inbox. `inbox_id`,
     * `rule_id` and `conversation_id` take one value each; `outcome` takes a comma list. `created_after` is
     * inclusive and `created_before` exclusive. A key restricted to inboxes sees only the runs of its own
     * inboxes; filtering on another inbox returns an empty page. A filter value that is not valid is `422
     * VALIDATION_FAILED`; a cursor from other filters is `400 INVALID_CURSOR`.
     *
     * @param  ?string  $inbox_id  Runs of rules of this inbox.
     * @param  ?string  $rule_id  Runs of this rule.
     * @param  ?string  $conversation_id  Runs triggered in this conversation.
     * @param  list<string>|string|null  $outcome  One outcome or a comma list, e.g. `skipped:cooldown,skipped:channel_cap`. Values: `applied`, `skipped:cooldown`, `skipped:channel_cap`, `skipped:loop_guard`.
     * @param  DateTimeInterface|string|null  $created_after  Runs at or after this instant.
     * @param  DateTimeInterface|string|null  $created_before  Runs before this instant.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\AutomationRun>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $inbox_id = null,
        ?string $rule_id = null,
        ?string $conversation_id = null,
        array|string|null $outcome = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'automationRuns.list',
            [
                'inbox_id' => $inbox_id,
                'rule_id' => $rule_id,
                'conversation_id' => $conversation_id,
                'outcome' => $outcome,
                'limit' => $limit,
            ],
            [
                'inbox_id' => [
                    'uuid' => true,
                ],
                'rule_id' => [
                    'uuid' => true,
                ],
                'conversation_id' => [
                    'uuid' => true,
                ],
                'outcome' => [
                    'maxItems' => 4,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'automationRuns.list',
            'GET',
            '/v1/automation-runs',
            query: Params::query([
                'inbox_id' => $inbox_id,
                'rule_id' => $rule_id,
                'conversation_id' => $conversation_id,
                'outcome' => $outcome,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\AutomationRun => Models\AutomationRun::fromArray($item), fn (string $next): Page => $this->list(inbox_id: $inbox_id, rule_id: $rule_id, conversation_id: $conversation_id, outcome: $outcome, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $inbox_id  Runs of rules of this inbox.
     * @param  ?string  $rule_id  Runs of this rule.
     * @param  ?string  $conversation_id  Runs triggered in this conversation.
     * @param  list<string>|string|null  $outcome  One outcome or a comma list, e.g. `skipped:cooldown,skipped:channel_cap`. Values: `applied`, `skipped:cooldown`, `skipped:channel_cap`, `skipped:loop_guard`.
     * @param  DateTimeInterface|string|null  $created_after  Runs at or after this instant.
     * @param  DateTimeInterface|string|null  $created_before  Runs before this instant.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\AutomationRun>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $inbox_id = null,
        ?string $rule_id = null,
        ?string $conversation_id = null,
        array|string|null $outcome = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(inbox_id: $inbox_id, rule_id: $rule_id, conversation_id: $conversation_id, outcome: $outcome, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }
}

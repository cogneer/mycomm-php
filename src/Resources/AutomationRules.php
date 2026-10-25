<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeInterface;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Inputs;
use MyComm\Models;
use MyComm\Omit;
use MyComm\RequestOptions;

final class AutomationRules
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List the automation rules of an inbox.
     *
     * Requires `automation:read`. All rules of the inbox ordered by `position`, not paginated: the away
     * message first (position 0), then the keyword rules from 1; rules are evaluated by ascending position and
     * the first matching keyword rule wins. A key restricted to inboxes reads only its own inboxes; another
     * inbox, or one of another account, is `404`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\AutomationRuleList {
        $response = $this->connection->request('automationRules.list', 'GET', '/v1/inboxes/'.rawurlencode($id).'/automation-rules', options: $requestOptions);

        return Models\AutomationRuleList::fromArray($response->json(), $response->meta());
    }

    /**
     * Create an automation rule.
     *
     * Requires `automation:write`, which a key restricted to inboxes never holds (`403 ABILITY_MISSING`). A
     * keyword rule goes to the end of the inbox's keyword rules unless `position` (1 to the number of keyword
     * rules plus one) says otherwise; an `away` rule has no `match`, sits at position 0 and fires outside the
     * inbox's business hours. An inbox holds at most 200 keyword rules and one away rule: a second one is `409
     * INVALID_STATE`. `422 TEMPLATE_ARCHIVED` and `LINK_PUBLIC_SHORTENER` (`details.url`) come from the
     * `reply` actions; `422 VALIDATION_FAILED` covers every field, an unknown group or template (also one of
     * another account), a reserved opt-out or opt-in keyword, and any field that is not listed here. Strings,
     * lists and objects have their size limits checked first, so oversized values are `422`, never an error.
     * The body is JSON, at most 128 KB (`413 PAYLOAD_TOO_LARGE`, `details.max_bytes`); anything else is `400
     * MALFORMED_REQUEST`.
     *
     * @param  list<Inputs\AutomationReplyAction|array<string, mixed>|Inputs\AutomationGroupAction|Inputs\AutomationTagAction|Inputs\AutomationFlagAction>  $actions
     * @param  Inputs\AutomationRuleMatchInput|array<string, mixed>|null  $match
     * @param  ?int  $position  Keyword rules only; from 1 to the number of keyword rules plus one. Default last.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $id,
        Enums\AutomationRuleCreateType|string $type,
        string $name,
        array $actions,
        ?bool $active = null,
        Inputs\AutomationRuleMatchInput|array|null $match = null,
        ?int $cooldown_minutes = null,
        ?int $position = null,
        ?RequestOptions $requestOptions = null,
    ): Models\AutomationRule {
        $this->connection->guard(
            'automationRules.create',
            [
                'type' => $type,
                'name' => $name,
                'actions' => $actions,
                'cooldown_minutes' => $cooldown_minutes,
                'position' => $position,
            ],
            [
                'type' => [
                    'enum' => ['keyword', 'away'],
                ],
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 60,
                ],
                'actions' => [
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'cooldown_minutes' => [
                    'min' => 0,
                    'max' => 10080,
                ],
                'position' => [
                    'min' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'automationRules.create',
            'POST',
            '/v1/inboxes/'.rawurlencode($id).'/automation-rules',
            body: Params::body([
                'type' => $type,
                'name' => $name,
                'active' => $active ?? Omit::Value,
                'match' => Params::object($match ?? Omit::Value),
                'actions' => $actions,
                'cooldown_minutes' => $cooldown_minutes ?? Omit::Value,
                'position' => $position ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\AutomationRule::fromArray($response->json(), $response->meta());
    }

    /**
     * Reorder the keyword rules of an inbox.
     *
     * Requires `automation:write`. `rule_ids` names every keyword rule of the inbox exactly once, in the new
     * order (the away rule is not part of it); positions become 1 to n. A missing, repeated, unknown or
     * foreign id is `422 VALIDATION_FAILED` and nothing changes. Answers with all rules of the inbox ordered
     * by position.
     *
     * @param  list<string>  $rule_ids  Every keyword rule of the inbox exactly once, in the new order.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function reorder(
        string $id,
        array $rule_ids,
        ?RequestOptions $requestOptions = null,
    ): Models\AutomationRuleList {
        $this->connection->guard(
            'automationRules.reorder',
            [
                'rule_ids' => $rule_ids,
            ],
            [
                'rule_ids' => [
                    'maxItems' => 200,
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'automationRules.reorder',
            'POST',
            '/v1/inboxes/'.rawurlencode($id).'/automation-rules/reorder',
            body: Params::body([
                'rule_ids' => $rule_ids,
            ]),
            options: $requestOptions,
        );

        return Models\AutomationRuleList::fromArray($response->json(), $response->meta());
    }

    /**
     * Get an automation rule.
     *
     * Requires `automation:read`. `404` for a rule of another inbox or account and for an inbox outside the
     * key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        string $rule_id,
        ?RequestOptions $requestOptions = null,
    ): Models\AutomationRule {
        $response = $this->connection->request('automationRules.get', 'GET', '/v1/inboxes/'.rawurlencode($id).'/automation-rules/'.rawurlencode($rule_id), options: $requestOptions);

        return Models\AutomationRule::fromArray($response->json(), $response->meta());
    }

    /**
     * Update an automation rule.
     *
     * Requires `automation:write`. Only the fields sent change; any field except `type` and `inbox_id`, which
     * are `422 VALIDATION_FAILED` when sent, even with their current value. A `position` moves a keyword rule
     * among the keyword rules of its inbox. The errors of the create call apply to the fields sent.
     *
     * @param  Inputs\AutomationRuleMatchInput|array<string, mixed>|null  $match
     * @param  list<Inputs\AutomationReplyAction|array<string, mixed>|Inputs\AutomationGroupAction|Inputs\AutomationTagAction|Inputs\AutomationFlagAction>|null  $actions
     * @param  ?int  $position  Moves a keyword rule to this place among the keyword rules of its inbox.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        string $rule_id,
        ?string $name = null,
        ?bool $active = null,
        Inputs\AutomationRuleMatchInput|array|null $match = null,
        ?array $actions = null,
        ?int $cooldown_minutes = null,
        ?int $position = null,
        ?RequestOptions $requestOptions = null,
    ): Models\AutomationRule {
        $this->connection->guard(
            'automationRules.update',
            [
                'name' => $name,
                'actions' => $actions,
                'cooldown_minutes' => $cooldown_minutes,
                'position' => $position,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 60,
                ],
                'actions' => [
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'cooldown_minutes' => [
                    'min' => 0,
                    'max' => 10080,
                ],
                'position' => [
                    'min' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'automationRules.update',
            'PATCH',
            '/v1/inboxes/'.rawurlencode($id).'/automation-rules/'.rawurlencode($rule_id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'active' => $active ?? Omit::Value,
                'match' => Params::object($match ?? Omit::Value),
                'actions' => $actions ?? Omit::Value,
                'cooldown_minutes' => $cooldown_minutes ?? Omit::Value,
                'position' => $position ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\AutomationRule::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete an automation rule.
     *
     * Requires `automation:write`. The remaining keyword rules keep their order at positions 1 to n. Past runs
     * of the rule are kept.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        string $rule_id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('automationRules.delete', 'DELETE', '/v1/inboxes/'.rawurlencode($id).'/automation-rules/'.rawurlencode($rule_id), options: $requestOptions);
    }

    /**
     * Test a message against the automation of an inbox.
     *
     * Requires `automation:read`. A dry run: the inbox's rules and guards decide what an inbound message with
     * this `body` would do, and nothing is sent, written or logged. `at` (default now) is the instant used for
     * business hours and the guard windows. With `contact_id` the per-conversation guards (loop guard,
     * cooldown) read that contact's conversation in the inbox; without it only the channel cap applies. A
     * guard outcome (`skipped:*`) still names the rule and lists its actions and reply preview, and `outcome`
     * says why nothing would be sent. `no_match` means no rule fits or the message runs no automation at all
     * (an opt-out keyword, for example): no rule, no actions and no preview. A key restricted to inboxes tests
     * only its own inboxes; another inbox, or one of another account, is `404`. The reply preview renders the
     * contact's fields, so a `contact_id` also needs `contacts:read` (`403 ABILITY_MISSING`, checked before
     * the contact is looked up). A `contact_id` that is not a contact of the account is `422
     * VALIDATION_FAILED`, as is any field not listed here. The body is JSON, at most 128 KB (`413
     * PAYLOAD_TOO_LARGE`); anything else is `400 MALFORMED_REQUEST`.
     *
     * @param  string  $body  The text of the inbound message to test.
     * @param  string|Omit|null  $contact_id  The sender, a contact of the account; needs `contacts:read`. Omitted or `null` tests an unknown sender.
     * @param  DateTimeInterface|string|Omit|null  $at  The instant the message would arrive, RFC 3339 with `Z` or an offset. Omitted or `null` is now.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function test(
        string $id,
        string $body,
        string|Omit|null $contact_id = Omit::Value,
        DateTimeInterface|string|Omit|null $at = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\AutomationDryRun {
        $this->connection->guard(
            'automationRules.test',
            [
                'body' => $body,
                'contact_id' => $contact_id,
            ],
            [
                'body' => [
                    'minLength' => 1,
                    'maxLength' => 1600,
                ],
                'contact_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'automationRules.test',
            'POST',
            '/v1/inboxes/'.rawurlencode($id).'/automation-rules/test',
            body: Params::body([
                'body' => $body,
                'contact_id' => $contact_id,
                'at' => $at,
            ]),
            options: $requestOptions,
        );

        return Models\AutomationDryRun::fromArray($response->json(), $response->meta());
    }
}

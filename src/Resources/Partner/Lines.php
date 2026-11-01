<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Partner;

use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Inputs;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Lines
{
    public readonly Lines\Links $links;

    public function __construct(private readonly Connection $connection)
    {
        $this->links = new Lines\Links($connection);
    }

    /**
     * Get a line's status.
     *
     * Requires `lines:read`. How the line is doing now, computed at the time of the call. `not_connected`
     * until the user's first number is verified on the line; then `connected`, or `needs_attention` with the
     * reasons. You are told of every change by webhook: `line.connected`, `line.needs_attention` and
     * `line.recovered` reach your endpoints that have `partner_lines`, and so does `line.alarm`, once per
     * episode of a condition that needs attention (a failover, no sender, a blocked pool member, a sender near
     * its hourly limit, many messages between the user's own numbers, a low sponsored allowance, unconfirmed
     * messages). `channels` counts the line's enabled senders per channel type (`healthy`: can send now).
     * `sponsorship` is null when the line has none. A line of another partner is `404 NOT_FOUND`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function status(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\PartnerLineStatus {
        $response = $this->connection->request('partner.lines.status', 'GET', '/partner/v1/lines/'.rawurlencode($id).'/status', options: $requestOptions);

        return Models\PartnerLineStatus::fromArray($response->json(), $response->meta());
    }

    /**
     * List your lines.
     *
     * Requires `lines:read`. Newest first. Archived (detached) lines are left out unless
     * `include_archived=true`, so an `external_ref` filter may then return a detached line beside the live
     * one.
     *
     * @param  ?string  $external_ref  Exact match.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\PartnerLine>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $external_ref = null,
        ?bool $include_archived = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'partner.lines.list',
            [
                'limit' => $limit,
            ],
            [
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'partner.lines.list',
            'GET',
            '/partner/v1/lines',
            query: Params::query([
                'external_ref' => $external_ref,
                'include_archived' => $include_archived,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\PartnerLine => Models\PartnerLine::fromArray($item), fn (string $next): Page => $this->list(external_ref: $external_ref, include_archived: $include_archived, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $external_ref  Exact match.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\PartnerLine>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $external_ref = null,
        ?bool $include_archived = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(external_ref: $external_ref, include_archived: $include_archived, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create a line.
     *
     * Requires `lines:write`. Makes a line for one of your users in a new, empty MyComm account that becomes
     * the user's account when they connect a phone through a hosted link. `user_number` is only a hint for
     * that page: it never joins the line to an account that already has the number (that needs a code sent to
     * the number), and the answer is the same whether or not the number has an account. `external_ref` is
     * unique among your live lines: a taken one answers `409 EXTERNAL_REF_TAKEN` with the line in
     * `details.line_id`. Detaching a line frees its `external_ref`. A `name` the account's Direct line has
     * ("Direct") answers `409 NAME_TAKEN`.
     *
     * @param  string|Omit|null  $user_number  The user's number in E.164, if you know it; only a hint for the connect page.
     * @param  list<string>|Omit|null  $allowed_channel_types
     * @param  Inputs\SponsorshipTerms|array<string, mixed>|Omit|null  $sponsorship  What you pay for on the line. Left out: your default terms (`PUT /sponsorship-defaults`), if any. `null`: none.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $external_ref,
        string $name,
        string|Omit|null $user_number = Omit::Value,
        array|Omit|null $allowed_channel_types = Omit::Value,
        Inputs\SponsorshipTerms|array|Omit|null $sponsorship = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\PartnerLine {
        $this->connection->guard(
            'partner.lines.create',
            [
                'external_ref' => $external_ref,
                'name' => $name,
                'user_number' => $user_number,
                'allowed_channel_types' => $allowed_channel_types,
            ],
            [
                'external_ref' => [
                    'minLength' => 1,
                    'maxLength' => 191,
                ],
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 120,
                ],
                'user_number' => [
                    'pattern' => '^\\+[1-9]\\d{6,14}$',
                ],
                'allowed_channel_types' => [
                    'minItems' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'partner.lines.create',
            'POST',
            '/partner/v1/lines',
            body: Params::body([
                'external_ref' => $external_ref,
                'name' => $name,
                'user_number' => $user_number,
                'allowed_channel_types' => $allowed_channel_types,
                'sponsorship' => Params::object($sponsorship),
            ]),
            options: $requestOptions,
        );

        return Models\PartnerLine::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a line.
     *
     * Requires `lines:read`. Archived lines are returned too. A line of another partner is `404`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\PartnerLine {
        $response = $this->connection->request('partner.lines.get', 'GET', '/partner/v1/lines/'.rawurlencode($id), options: $requestOptions);

        return Models\PartnerLine::fromArray($response->json(), $response->meta());
    }

    /**
     * Change a line.
     *
     * Requires `lines:write`. Changes `name` and `allowed_channel_types`; a field left out or `null` stays as
     * it is. `external_ref` never changes (`422`). A name another line of the account has answers `409
     * NAME_TAKEN`.
     *
     * @param  list<string>|Omit|null  $allowed_channel_types
     * @param  Inputs\SponsorshipTerms|array<string, mixed>|Omit|null  $sponsorship  New terms, which apply at once within the current cycle (what it used so far stays). `null` ends the sponsorship: the numbers it paid for get 14 days of grace. Left out: unchanged.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        array|Omit|null $allowed_channel_types = Omit::Value,
        Inputs\SponsorshipTerms|array|Omit|null $sponsorship = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\PartnerLine {
        $this->connection->guard(
            'partner.lines.update',
            [
                'name' => $name,
                'allowed_channel_types' => $allowed_channel_types,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 120,
                ],
                'allowed_channel_types' => [
                    'minItems' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'partner.lines.update',
            'PATCH',
            '/partner/v1/lines/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'allowed_channel_types' => $allowed_channel_types,
                'sponsorship' => Params::object($sponsorship),
            ]),
            options: $requestOptions,
        );

        return Models\PartnerLine::fromArray($response->json(), $response->meta());
    }

    /**
     * Detach a line.
     *
     * Requires `lines:write`. Archives the line: its open conversations move to the account's Direct line, and
     * the user keeps the account, its channels, phones and history. `line.detached` is emitted once; detaching
     * an archived line again changes nothing. The line header stops working for it (`403
     * LINE_CONTEXT_INVALID`).
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function detach(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('partner.lines.detach', 'DELETE', '/partner/v1/lines/'.rawurlencode($id), options: $requestOptions);
    }

    /**
     * Vouch for a recovery request.
     *
     * Requires `lines:write`. Someone asked to recover the account of your line (`line.recovery_requested`),
     * and you know this person. MyComm staff see your answer when they decide. It is never required and never
     * enough alone: nothing changes before the 7-day wait is over and staff approve. A repeat changes nothing
     * and answers `200` with the first `vouched_at`. A line that is not your live line in that request's
     * account, or no such request, is `404 NOT_FOUND`; a request already decided is `409 INVALID_STATE`.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function vouch(
        string $id,
        string $recovery_request_id,
        ?RequestOptions $requestOptions = null,
    ): Models\RecoveryVouch {
        $this->connection->guard(
            'partner.lines.vouch',
            [
                'recovery_request_id' => $recovery_request_id,
            ],
            [
                'recovery_request_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'partner.lines.vouch',
            'POST',
            '/partner/v1/lines/'.rawurlencode($id).'/vouch',
            body: Params::body([
                'recovery_request_id' => $recovery_request_id,
            ]),
            options: $requestOptions,
        );

        return Models\RecoveryVouch::fromArray($response->json(), $response->meta());
    }
}

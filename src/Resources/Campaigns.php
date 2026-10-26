<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeInterface;
use Generator;
use MyComm\Enums;
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

final class Campaigns
{
    public readonly Campaigns\Recipients $recipients;

    public function __construct(private readonly Connection $connection)
    {
        $this->recipients = new Campaigns\Recipients($connection);
    }

    /**
     * Campaign report.
     *
     * Requires `campaigns:read`. The aggregate report of a campaign, live while it runs and after it ends.
     * `stats` is `campaigns.stats`. Rates use `stats.sent` as denominator (`delivery_rate` = delivered / sent,
     * `click_rate` = unique clicks / sent) and are `null` while nothing was sent. `failures_by_code` counts
     * the campaign's failed messages by failure code (`UNKNOWN` when none was reported). `timeline` has one
     * entry per UTC hour in which a message was sent, delivered or failed, oldest first. `links` has one entry
     * per target URL with its human clicks and the number of recipients who clicked; bot clicks are excluded.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function report(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\CampaignReport {
        $response = $this->connection->request('campaigns.report', 'GET', '/v1/campaigns/'.rawurlencode($id).'/report', options: $requestOptions);

        return Models\CampaignReport::fromArray($response->json(), $response->meta());
    }

    /**
     * List campaigns.
     *
     * Requires `campaigns:read`. Campaigns of the account, newest first (`sort=-created_at`, the only sort),
     * with cursor pagination. An item carries no `audience.addresses`; read the campaign for them. A key
     * limited to some inboxes never holds `campaigns:read` and answers `403 ABILITY_MISSING`.
     *
     * @param  Enums\CampaignStatus|string|null  $status  Campaign status.
     * @param  ?string  $inbox_id  Campaigns sent from this inbox.
     * @param  ?string  $channel_id  Campaigns whose inbox belongs to this channel.
     * @param  DateTimeInterface|string|null  $created_after  Created at or after this time.
     * @param  DateTimeInterface|string|null  $created_before  Created before this time.
     * @param  ?string  $sort  One of: `created_at`, `-created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Campaign>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        Enums\CampaignStatus|string|null $status = null,
        ?string $inbox_id = null,
        ?string $channel_id = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'campaigns.list',
            [
                'inbox_id' => $inbox_id,
                'channel_id' => $channel_id,
                'limit' => $limit,
            ],
            [
                'inbox_id' => [
                    'uuid' => true,
                ],
                'channel_id' => [
                    'uuid' => true,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'campaigns.list',
            'GET',
            '/v1/campaigns',
            query: Params::query([
                'status' => $status,
                'inbox_id' => $inbox_id,
                'channel_id' => $channel_id,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Campaign => Models\Campaign::fromArray($item), fn (string $next): Page => $this->list(status: $status, inbox_id: $inbox_id, channel_id: $channel_id, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  Enums\CampaignStatus|string|null  $status  Campaign status.
     * @param  ?string  $inbox_id  Campaigns sent from this inbox.
     * @param  ?string  $channel_id  Campaigns whose inbox belongs to this channel.
     * @param  DateTimeInterface|string|null  $created_after  Created at or after this time.
     * @param  DateTimeInterface|string|null  $created_before  Created before this time.
     * @param  ?string  $sort  One of: `created_at`, `-created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Campaign>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        Enums\CampaignStatus|string|null $status = null,
        ?string $inbox_id = null,
        ?string $channel_id = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(status: $status, inbox_id: $inbox_id, channel_id: $channel_id, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create a draft campaign.
     *
     * Requires `campaigns:write`. Creates a `draft`: `body` or `template_id` (a copy), the audience
     * (`group_ids`, `smart_list_ids`, `import_id` of a contacts CSV import, and at most 10,000 `addresses`),
     * defaults for `category` (`marketing`), `append_opt_out_text` (`true` for marketing), `shorten_links` and
     * `track_clicks` (`true`). `compliance.consent_asserted: true` stamps the assertion with the caller.
     * `channel_id` is read-only. The save-time start checks answer `422` with their own code
     * (`CHANNEL_INACTIVE`, `INBOX_ARCHIVED`, `BODY_EMPTY`, `TEMPLATE_INVALID`, `TEMPLATE_ARCHIVED`,
     * `CHANNEL_CAPABILITY_UNSUPPORTED`, `MEDIA_TYPE_UNSUPPORTED`, `AUDIENCE_EMPTY`, `LINK_BLOCKED`,
     * `LINK_PUBLIC_SHORTENER`, `TOO_MANY_LINKS`, `CONTENT_PROHIBITED`, `CATEGORY_MISMATCH`); a malformed body
     * or an over-long list answers `VALIDATION_FAILED`. `audience.import_id` names a `contacts_csv` import
     * uploaded through `/imports`: the campaign gets its own campaign CSV import over the same file, queued at
     * once, whose rows become the campaign's recipients, and `audience.import_id` then holds that import's id.
     * An import of another account, of another type, `failed` or `cancelled`, or a second CSV answers `422
     * VALIDATION_FAILED`; an import already the CSV of another campaign answers `409 INVALID_STATE`.
     *
     * @param  string|Omit|null  $body  The message text; exclusive with `template_id`.
     * @param  string|Omit|null  $template_id  A template whose body and picture are copied into the campaign; exclusive with `body`.
     * @param  array<string, mixed>|null  $variables
     * @param  array{group_ids?: list<string>, smart_list_ids?: list<string>, import_id?: string|null, addresses?: list<Inputs\CampaignAddress|array<string, mixed>>}|null  $audience
     * @param  array{group_ids?: list<string>}|null  $exclude
     * @param  array{consent_asserted?: bool, service_message?: bool}|null  $compliance
     * @param  ?bool  $append_opt_out_text  Default `true` for marketing, `false` for transactional.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $name,
        string $inbox_id,
        string|Omit|null $body = Omit::Value,
        string|Omit|null $template_id = Omit::Value,
        string|Omit|null $media_id = Omit::Value,
        ?array $variables = null,
        ?array $audience = null,
        ?array $exclude = null,
        Enums\CampaignCreateCategory|string|null $category = null,
        ?array $compliance = null,
        ?bool $append_opt_out_text = null,
        ?bool $shorten_links = null,
        ?bool $track_clicks = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $this->connection->guard(
            'campaigns.create',
            [
                'name' => $name,
                'inbox_id' => $inbox_id,
                'body' => $body,
                'template_id' => $template_id,
                'media_id' => $media_id,
                'variables' => $variables,
                'category' => $category,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 200,
                ],
                'inbox_id' => [
                    'uuid' => true,
                ],
                'body' => [
                    'maxLength' => 4000,
                ],
                'template_id' => [
                    'uuid' => true,
                ],
                'media_id' => [
                    'uuid' => true,
                ],
                'variables' => [
                    'maxProperties' => 50,
                    'scalarValues' => true,
                ],
                'category' => [
                    'enum' => ['marketing', 'transactional'],
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'campaigns.create',
            'POST',
            '/v1/campaigns',
            body: Params::body([
                'name' => $name,
                'inbox_id' => $inbox_id,
                'body' => $body,
                'template_id' => $template_id,
                'media_id' => $media_id,
                'variables' => Params::object($variables ?? Omit::Value),
                'audience' => Params::object($audience ?? Omit::Value),
                'exclude' => Params::object($exclude ?? Omit::Value),
                'category' => $category ?? Omit::Value,
                'compliance' => Params::object($compliance ?? Omit::Value),
                'append_opt_out_text' => $append_opt_out_text ?? Omit::Value,
                'shorten_links' => $shorten_links ?? Omit::Value,
                'track_clicks' => $track_clicks ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Retrieve a campaign.
     *
     * Requires `campaigns:read`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $response = $this->connection->request('campaigns.show', 'GET', '/v1/campaigns/'.rawurlencode($id), options: $requestOptions);

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a draft campaign.
     *
     * Requires `campaigns:write`. Any create field, while the campaign is a `draft`; a field that is absent
     * keeps its value. An `audience` given in part changes only the keys it names (a CSV `import_id` stays
     * unless the call sets it; a new one is attached as on create, the same one again changes nothing, and an
     * attached CSV cannot be removed). A `scheduled` campaign must be unscheduled first; any other status than
     * `draft` answers `409 INVALID_STATE` with `details.status`. The save-time checks run again on the merged
     * campaign.
     *
     * @param  string|Omit|null  $body  The message text; exclusive with `template_id`.
     * @param  string|Omit|null  $template_id  A template whose body and picture are copied into the campaign; exclusive with `body`.
     * @param  array<string, mixed>|null  $variables
     * @param  array{group_ids?: list<string>, smart_list_ids?: list<string>, import_id?: string|null, addresses?: list<Inputs\CampaignAddress|array<string, mixed>>}|null  $audience
     * @param  array{group_ids?: list<string>}|null  $exclude
     * @param  array{consent_asserted?: bool, service_message?: bool}|null  $compliance
     * @param  ?bool  $append_opt_out_text  Default `true` for marketing, `false` for transactional.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        ?string $inbox_id = null,
        string|Omit|null $body = Omit::Value,
        string|Omit|null $template_id = Omit::Value,
        string|Omit|null $media_id = Omit::Value,
        ?array $variables = null,
        ?array $audience = null,
        ?array $exclude = null,
        Enums\CampaignUpdateCategory|string|null $category = null,
        ?array $compliance = null,
        ?bool $append_opt_out_text = null,
        ?bool $shorten_links = null,
        ?bool $track_clicks = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $this->connection->guard(
            'campaigns.update',
            [
                'name' => $name,
                'inbox_id' => $inbox_id,
                'body' => $body,
                'template_id' => $template_id,
                'media_id' => $media_id,
                'variables' => $variables,
                'category' => $category,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 200,
                ],
                'inbox_id' => [
                    'uuid' => true,
                ],
                'body' => [
                    'maxLength' => 4000,
                ],
                'template_id' => [
                    'uuid' => true,
                ],
                'media_id' => [
                    'uuid' => true,
                ],
                'variables' => [
                    'maxProperties' => 50,
                    'scalarValues' => true,
                ],
                'category' => [
                    'enum' => ['marketing', 'transactional'],
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'campaigns.update',
            'PATCH',
            '/v1/campaigns/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'inbox_id' => $inbox_id ?? Omit::Value,
                'body' => $body,
                'template_id' => $template_id,
                'media_id' => $media_id,
                'variables' => Params::object($variables ?? Omit::Value),
                'audience' => Params::object($audience ?? Omit::Value),
                'exclude' => Params::object($exclude ?? Omit::Value),
                'category' => $category ?? Omit::Value,
                'compliance' => Params::object($compliance ?? Omit::Value),
                'append_opt_out_text' => $append_opt_out_text ?? Omit::Value,
                'shorten_links' => $shorten_links ?? Omit::Value,
                'track_clicks' => $track_clicks ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a draft campaign.
     *
     * Requires `campaigns:write`. A `draft` only; any other status answers `409 INVALID_STATE`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('campaigns.delete', 'DELETE', '/v1/campaigns/'.rawurlencode($id), options: $requestOptions);
    }

    /**
     * Estimate a campaign.
     *
     * Requires `campaigns:read`. Expands the audience without saving anything and returns the review figures:
     * recipients, skips, segments, start and finish time, warnings and the other review counts. Exact figures
     * come after the start expands the audience. `503 SERVICE_UNAVAILABLE` when a smart list of the audience
     * cannot be counted in time. Rate limited to 10 estimates per minute for each API key and campaign,
     * tighter than the general per-key limit; beyond it the answer is `429 RATE_LIMITED` with `Retry-After`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function estimate(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\CampaignEstimate {
        $response = $this->connection->request('campaigns.estimate', 'POST', '/v1/campaigns/'.rawurlencode($id).'/estimate', options: $requestOptions);

        return Models\CampaignEstimate::fromArray($response->json(), $response->meta());
    }

    /**
     * Start or schedule a campaign.
     *
     * Requires `campaigns:write`. From `draft`: `scheduled` when `scheduled_at` is in the future
     * (`campaign.scheduled`), else `running` (the audience is expanded then, `campaign.started`). From
     * `scheduled`: no `scheduled_at` (or a past one) runs it now, a new future time reschedules it. The start
     * checks run again and answer `422`: `CHANNEL_INACTIVE`, `BODY_EMPTY`, `TEMPLATE_INVALID`,
     * `CHANNEL_CAPABILITY_UNSUPPORTED`, `MEDIA_TYPE_UNSUPPORTED`, `SEND_AFTER_TOO_FAR`, `AUDIENCE_EMPTY`,
     * `LINK_BLOCKED`, `LINK_PUBLIC_SHORTENER`, `TOO_MANY_LINKS`, `CONTENT_PROHIBITED`,
     * `CONSENT_ASSERTION_REQUIRED`, `CONSENT_PROVENANCE_MISSING`, `MARKETING_NOT_ALLOWED`,
     * `CATEGORY_MISMATCH`, `ACCOUNT_THROTTLED`, `NEW_ACCOUNT_LIMIT` or `VALIDATION_FAILED`. Any other status
     * answers `409 INVALID_STATE`.
     *
     * @param  DateTimeInterface|string|Omit|null  $scheduled_at  A time 5 minutes to 30 days ahead schedules (or reschedules) the campaign; absent, past or null starts it now.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function start(
        string $id,
        DateTimeInterface|string|Omit|null $scheduled_at = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $response = $this->connection->request(
            'campaigns.start',
            'POST',
            '/v1/campaigns/'.rawurlencode($id).'/start',
            body: Params::body([
                'scheduled_at' => $scheduled_at,
            ]),
            options: $requestOptions,
        );

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Unschedule a campaign.
     *
     * Requires `campaigns:write`. `scheduled` back to `draft` with `scheduled_at` cleared
     * (`campaign.unscheduled`); edit it, then start it again. `409 INVALID_STATE` from any other status.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unschedule(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $response = $this->connection->request('campaigns.unschedule', 'POST', '/v1/campaigns/'.rawurlencode($id).'/unschedule', options: $requestOptions);

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Pause a campaign.
     *
     * Requires `campaigns:write`. `running` to `paused`: feeding stops and campaign messages still `queued`
     * are held (`hold_reason: campaign_paused`); messages already on the phone complete. `409 INVALID_STATE`
     * from any other status.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function pause(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $response = $this->connection->request('campaigns.pause', 'POST', '/v1/campaigns/'.rawurlencode($id).'/pause', options: $requestOptions);

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Resume a campaign.
     *
     * Requires `campaigns:write`. `paused` to `running`; held messages are released. `409 INVALID_STATE` from
     * any other status. While the account is frozen the campaign stays paused and the call answers `422
     * ACCOUNT_THROTTLED` with `details.risk_state: frozen`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function resume(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $response = $this->connection->request('campaigns.resume', 'POST', '/v1/campaigns/'.rawurlencode($id).'/resume', options: $requestOptions);

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }

    /**
     * Cancel a campaign.
     *
     * Requires `campaigns:write`. `draft`, `scheduled`, `running` or `paused` to `cancelled`. Pending
     * recipients and queued campaign messages become `cancelled` (`message.cancelled`, reason
     * `campaign_cancelled`). `409 INVALID_STATE` from `completed` or `cancelled`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function cancel(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Campaign {
        $response = $this->connection->request('campaigns.cancel', 'POST', '/v1/campaigns/'.rawurlencode($id).'/cancel', options: $requestOptions);

        return Models\Campaign::fromArray($response->json(), $response->meta());
    }
}

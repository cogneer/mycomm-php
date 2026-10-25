<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Campaigns;

use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Recipients
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List the recipients of a campaign.
     *
     * Requires `campaigns:read`. The per-recipient results of a campaign, oldest first (`sort=created_at`),
     * with cursor pagination. Each filter takes one value. A campaign of another account answers `404`. A key
     * limited to some inboxes never holds `campaigns:read` and answers `403 ABILITY_MISSING`.
     *
     * @param  Enums\CampaignRecipientStatus|string|null  $status  Recipient status.
     * @param  Enums\CampaignRecipientSkipReason|string|null  $skip_reason  Why a `skipped` recipient was skipped.
     * @param  ?string  $address  The exact recipient address, e.g. an E.164 number.
     * @param  ?string  $contact_id  Recipients of this contact.
     * @param  ?string  $sort  One of: `created_at`, `-created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\CampaignRecipient>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        string $id,
        Enums\CampaignRecipientStatus|string|null $status = null,
        Enums\CampaignRecipientSkipReason|string|null $skip_reason = null,
        ?string $address = null,
        ?string $contact_id = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'campaigns.recipients.list',
            [
                'address' => $address,
                'contact_id' => $contact_id,
                'limit' => $limit,
            ],
            [
                'address' => [
                    'maxLength' => 64,
                ],
                'contact_id' => [
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
            'campaigns.recipients.list',
            'GET',
            '/v1/campaigns/'.rawurlencode($id).'/recipients',
            query: Params::query([
                'status' => $status,
                'skip_reason' => $skip_reason,
                'address' => $address,
                'contact_id' => $contact_id,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\CampaignRecipient => Models\CampaignRecipient::fromArray($item), fn (string $next): Page => $this->list(id: $id, status: $status, skip_reason: $skip_reason, address: $address, contact_id: $contact_id, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  Enums\CampaignRecipientStatus|string|null  $status  Recipient status.
     * @param  Enums\CampaignRecipientSkipReason|string|null  $skip_reason  Why a `skipped` recipient was skipped.
     * @param  ?string  $address  The exact recipient address, e.g. an E.164 number.
     * @param  ?string  $contact_id  Recipients of this contact.
     * @param  ?string  $sort  One of: `created_at`, `-created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\CampaignRecipient>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        string $id,
        Enums\CampaignRecipientStatus|string|null $status = null,
        Enums\CampaignRecipientSkipReason|string|null $skip_reason = null,
        ?string $address = null,
        ?string $contact_id = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(id: $id, status: $status, skip_reason: $skip_reason, address: $address, contact_id: $contact_id, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }
}

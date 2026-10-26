<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Partner;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Usage
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Usage across managed accounts.
     *
     * Dates are interpreted in each account's own time zone (as stored in `usage_daily`). Paginated over
     * accounts, sorted by `sent_segments` descending. Counters match the usage reports in the portal; rows and
     * totals add `by_channel_type`. Per-day detail for one account: `GET /v1/stats/daily` acting as it.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $from,
        string $to,
        ?string $account_id = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Models\UsageResponse {
        $this->connection->guard(
            'partner.usage.get',
            [
                'account_id' => $account_id,
                'limit' => $limit,
            ],
            [
                'account_id' => [
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
            'partner.usage.get',
            'GET',
            '/partner/v1/usage',
            query: Params::query([
                'from' => $from,
                'to' => $to,
                'account_id' => $account_id,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Models\UsageResponse::fromArray($response->json(), $response->meta());
    }
}

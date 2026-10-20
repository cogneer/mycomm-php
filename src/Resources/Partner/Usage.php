<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Partner;

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
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Usage
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Usage across managed accounts.
     *
     * Dates are interpreted in each account's own time zone (as stored in `usage_daily`). Paginated over
     * accounts, sorted by `sent_segments` descending. Counters are those of 03 section 3.14; rows and totals
     * add `by_channel_type`. Per-day detail for one account: `GET /v1/stats/daily` acting as it.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     *
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

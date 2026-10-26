<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Stats
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Daily counters.
     *
     * Requires `reports:read`. Counters per calendar day in the account time zone, from `usage_daily`. `from`
     * and `to` are inclusive and span at most 366 days (`422 RANGE_TOO_LARGE`, details `{max_days: 366}`).
     * With `group_by=none` every day is present, zeros included, and the response is not paginated. Grouped
     * results contain rows only where there was traffic, ordered by `date` then the group key, and are
     * paginated with `cursor`. A key restricted to inboxes sees its inboxes only and may group by `none` or
     * `inbox`; other values give `VALIDATION_FAILED`.
     *
     * @param  ?string  $channel_type  One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?string  $group_by  Default `none`. One of: `none`, `channel`, `inbox`, `channel_type`.
     * @param  ?int  $limit  Grouped results only. 1 to 1,000 rows, default 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function daily(
        string $from,
        string $to,
        ?string $channel_id = null,
        ?string $inbox_id = null,
        ?string $channel_type = null,
        ?string $group_by = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Models\StatsDailyResponse {
        $this->connection->guard(
            'stats.daily',
            [
                'channel_id' => $channel_id,
                'inbox_id' => $inbox_id,
                'limit' => $limit,
            ],
            [
                'channel_id' => [
                    'uuid' => true,
                ],
                'inbox_id' => [
                    'uuid' => true,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 1000,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'stats.daily',
            'GET',
            '/v1/stats/daily',
            query: Params::query([
                'from' => $from,
                'to' => $to,
                'channel_id' => $channel_id,
                'inbox_id' => $inbox_id,
                'channel_type' => $channel_type,
                'group_by' => $group_by,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Models\StatsDailyResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * Period summary and live state.
     *
     * Requires `reports:read`. One call for a dashboard header. `totals` are sums of `usage_daily` over the
     * inclusive range (calendar days in the account time zone, at most 366 days: `422 RANGE_TOO_LARGE`,
     * details `{max_days: 366}`). `delivery_rate` is delivered over sent on channels with delivery reports;
     * `read_rate` is `null` when the account has no read-capable channel; a rate is `null` when there is
     * nothing to divide by. `failures_by_code` counts outbound messages whose `failed_at` falls in the range.
     * `queue_now`, `channels` and `devices` are current counts. A key restricted to inboxes gets figures for
     * its inboxes, channels and devices only.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function summary(
        string $from,
        string $to,
        ?RequestOptions $requestOptions = null,
    ): Models\StatsSummary {
        $response = $this->connection->request(
            'stats.summary',
            'GET',
            '/v1/stats/summary',
            query: Params::query([
                'from' => $from,
                'to' => $to,
            ]),
            options: $requestOptions,
        );

        return Models\StatsSummary::fromArray($response->json(), $response->meta());
    }
}

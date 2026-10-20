<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

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

final class Exports
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * List exports.
     *
     * Requires the ability of at least one export type; the list holds only the exports of types the key may
     * request, newest first. List rows carry no signed download URL: `url` and `url_expires_at` are `null`
     * here, even for a `completed` export. Fetch the export with `GET /v1/exports/{id}` to get a `url` signed
     * for 15 minutes.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Export>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        Enums\ExportType|string|null $type = null,
        Enums\ExportStatus|string|null $status = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'exports.list',
            'GET',
            '/v1/exports',
            query: Params::query([
                'type' => $type,
                'status' => $status,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Export => Models\Export::fromArray($item), fn (string $next): Page => $this->list(type: $type, status: $status, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Export>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        Enums\ExportType|string|null $type = null,
        Enums\ExportStatus|string|null $status = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(type: $type, status: $status, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Request an export.
     *
     * The required ability depends on `type`: `messages_csv` needs `reports:read`, `contacts_csv` and
     * `contact_json` need `contacts:read`, `campaign_report` needs `campaigns:read`, `account_zip` needs
     * `account:write`. `messages_csv` takes the `GET /v1/messages` filters as `params`; `created_after` and
     * `created_before` are required and at most 366 days apart. A key restricted to inboxes exports only its
     * inboxes. The export is built asynchronously: poll `GET /v1/exports/{id}` or wait for the
     * `export.completed` webhook.
     *
     * Errors: `422 VALIDATION_FAILED` (unknown `type` or invalid `params`), `403 ABILITY_MISSING`.
     *
     * @param  array<string, mixed>|null  $params
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        Enums\ExportType|string $type,
        ?array $params = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Export {
        $response = $this->connection->request(
            'exports.create',
            'POST',
            '/v1/exports',
            body: Params::body([
                'type' => $type,
                'params' => Params::object($params ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\Export::fromArray($response->json(), $response->meta());
    }

    /**
     * Get an export with a fresh signed URL.
     *
     * Requires the ability of the export's type. A `completed` export carries a `url` signed for 15 minutes,
     * refreshed on every call; the URL is a secret. An export of another account answers `404 NOT_FOUND`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Export {
        $response = $this->connection->request('exports.get', 'GET', '/v1/exports/'.rawurlencode($id), options: $requestOptions);

        return Models\Export::fromArray($response->json(), $response->meta());
    }
}

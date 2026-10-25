<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Imports
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List imports.
     *
     * Requires `contacts:read`. Newest first.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Import>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        Enums\ImportType|string|null $type = null,
        Enums\ImportStatus|string|null $status = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'imports.list',
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
            'imports.list',
            'GET',
            '/v1/imports',
            query: Params::query([
                'type' => $type,
                'status' => $status,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Import => Models\Import::fromArray($item), fn (string $next): Page => $this->list(type: $type, status: $status, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Import>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        Enums\ImportType|string|null $type = null,
        Enums\ImportStatus|string|null $status = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(type: $type, status: $status, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Upload an import.
     *
     * The required ability depends on `type`: `contacts_csv` needs `contacts:write`, `suppressions_csv` needs
     * `suppressions:write`. `multipart/form-data` with `type`, `file` (CSV UTF-8 with a header row, at most 20
     * MB and 200,000 rows) and `options`, a JSON string. For `contacts_csv`, `options.mapping` maps a column
     * to `phone`, `first_name`, `last_name`, `full_name` (split at the last space), `email`, `timezone`,
     * `language`, `group` or `custom.<key>`; `existing` is `fill_empty` (default), `overwrite` or `skip`;
     * `consent_assertion` is `{asserted: true, source}`. For `suppressions_csv`, `options.mapping` must map
     * one column to `phone`; `reason` is `import` (default) or `dnc`. The import goes straight from `uploaded`
     * to `queued`; poll `GET /v1/imports/{id}` or wait for the `import.completed` / `import.failed` webhook.
     * The file is private data and is never logged.
     *
     * Errors: `422 VALIDATION_FAILED` (unknown `type`, bad `options`, a CSV that is empty, too large, too long
     * or unreadable), `403 ABILITY_MISSING`.
     *
     * @param  array{days: int}  $options
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $type,
        string $channel_id,
        array $options,
        ?RequestOptions $requestOptions = null,
    ): Models\Import {
        $this->connection->guard(
            'imports.create',
            [
                'type' => $type,
                'channel_id' => $channel_id,
            ],
            [
                'type' => [
                    'enum' => ['phone_history'],
                ],
                'channel_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'imports.create',
            'POST',
            '/v1/imports',
            body: Params::body([
                'type' => $type,
                'channel_id' => $channel_id,
                'options' => Params::object($options),
            ]),
            options: $requestOptions,
        );

        return Models\Import::fromArray($response->json(), $response->meta());
    }

    /**
     * Upload an import.
     *
     * The required ability depends on `type`: `contacts_csv` needs `contacts:write`, `suppressions_csv` needs
     * `suppressions:write`. `multipart/form-data` with `type`, `file` (CSV UTF-8 with a header row, at most 20
     * MB and 200,000 rows) and `options`, a JSON string. For `contacts_csv`, `options.mapping` maps a column
     * to `phone`, `first_name`, `last_name`, `full_name` (split at the last space), `email`, `timezone`,
     * `language`, `group` or `custom.<key>`; `existing` is `fill_empty` (default), `overwrite` or `skip`;
     * `consent_assertion` is `{asserted: true, source}`. For `suppressions_csv`, `options.mapping` must map
     * one column to `phone`; `reason` is `import` (default) or `dnc`. The import goes straight from `uploaded`
     * to `queued`; poll `GET /v1/imports/{id}` or wait for the `import.completed` / `import.failed` webhook.
     * The file is private data and is never logged.
     *
     * Errors: `422 VALIDATION_FAILED` (unknown `type`, bad `options`, a CSV that is empty, too large, too long
     * or unreadable), `403 ABILITY_MISSING`.
     *
     * @param  ?string  $options  JSON object of the import options.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function createFromFile(
        Enums\ImportType|string $type,
        FileUpload|string $file,
        ?string $options = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Import {
        $this->connection->guard(
            'imports.create',
            [
                'type' => $type,
            ],
            [
                'type' => [
                    'enum' => ['phone_history', 'contacts_csv', 'suppressions_csv'],
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'imports.create',
            'POST',
            '/v1/imports',
            multipart: [
                'type' => $type,
                'file' => Params::file($file),
                'options' => $options,
            ],
            options: $requestOptions,
        );

        return Models\Import::fromArray($response->json(), $response->meta());
    }

    /**
     * Get an import.
     *
     * Requires `contacts:read`. Carries the totals and, once an error report exists, `error_report_url` signed
     * for 15 minutes and refreshed on every call (the URL is a secret). An import of another account answers
     * `404 NOT_FOUND`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Import {
        $response = $this->connection->request('imports.get', 'GET', '/v1/imports/'.rawurlencode($id), options: $requestOptions);

        return Models\Import::fromArray($response->json(), $response->meta());
    }

    /**
     * Cancel an import.
     *
     * Requires the ability of the import's type. An import that is `awaiting_device_confirmation`
     * (`phone_history`), `queued` or `running` becomes `cancelled`; rows already written stay. An import in
     * any other state answers `409 INVALID_STATE`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function cancel(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Import {
        $response = $this->connection->request('imports.cancel', 'POST', '/v1/imports/'.rawurlencode($id).'/cancel', options: $requestOptions);

        return Models\Import::fromArray($response->json(), $response->meta());
    }
}

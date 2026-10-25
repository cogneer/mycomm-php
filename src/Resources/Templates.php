<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Templates
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List templates.
     *
     * Requires `templates:read`. Templates of the account sorted by `name` (default) with cursor pagination.
     * Archived templates are hidden unless `archived=true`, which lists only them.
     *
     * @param  ?string  $q  Name contains, case-insensitively; at least 2 characters.
     * @param  ?bool  $archived  `true` lists only archived templates.
     * @param  ?string  $sort  One of: `name`, `-name`, `created_at`, `-created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Template>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $q = null,
        Enums\TemplateCategory|string|null $category = null,
        ?bool $archived = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'templates.list',
            [
                'q' => $q,
                'limit' => $limit,
            ],
            [
                'q' => [
                    'minLength' => 2,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'templates.list',
            'GET',
            '/v1/templates',
            query: Params::query([
                'q' => $q,
                'category' => $category,
                'archived' => $archived,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Template => Models\Template::fromArray($item), fn (string $next): Page => $this->list(q: $q, category: $category, archived: $archived, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $q  Name contains, case-insensitively; at least 2 characters.
     * @param  ?bool  $archived  `true` lists only archived templates.
     * @param  ?string  $sort  One of: `name`, `-name`, `created_at`, `-created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Template>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $q = null,
        Enums\TemplateCategory|string|null $category = null,
        ?bool $archived = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(q: $q, category: $category, archived: $archived, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create a template.
     *
     * Requires `templates:write`. `body` uses the variable syntax (`{{ name }}`, `{{ name | "fallback" }}`); a
     * syntax error answers `422 TEMPLATE_INVALID` with `details.position`. Names are unique among non-archived
     * templates, ignoring case (`409 NAME_TAKEN`). A body linking through a public URL shortener answers `422
     * LINK_PUBLIC_SHORTENER`, one estimated over 10 SMS parts `422 BODY_TOO_LONG`. Variables that are neither
     * contact nor custom fields are allowed (they must be supplied at send) and listed in `warnings`.
     * `media_id` must be a media item of the account.
     *
     * @param  ?string  $body  Required unless `media_id` is given.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $name,
        ?string $body,
        Enums\TemplateCategory|string $category,
        string|Omit|null $media_id = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\TemplateSaved {
        $this->connection->guard(
            'templates.create',
            [
                'name' => $name,
                'body' => $body,
                'category' => $category,
                'media_id' => $media_id,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'body' => [
                    'maxLength' => 3000,
                ],
                'category' => [
                    'enum' => ['transactional', 'marketing'],
                ],
                'media_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'templates.create',
            'POST',
            '/v1/templates',
            body: Params::body([
                'name' => $name,
                'body' => $body,
                'category' => $category,
                'media_id' => $media_id,
            ]),
            options: $requestOptions,
        );

        return Models\TemplateSaved::fromArray($response->json(), $response->meta());
    }

    /**
     * Preview unsaved template text.
     *
     * Requires `templates:read`, and `contacts:read` when `contact_id` is given. The same as render, for a
     * `body` that is not saved. Nothing is written.
     *
     * @param  array<string, mixed>|Omit|null  $variables  Variable values by name, at most 100; names up to 64 characters, values strings (up to 1000 characters) or numbers.
     * @param  string|Omit|null  $contact_id  A contact of the account whose fields fill the variables; the key must also hold `contacts:read`.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function preview(
        string $body,
        array|Omit|null $variables = Omit::Value,
        string|Omit|null $contact_id = Omit::Value,
        ?bool $shorten_links = null,
        ?RequestOptions $requestOptions = null,
    ): Models\TemplateRender {
        $this->connection->guard(
            'templates.preview',
            [
                'variables' => $variables,
                'contact_id' => $contact_id,
                'body' => $body,
            ],
            [
                'variables' => [
                    'maxProperties' => 100,
                    'scalarValues' => true,
                ],
                'contact_id' => [
                    'uuid' => true,
                ],
                'body' => [
                    'maxLength' => 3000,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'templates.preview',
            'POST',
            '/v1/templates/preview',
            body: Params::body([
                'variables' => Params::object($variables),
                'contact_id' => $contact_id,
                'shorten_links' => $shorten_links ?? Omit::Value,
                'body' => $body,
            ]),
            options: $requestOptions,
        );

        return Models\TemplateRender::fromArray($response->json(), $response->meta());
    }

    /**
     * Retrieve a template.
     *
     * Requires `templates:read`. Archived templates are returned too.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Template {
        $response = $this->connection->request('templates.show', 'GET', '/v1/templates/'.rawurlencode($id), options: $requestOptions);

        return Models\Template::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a template.
     *
     * Requires `templates:write`. Changes `name`, `body`, `category` and `media_id` (`null` detaches the
     * picture) under the rules of create. Messages already accepted keep their rendered body. An archived
     * template answers `422 VALIDATION_FAILED`; restore it first.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        string|Omit|null $body = Omit::Value,
        Enums\TemplateCategory|string|null $category = null,
        string|Omit|null $media_id = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\TemplateSaved {
        $this->connection->guard(
            'templates.update',
            [
                'name' => $name,
                'body' => $body,
                'category' => $category,
                'media_id' => $media_id,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'body' => [
                    'maxLength' => 3000,
                ],
                'category' => [
                    'enum' => ['transactional', 'marketing'],
                ],
                'media_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'templates.update',
            'PATCH',
            '/v1/templates/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'body' => $body,
                'category' => $category ?? Omit::Value,
                'media_id' => $media_id,
            ]),
            options: $requestOptions,
        );

        return Models\TemplateSaved::fromArray($response->json(), $response->meta());
    }

    /**
     * Archive a template.
     *
     * Requires `templates:write`. Archives the template (`archived_at` set; sends refuse it with
     * `TEMPLATE_ARCHIVED`). Templates are never hard-deleted through the API, because messages, campaigns and
     * automation rules reference them. Archiving an archived template changes nothing.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function archive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Template {
        $response = $this->connection->request('templates.archive', 'DELETE', '/v1/templates/'.rawurlencode($id), options: $requestOptions);

        return Models\Template::fromArray($response->json(), $response->meta());
    }

    /**
     * Restore an archived template.
     *
     * Requires `templates:write`. Unarchives the template; `409 NAME_TAKEN` while a non-archived template has
     * its name. Send `{}` as the body.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function restore(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Template {
        $response = $this->connection->request('templates.restore', 'POST', '/v1/templates/'.rawurlencode($id).'/restore', options: $requestOptions);

        return Models\Template::fromArray($response->json(), $response->meta());
    }

    /**
     * Render a template.
     *
     * Requires `templates:read`, and `contacts:read` when `contact_id` is given (`403 ABILITY_MISSING`
     * otherwise). Renders the saved body exactly as a send renders it for one recipient: `variables` first,
     * then the fields of `contact_id` (a contact of the account), then account fields, then each variable's
     * fallback. A variable without value or fallback renders empty and is listed in `missing_variables`, as a
     * send does. Values are plain text and are never evaluated as template syntax; values over 500 characters
     * are cut to 500. With `shorten_links`, each URL a send would shorten shows as a placeholder link on the
     * active short domain (`https://{domain}/l/xxxxxxx`, the length of a real short link) and counts at that
     * length; with no active short domain links stay as written, as in a send. Smart character replacement
     * follows the account setting `sms.smart_replace`. `non_gsm_characters` lists the characters that force
     * UCS-2. Nothing is written.
     *
     * @param  array<string, mixed>|Omit|null  $variables  Variable values by name, at most 100; names up to 64 characters, values strings (up to 1000 characters) or numbers.
     * @param  string|Omit|null  $contact_id  A contact of the account whose fields fill the variables; the key must also hold `contacts:read`.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function render(
        string $id,
        array|Omit|null $variables = Omit::Value,
        string|Omit|null $contact_id = Omit::Value,
        ?bool $shorten_links = null,
        ?RequestOptions $requestOptions = null,
    ): Models\TemplateRender {
        $this->connection->guard(
            'templates.render',
            [
                'variables' => $variables,
                'contact_id' => $contact_id,
            ],
            [
                'variables' => [
                    'maxProperties' => 100,
                    'scalarValues' => true,
                ],
                'contact_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'templates.render',
            'POST',
            '/v1/templates/'.rawurlencode($id).'/render',
            body: Params::body([
                'variables' => Params::object($variables),
                'contact_id' => $contact_id,
                'shorten_links' => $shorten_links ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\TemplateRender::fromArray($response->json(), $response->meta());
    }
}

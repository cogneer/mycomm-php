<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

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

final class ContactFields
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List custom contact field definitions.
     *
     * Requires `contacts:read`. All definitions of the account ordered by `position`; not paginated.
     *
     * @return Page<Models\ContactField>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request('contactFields.list', 'GET', '/v1/contact-fields', options: $requestOptions);

        return Page::fromResponse($response, static fn (array $item): Models\ContactField => Models\ContactField::fromArray($item), null);
    }

    /**
     * Create a custom contact field.
     *
     * Requires `contacts:write`. `key` matches `^[a-z][a-z0-9_]{0,39}$` and is not reserved (`first_name`,
     * `last_name`, `full_name`, `email`, `phone`, `language`, `group`); `key` and `type` cannot change later.
     * `options` is required for `select` and refused for other types. An account has at most 100 fields.
     * `position` defaults to after the last field.
     *
     * @param  list<string>|null  $options
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $key,
        string $label,
        Enums\ContactFieldType|string $type,
        ?array $options = null,
        ?int $position = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactField {
        $this->connection->guard(
            'contactFields.create',
            [
                'key' => $key,
                'label' => $label,
                'type' => $type,
                'options' => $options,
                'position' => $position,
            ],
            [
                'key' => [
                    'pattern' => '^[a-z][a-z0-9_]{0,39}$',
                ],
                'label' => [
                    'minLength' => 1,
                    'maxLength' => 60,
                ],
                'type' => [
                    'enum' => ['text', 'number', 'date', 'boolean', 'select'],
                ],
                'options' => [
                    'minItems' => 1,
                    'maxItems' => 100,
                ],
                'position' => [
                    'min' => 0,
                ],
            ],
            [
                ['requiredWhen', 'options', 'type', 'select'],
            ],
        );

        $response = $this->connection->request(
            'contactFields.create',
            'POST',
            '/v1/contact-fields',
            body: Params::body([
                'key' => $key,
                'label' => $label,
                'type' => $type,
                'options' => $options ?? Omit::Value,
                'position' => $position ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\ContactField::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a custom contact field.
     *
     * Requires `contacts:write`. Changes `label`, `options` (select fields only) and `position`. Sending `key`
     * or `type` is refused with `422`.
     *
     * @param  list<string>|null  $options
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $label = null,
        ?array $options = null,
        ?int $position = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactField {
        $this->connection->guard(
            'contactFields.update',
            [
                'label' => $label,
                'options' => $options,
                'position' => $position,
            ],
            [
                'label' => [
                    'minLength' => 1,
                    'maxLength' => 60,
                ],
                'options' => [
                    'minItems' => 1,
                    'maxItems' => 100,
                ],
                'position' => [
                    'min' => 0,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'contactFields.update',
            'PATCH',
            '/v1/contact-fields/'.rawurlencode($id),
            body: Params::body([
                'label' => $label ?? Omit::Value,
                'options' => $options ?? Omit::Value,
                'position' => $position ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\ContactField::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a custom contact field.
     *
     * Requires `contacts:write`. Removes the definition and its key from every contact. Refused with `409`
     * `INVALID_STATE` and `details.used_by` (the users of the field, each `{type, id, name}`) while a
     * smart-list filter references it; edit or delete those smart lists first.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('contactFields.delete', 'DELETE', '/v1/contact-fields/'.rawurlencode($id), options: $requestOptions);
    }
}

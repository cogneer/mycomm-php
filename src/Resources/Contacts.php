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

final class Contacts
{
    public readonly Contacts\Identities $identities;

    public function __construct(private readonly Connection $connection)
    {
        $this->identities = new Contacts\Identities($connection);
    }

    /**
     * Upsert a contact by phone number.
     *
     * Requires `contacts:write`. Finds the contact holding `identity` (the address is normalised to E.164 with
     * the account country), including a soft-deleted one, which is restored. When found, the sent fields are
     * merged as in `PATCH /v1/contacts/{id}` (top-level fields replace, `null` empties one; `custom_fields`
     * merges key by key, a `null` value removes a key) and the answer is `200`; otherwise a contact is created
     * with that identity as primary and the answer is `201`. `add_to_group_ids` only adds memberships. An
     * opt-out of the number is never cleared. Emits `contact.created` or `contact.updated`. This is the call
     * integrations use to keep MyComm contacts in sync with their own customer records.
     *
     * @param  array{type: string, address: string}  $identity
     * @param  string|Omit|null  $timezone  IANA time zone.
     * @param  string|Omit|null  $language  BCP 47 tag.
     * @param  array<string, mixed>|Omit|null  $custom_fields  Merged key by key; a `null` value removes the key.
     * @param  list<string>|null  $add_to_group_ids  Adds these memberships; existing ones are kept.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function upsertByIdentity(
        array $identity,
        string|Omit|null $first_name = Omit::Value,
        string|Omit|null $last_name = Omit::Value,
        string|Omit|null $email = Omit::Value,
        string|Omit|null $timezone = Omit::Value,
        string|Omit|null $language = Omit::Value,
        array|Omit|null $custom_fields = Omit::Value,
        ?array $add_to_group_ids = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Contact {
        $response = $this->connection->request(
            'contacts.upsertByIdentity',
            'PUT',
            '/v1/contacts/by-identity',
            body: Params::body([
                'identity' => Params::object($identity),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'timezone' => $timezone,
                'language' => $language,
                'custom_fields' => Params::object($custom_fields),
                'add_to_group_ids' => $add_to_group_ids ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Contact::fromArray($response->json(), $response->meta());
    }

    /**
     * Upsert up to 1,000 contacts.
     *
     * Requires `contacts:write`. Each item has the shape of `PUT /v1/contacts/by-identity` and is processed
     * independently, in order; the top-level `add_to_group_ids` is added for every item, together with the
     * item's own. The answer is always `200` with a `summary` and one `results[]` entry per item, also when
     * every item was rejected. A rejected item carries the error code the single upsert would answer
     * (`INVALID_NUMBER`, `IDENTITY_TYPE_UNAVAILABLE`, `VALIDATION_FAILED`) and writes nothing.
     *
     * Envelope problems answer `422` and nothing is processed: `BATCH_TOO_LARGE` (`details.max`, 1,000) or
     * `VALIDATION_FAILED`. Larger sets go through a CSV import. `Idempotency-Key` covers the whole call.
     *
     * @param  list<Inputs\ContactUpsert|array<string, mixed>>  $contacts
     * @param  list<string>|null  $add_to_group_ids  Added for every item, together with the item's own `add_to_group_ids`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function bulkUpsert(
        array $contacts,
        ?array $add_to_group_ids = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactBulkResponse {
        $response = $this->connection->request(
            'contacts.bulkUpsert',
            'POST',
            '/v1/contacts/bulk',
            body: Params::body([
                'contacts' => $contacts,
                'add_to_group_ids' => $add_to_group_ids ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\ContactBulkResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * List contacts.
     *
     * Requires `contacts:read`. Live (not soft-deleted) contacts of the account with cursor pagination. Comma
     * lists match any of the values. `q` matches the name, email and any address (case-insensitive, at least 2
     * characters; a phone-like search is also matched against the digits of the stored number, so `3451234`
     * finds `+923451234567`). `display_name` sorts with ICU collation; contacts without a name sort first and
     * ties break on `id`.
     *
     * @param  ?string  $q  Name, email or address contains, at least 2 characters.
     * @param  ?string  $address  Matches any identity address (comma list).
     * @param  ?string  $identity_type  Comma list.
     * @param  ?string  $group_id  Members of any of these groups (comma list).
     * @param  ?string  $smart_list_id  Members of this smart list, evaluated live; an id that is not a smart list of the account answers `422`.
     * @param  ?string  $source  Comma list.
     * @param  ?string  $language  Comma list.
     * @param  ?bool  $blocked  `true` = any identity is blocked.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  DateTimeInterface|string|null  $updated_after  Inclusive lower bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`, `display_name`, `-display_name`, `-updated_at`, `updated_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Contact>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $q = null,
        ?string $address = null,
        ?string $identity_type = null,
        ?string $group_id = null,
        ?string $smart_list_id = null,
        ?string $source = null,
        ?string $language = null,
        ?bool $blocked = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        DateTimeInterface|string|null $updated_after = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'contacts.list',
            'GET',
            '/v1/contacts',
            query: Params::query([
                'q' => $q,
                'address' => $address,
                'identity_type' => $identity_type,
                'group_id' => $group_id,
                'smart_list_id' => $smart_list_id,
                'source' => $source,
                'language' => $language,
                'blocked' => $blocked,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'updated_after' => $updated_after,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Contact => Models\Contact::fromArray($item), fn (string $next): Page => $this->list(q: $q, address: $address, identity_type: $identity_type, group_id: $group_id, smart_list_id: $smart_list_id, source: $source, language: $language, blocked: $blocked, created_after: $created_after, created_before: $created_before, updated_after: $updated_after, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $q  Name, email or address contains, at least 2 characters.
     * @param  ?string  $address  Matches any identity address (comma list).
     * @param  ?string  $identity_type  Comma list.
     * @param  ?string  $group_id  Members of any of these groups (comma list).
     * @param  ?string  $smart_list_id  Members of this smart list, evaluated live; an id that is not a smart list of the account answers `422`.
     * @param  ?string  $source  Comma list.
     * @param  ?string  $language  Comma list.
     * @param  ?bool  $blocked  `true` = any identity is blocked.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  DateTimeInterface|string|null  $updated_after  Inclusive lower bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`, `display_name`, `-display_name`, `-updated_at`, `updated_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Contact>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $q = null,
        ?string $address = null,
        ?string $identity_type = null,
        ?string $group_id = null,
        ?string $smart_list_id = null,
        ?string $source = null,
        ?string $language = null,
        ?bool $blocked = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        DateTimeInterface|string|null $updated_after = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(q: $q, address: $address, identity_type: $identity_type, group_id: $group_id, smart_list_id: $smart_list_id, source: $source, language: $language, blocked: $blocked, created_after: $created_after, created_before: $created_before, updated_after: $updated_after, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create a contact.
     *
     * Requires `contacts:write`. At least one identity; `phone` is the only type in v1 (`422
     * IDENTITY_TYPE_UNAVAILABLE` for the others) and the address is normalised to E.164 using the account
     * country (`422 INVALID_NUMBER`). A number held by another contact answers `409 IDENTITY_TAKEN` with
     * `details.contact_id`. `custom_fields` keys must be defined contact fields. `group_ids` adds the
     * memberships. Emits `contact.created`.
     *
     * @param  list<array{type: string, address: string, is_primary?: bool}>  $identities
     * @param  string|Omit|null  $timezone  IANA time zone.
     * @param  string|Omit|null  $language  BCP 47 tag.
     * @param  array<string, mixed>|Omit|null  $custom_fields
     * @param  list<string>|null  $group_ids
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        array $identities,
        string|Omit|null $first_name = Omit::Value,
        string|Omit|null $last_name = Omit::Value,
        string|Omit|null $email = Omit::Value,
        string|Omit|null $timezone = Omit::Value,
        string|Omit|null $language = Omit::Value,
        array|Omit|null $custom_fields = Omit::Value,
        ?array $group_ids = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Contact {
        $response = $this->connection->request(
            'contacts.create',
            'POST',
            '/v1/contacts',
            body: Params::body([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'timezone' => $timezone,
                'language' => $language,
                'custom_fields' => Params::object($custom_fields),
                'identities' => $identities,
                'group_ids' => $group_ids ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Contact::fromArray($response->json(), $response->meta());
    }

    /**
     * Retrieve a contact.
     *
     * Requires `contacts:read`. A soft-deleted contact answers `404`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Contact {
        $response = $this->connection->request('contacts.show', 'GET', '/v1/contacts/'.rawurlencode($id), options: $requestOptions);

        return Models\Contact::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a contact.
     *
     * Requires `contacts:write`. Top-level fields replace the stored value (`null` empties it);
     * `custom_fields` merges key by key (`null` removes a key); `group_ids` replaces the memberships when
     * sent. Identities have their own endpoints. Emits `contact.updated` when something changed.
     *
     * @param  string|Omit|null  $timezone  IANA time zone.
     * @param  string|Omit|null  $language  BCP 47 tag.
     * @param  array<string, mixed>|Omit|null  $custom_fields  Merged key by key; a `null` value removes the key.
     * @param  list<string>|null  $group_ids  Replaces the memberships.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        string|Omit|null $first_name = Omit::Value,
        string|Omit|null $last_name = Omit::Value,
        string|Omit|null $email = Omit::Value,
        string|Omit|null $timezone = Omit::Value,
        string|Omit|null $language = Omit::Value,
        array|Omit|null $custom_fields = Omit::Value,
        ?array $group_ids = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Contact {
        $response = $this->connection->request(
            'contacts.update',
            'PATCH',
            '/v1/contacts/'.rawurlencode($id),
            body: Params::body([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'timezone' => $timezone,
                'language' => $language,
                'custom_fields' => Params::object($custom_fields),
                'group_ids' => $group_ids ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Contact::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a contact.
     *
     * Requires `contacts:write`. Soft delete: the contact is hidden from lists, groups and audiences while its
     * conversations and messages stay; emits `contact.deleted` (`erased: false`) and answers `204`. With
     * `?erase=true` (GDPR erasure, 01 section 4.4) the contact, its identities, group memberships,
     * conversations, messages, message events, media, short links and link clicks are permanently deleted by a
     * queued job, which emits `contact.deleted` (`erased: true`); suppressions and consent events (plain
     * E.164) and aggregates (`usage_daily`, campaign stats) are kept. Answers `202 {"status": "erasing"}`; a
     * soft-deleted contact can be erased too.
     *
     * @param  ?bool  $erase  `true` erases the contact and everything on the erasure list.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?bool $erase = null,
        ?RequestOptions $requestOptions = null,
    ): ?Models\ContactsDeleteResponse {
        $response = $this->connection->request(
            'contacts.delete',
            'DELETE',
            '/v1/contacts/'.rawurlencode($id),
            query: Params::query([
                'erase' => $erase,
            ]),
            options: $requestOptions,
        );

        if ($response->body === '') {
            return null;
        }

        return Models\ContactsDeleteResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * List the conversations of a contact.
     *
     * Requires `conversations:read`. The contact's conversations across inboxes, newest `last_message_at`
     * first (restricted keys: the conversations of their inboxes only). A conversation without messages is not
     * listed.
     *
     * @param  ?string  $sort  One of: `-last_message_at`, `last_message_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Conversation>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function conversations(
        string $id,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'contacts.conversations',
            'GET',
            '/v1/contacts/'.rawurlencode($id).'/conversations',
            query: Params::query([
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Conversation => Models\Conversation::fromArray($item), fn (string $next): Page => $this->conversations(id: $id, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }
}

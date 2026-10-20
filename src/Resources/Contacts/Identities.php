<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Contacts;

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

final class Identities
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Add an identity to a contact.
     *
     * Requires `contacts:write`. `type` is `phone` in v1 and the address is normalised to E.164 with the
     * account country (`422 INVALID_NUMBER`); `whatsapp` and `telegram` identities are added with their
     * channels (`422 IDENTITY_TYPE_UNAVAILABLE` before). `sender_id` identities are created by MyComm from
     * inbound only (`422 INVALID_NUMBER`). A number another contact holds answers `409 IDENTITY_TAKEN` with
     * `details.contact_id`; one this contact already holds answers `200` and creates nothing. A contact has at
     * most 10 identities. `is_primary: true` makes it the primary (the previous primary is unset and an
     * automatic `display_name` follows it). Emits `contact.updated`. An opt-out of the number is never
     * cleared.
     *
     * @param  string  $type  `phone` in v1.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $id,
        string $type,
        string $address,
        ?bool $is_primary = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactIdentity {
        $response = $this->connection->request(
            'contacts.identities.create',
            'POST',
            '/v1/contacts/'.rawurlencode($id).'/identities',
            body: Params::body([
                'type' => $type,
                'address' => $address,
                'is_primary' => $is_primary ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\ContactIdentity::fromArray($response->json(), $response->meta());
    }

    /**
     * Make an identity primary.
     *
     * Requires `contacts:write`. Only `{"is_primary": true}`: the identity becomes the contact's primary, the
     * previous primary is unset and an automatic `display_name` (one the owner did not set) follows the new
     * primary. The type and address of an identity never change (`422 VALIDATION_FAILED`). A `sender_id`
     * identity is receive-only and cannot be made primary (`422 INVALID_NUMBER`). Emits `contact.updated` when
     * something changed.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        string $identity_id,
        bool $is_primary,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactIdentity {
        $response = $this->connection->request(
            'contacts.identities.update',
            'PATCH',
            '/v1/contacts/'.rawurlencode($id).'/identities/'.rawurlencode($identity_id),
            body: Params::body([
                'is_primary' => $is_primary,
            ]),
            options: $requestOptions,
        );

        return Models\ContactIdentity::fromArray($response->json(), $response->meta());
    }

    /**
     * Remove an identity.
     *
     * Requires `contacts:write`. `409 INVALID_STATE` for the contact's last identity or an identity with
     * conversations (delete the contact instead). A removed primary is replaced by the earliest identity left,
     * and an automatic `display_name` follows it. Suppressions and consent of the address are kept, so an
     * opt-out still applies when the number comes back. Emits `contact.updated`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        string $identity_id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('contacts.identities.delete', 'DELETE', '/v1/contacts/'.rawurlencode($id).'/identities/'.rawurlencode($identity_id), options: $requestOptions);
    }

    /**
     * Block an identity account-wide.
     *
     * Requires `contacts:write`. Sets `blocked_at` for every inbox and channel of the account. New outbound to
     * the identity is refused (`CONTACT_BLOCKED`) and its `queued` outbound is cancelled (`message.cancelled`,
     * reason `contact_blocked`). Inbound is still stored but is not counted as unread, runs no automation,
     * sends no notification and emits no `message.received` webhook; opt-out and opt-in keywords still apply.
     * Campaign recipients are skipped (`blocked`). Any identity can be blocked, a `sender_id` one included.
     * Idempotent: blocking a blocked identity changes nothing. Emits `contact.updated`. An opt-out is neither
     * set nor cleared. Send an empty JSON object `{}` as the body.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function block(
        string $id,
        string $identity_id,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactIdentity {
        $response = $this->connection->request('contacts.identities.block', 'POST', '/v1/contacts/'.rawurlencode($id).'/identities/'.rawurlencode($identity_id).'/block', options: $requestOptions);

        return Models\ContactIdentity::fromArray($response->json(), $response->meta());
    }

    /**
     * Unblock an identity.
     *
     * Requires `contacts:write`. Clears `blocked_at`, account-wide. Nothing is replayed: messages received or
     * cancelled while blocked stay as they are. Idempotent. Emits `contact.updated`. Send an empty JSON object
     * `{}` as the body.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unblock(
        string $id,
        string $identity_id,
        ?RequestOptions $requestOptions = null,
    ): Models\ContactIdentity {
        $response = $this->connection->request('contacts.identities.unblock', 'POST', '/v1/contacts/'.rawurlencode($id).'/identities/'.rawurlencode($identity_id).'/unblock', options: $requestOptions);

        return Models\ContactIdentity::fromArray($response->json(), $response->meta());
    }
}

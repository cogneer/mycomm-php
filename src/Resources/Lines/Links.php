<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Lines;

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

final class Links
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Create a hosted link for a line.
     *
     * Requires `links:write`. A short-lived link to MyComm's hosted page for one of your lines: send your
     * user's browser to `url`. `purpose` is what the page does: `connect` (the first phone; it also asks for
     * the user's number and joins an account that already has it, after a code sent to that number),
     * `add_channel` (one more SIM, added to the line as its last backup), `replace_device` (a new phone for
     * `replaces_device_id`, a phone of the line's account), `manage` and `claim`. `url` is returned only here
     * and is a bearer credential: treat it like a password. At most 20 links of a line are open at once (`422
     * CONNECT_SESSION_LIMIT_REACHED` with `details.max`) and 60 are created per line per hour (`429
     * RATE_LIMITED`). A detached line takes no new link (`422`), and detaching a line ends its open links. A
     * line this key does not see is `404 NOT_FOUND`. Partner keys use `POST /partner/v1/lines/{id}/links`.
     *
     * @param  string|Omit|null  $return_url  `https` URL the page returns to when done; no credentials.
     * @param  list<string>|Omit|null  $allowed_origins  Origins that may frame the page and receive its outcome. `null` = no framing and no messages.
     * @param  int|Omit|null  $ttl  Seconds until the link stops working.
     * @param  string|Omit|null  $locale  BCP 47 tag of the page's language; defaults to the account's default locale.
     * @param  string|Omit|null  $replaces_device_id  Required for `replace_device`, refused for any other purpose. An active phone of the line's account.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $id,
        Enums\HostedLinkPurpose|string $purpose,
        string|Omit|null $return_url = Omit::Value,
        array|Omit|null $allowed_origins = Omit::Value,
        int|Omit|null $ttl = Omit::Value,
        string|Omit|null $locale = Omit::Value,
        string|Omit|null $replaces_device_id = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\CreatedHostedLink {
        $this->connection->guard(
            'lines.links.create',
            [
                'purpose' => $purpose,
                'return_url' => $return_url,
                'allowed_origins' => $allowed_origins,
                'ttl' => $ttl,
                'locale' => $locale,
                'replaces_device_id' => $replaces_device_id,
            ],
            [
                'purpose' => [
                    'enum' => ['connect', 'add_channel', 'replace_device', 'manage', 'claim'],
                ],
                'return_url' => [
                    'maxLength' => 2048,
                ],
                'allowed_origins' => [
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'ttl' => [
                    'min' => 300,
                    'max' => 3600,
                ],
                'locale' => [
                    'maxLength' => 35,
                ],
                'replaces_device_id' => [
                    'uuid' => true,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'lines.links.create',
            'POST',
            '/v1/lines/'.rawurlencode($id).'/links',
            body: Params::body([
                'purpose' => $purpose,
                'return_url' => $return_url,
                'allowed_origins' => $allowed_origins,
                'ttl' => $ttl,
                'locale' => $locale,
                'replaces_device_id' => $replaces_device_id,
            ]),
            options: $requestOptions,
        );

        return Models\CreatedHostedLink::fromArray($response->json(), $response->meta());
    }
}

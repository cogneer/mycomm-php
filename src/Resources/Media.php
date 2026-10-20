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

final class Media
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Upload an image.
     *
     * Requires `media:write`. `multipart/form-data` with one part `file` of at most 10 MB. The type is sniffed
     * from the content (`image/jpeg`, `image/png`, `image/gif`, `image/webp`), never taken from the header or
     * the file name. The upload is stored with `purpose: message`; reference its `id` from a message. An
     * upload still unattached after 24 hours is deleted. Rate limit: 60 requests per minute per account.
     *
     * Errors: `413 PAYLOAD_TOO_LARGE` (over 10 MB), `422 MEDIA_TYPE_UNSUPPORTED`, `422 VALIDATION_FAILED` (no
     * `file` part).
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function upload(
        FileUpload|string $file,
        ?RequestOptions $requestOptions = null,
    ): Models\Media {
        $response = $this->connection->request(
            'media.upload',
            'POST',
            '/v1/media',
            multipart: [
                'file' => Params::file($file),
            ],
            options: $requestOptions,
        );

        return Models\Media::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a media with a fresh signed URL.
     *
     * Requires `media:write` or `messages:read`. Every call signs a new `url` valid for 15 minutes; inbound
     * MMS media are fetched this way (webhooks carry ids, not URLs). A key restricted to inboxes sees only
     * media of messages in its inboxes, or uploaded with that key; any other media answers `404 NOT_FOUND`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Media {
        $response = $this->connection->request('media.get', 'GET', '/v1/media/'.rawurlencode($id), options: $requestOptions);

        return Models\Media::fromArray($response->json(), $response->meta());
    }
}

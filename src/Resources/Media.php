<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\RequestOptions;

final class Media
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Upload a media file.
     *
     * Requires `media:write`. `multipart/form-data` with one part `file`. The type is sniffed from the
     * content, never taken from the header or the file name. Every user may upload images (`image/jpeg`,
     * `image/png`, `image/gif`, `image/webp`) of at most 10 MB; the other types and sizes are those of the
     * channel types the user may use (`GET /v1/channels/types`, `capabilities.media` of each available type),
     * for example WhatsApp video, audio and documents up to their caps. Whether a channel can send the file is
     * checked when a message references it. Images have their metadata stripped; other files are stored as
     * uploaded. The upload is stored with `purpose: message`; reference its `id` from a message. An upload
     * still unattached after 24 hours is deleted. Rate limit: 60 requests per minute per user.
     *
     * A file the sniffer names otherwise than a channel type does (for WhatsApp: an m4a sniffed as
     * `audio/x-m4a` is `audio/mp4`; a real docx, xlsx or pptx without macros that sniffs as a zip is its
     * Office type) is stored under the channel type's name.
     *
     * Errors: `413 PAYLOAD_TOO_LARGE` (over the cap of its type, `details.max_bytes`), `422
     * MEDIA_TYPE_UNSUPPORTED` (`details.accepted` lists the types this user may upload), `422
     * VALIDATION_FAILED` (no `file` part), `503 SERVICE_UNAVAILABLE` (the file could not be stored right now;
     * retry after `Retry-After` seconds).
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
     * MMS media are fetched this way (webhooks carry ids, not URLs). A key restricted to lines sees only media
     * of messages in its lines, or uploaded with that key; any other media answers `404 NOT_FOUND`.
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

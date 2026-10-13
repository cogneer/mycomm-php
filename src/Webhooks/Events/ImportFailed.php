<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `import.failed`: An import failed; `data.import.error` says why. Same shape as `import.completed`.
 */
final class ImportFailed extends Event
{
    public const TYPE = 'import.failed';

    public readonly Models\WebhookImportData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookImportData::fromArray($data['data']);
    }
}

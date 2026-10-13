<?php

declare(strict_types=1);

namespace MyComm\Webhooks;

/**
 * An event whose type this SDK version does not know. Read `data` as an array, or ignore the event and
 * answer 2xx.
 */
final class UnknownEvent extends Event
{
    /** @var array<string, mixed> */
    public readonly array $data;

    protected function hydrateData(array $data): void
    {
        $this->data = is_array($data['data'] ?? null) ? $data['data'] : [];
    }
}

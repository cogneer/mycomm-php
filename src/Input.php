<?php

declare(strict_types=1);

namespace MyComm;

use JsonSerializable;

/**
 * Base of the objects that are nested in a request, such as one item of a batch.
 */
abstract class Input implements JsonSerializable
{
    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * @return array<string, mixed>|object
     */
    public function jsonSerialize(): array|object
    {
        $data = $this->toArray();

        return $data === [] ? new \stdClass : $data;
    }
}

<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

use MyComm\Http\Response;

/**
 * A successful answer whose body is not the JSON the API documents, usually a proxy in between.
 */
final class UnexpectedResponseException extends MyCommException
{
    public function __construct(string $message, public readonly ?Response $response = null)
    {
        parent::__construct($message);
    }
}

<?php

declare(strict_types=1);

namespace MyComm\Http;

use MyComm\Exceptions\ConnectionException;

/**
 * Sends one prepared request and returns whatever the server answered, error statuses included.
 */
interface Transport
{
    /**
     * @throws ConnectionException when no answer arrived (DNS, TLS, timeout, connection reset)
     */
    public function send(Request $request): Response;
}

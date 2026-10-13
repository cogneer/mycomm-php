<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 401: the API key is missing, unknown, revoked or expired.
 */
final class AuthenticationException extends ApiException {}

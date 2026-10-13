<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 413: the request body is too large.
 */
final class PayloadTooLargeException extends ApiException {}

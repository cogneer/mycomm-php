<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 415: the request body is not in the format the endpoint takes. `details['expected']` names the format the endpoint wants; JSON endpoints want `Content-Type: application/json`. The SDK sets it, so this means a custom transport changed the header.
 */
final class UnsupportedMediaTypeException extends BadRequestException {}

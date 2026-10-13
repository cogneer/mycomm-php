<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 422: the request is well formed but a rule refuses it, for example `RECIPIENT_OPTED_OUT` or
 * `CHANNEL_INACTIVE`. Do not retry it unchanged.
 */
class UnprocessableException extends ApiException {}

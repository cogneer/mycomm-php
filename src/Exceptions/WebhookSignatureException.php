<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * A webhook request that must be rejected: no valid signature, a timestamp outside the tolerance, or a
 * body that is not an event.
 */
final class WebhookSignatureException extends MyCommException {}

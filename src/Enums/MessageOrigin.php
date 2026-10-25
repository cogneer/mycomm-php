<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * Where the message came from: `api` is your request, `portal` a person using the portal, `campaign` and
 * `automation` the features of those names, `phone` a message typed on the phone, `import` a phone-history
 * import, `system` MyComm itself, `canary` a delivery check. A new value may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageOrigin: string
{
    case Portal = 'portal';
    case Api = 'api';
    case Campaign = 'campaign';
    case Automation = 'automation';
    case Phone = 'phone';
    case Import = 'import';
    case System = 'system';
    case Canary = 'canary';
}

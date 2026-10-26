<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ImportType: string
{
    case PhoneHistory = 'phone_history';
    case ContactsCsv = 'contacts_csv';
    case SuppressionsCsv = 'suppressions_csv';
}

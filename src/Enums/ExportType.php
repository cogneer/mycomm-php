<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ExportType: string
{
    case MessagesCsv = 'messages_csv';
    case ContactsCsv = 'contacts_csv';
    case CampaignReport = 'campaign_report';
    case ContactJson = 'contact_json';
    case AccountZip = 'account_zip';
}

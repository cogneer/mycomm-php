# Changelog

All notable changes to this package are listed here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the package follows
[semantic versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - Unreleased

Built from the MyComm public API and partner API `2.0.0`, where lines replace inboxes and partners give each user a
line. Nothing from 1.x is kept as an alias.

### Changed (breaking)

- `inboxes` is now `lines`: `$mycomm->lines->list()`, `all()`, `create()`, `get()`, `update()`, `archive()` (now
  `DELETE /v1/lines/{id}`) and `unarchive()`. `Inbox`, `InboxChannel`, `InboxSettings` and `InboxKind` are gone; use
  `Line`, `LineSender`, `LineSettings` and `LineKind`.
- `inbox_id` is now `line_id` everywhere: `messages->send()`, `sendBatch()` items, conversations, messages and
  webhook events (`$event->line_id`).
- `Client::verify(?string $to, ?string $lineId)`: the second argument was `$inboxId`.
- Partner keys act on a line, not an account: `Client::forLine(string $lineId)` replaces `forAccount()` and every `/v1`
  call of that client carries `MyComm-Line` (was `MyComm-Account`); the constructor option `lineId` replaces
  `accountId`, and `MyCommFake` takes `lineId` too.

### Removed (breaking)

- Managed accounts: `partner->accounts` (`provision`, `list`, `all`, `get`, `update`, `suspend`, `reactivate`,
  `delete`) and `partner->accountKeys` (`create`, `list`, `all`, `revoke`), with `ManagedAccount`,
  `ManagedAccountCounts` and `ManagedAccountSettings`. A partner gives each user a line instead.
- Connect sessions: `connectSessions` (`create`, `get`, `delete`), `ConnectSession`, `ConnectSessionsCreateResponse` and
  `ConnectSessionStatus`. Hosted links replace them.

### Added

- Partner lines: `partner->lines->list()`, `all()`, `create()`, `get()`, `update()` and `detach()`, and
  `partner->lines->status($id)` (`PartnerLineStatus`: `state`, `needs_attention_reasons`, channels and sponsorship).
- Hosted links: `partner->lines->links->create($lineId, purpose: ...)` and `partner->links->get()` / `revoke()` for
  partner keys, `lines->links->create()` and `links->get()` / `revoke()` for account keys (`CreatedHostedLink`, with
  the one-time `url`, and `HostedLink`; purposes `connect`, `add_channel`, `replace_device`, `manage`, `claim`).
- `partner->sponsorshipDefaults->get()` and `put()`, and `sponsorship` on partner lines (`SponsorshipTerms`).
- `partner->usage->get()` reports one row per partner line (`line_id`, `external_ref`); its filter is `line_id`.
- `webhookEndpoints->create()` / `update()`: `partner_lines`.
- Webhook events `line.connected`, `line.needs_attention`, `line.recovered`, `line.detached`, `line.quota_low` and
  `line.quota_exhausted` (56 in all); enums `LineState`, `LineNeedsAttentionReason`, `LineDetachedBy`,
  `HostedLinkPurpose`, `HostedLinkStatus` and `SponsorshipOnExhausted`.
- README: the partner flow for an ERP (a line per tenant, a `connect` link, the widget, `forLine()`, status and
  `line.*` webhooks).
- `lines->listSenders($id)` and `lines->setSenders($id, senders: [...])`: the channels a line sends from, with role
  (`primary`, `backup`, `pool`) and health.
- `conversations->handover($id, line_id: ...)`: hand a conversation over to another line.
- Webhook events `line.sender_changed`, `line.failover`, `line.no_sender` and `conversation.handed_over`.
- `Message::$sender_pinned` and `$sender_attempts`; `Channel::$line_ids`.

## [1.0.1] - 2026-10-26

Built from the same API `1.0.0`, whose document now states more of the rules the server already applies.

### Fixed

- More checks before sending, for rules the server already enforced: `contactFields->create()` needs `options` for a
  `select` field, `devices->update()` needs `os_limit_raised` with `os_limit_value` and a value when raising,
  `automationRules->create()` needs `match` for a keyword rule, and `exports->create()` needs `params` for
  `messages_csv`, `contact_json` and `campaign_report`. A broken rule now fails at once with `InvalidArgumentException`
  instead of a `422` from the API.

## [1.0.0] - 2026-10-26

The first stable release, built from the MyComm public API and partner API `1.0.0`, the frozen contract. From here the
package follows semantic versioning against that contract: `1.x` releases only add. Against 0.1.0 this release changes
signatures and exceptions, and nothing from 0.1.0 is kept as an alias (0.2.0 was never released; its changes are here).

### Added

- **Checks before sending.** Limits and rules of the OpenAPI document (ranges, lengths, formats, allowed values,
  `to` or `contact_id`, at least one content field, `variables` with `template_id`, `category` with the bulk lane)
  are checked before a call is sent, and `InvalidArgumentException` names the field and the rule. Turn off with
  `new Client(..., validate: false)`.
- `apiKeys->rotate($id, grace_period_hours: 24)`: a new key with the same scope; the old one keeps working for the grace
  period (`0` revokes it at once). The result has the new `token` and `replaces`.
- `Client::verify()` and `VerifyResult`: checks a key and, with a number, dry-runs a send. Sends nothing.
- `ApiException::summary()`, `__toString()`, `code()` (the enum case, or `null` for a newer code), `isAnyOf()` and
  `docUrl()`; `ValidationException::summary()` lists each field and rule.
- `InvalidHeaderException` (`400 INVALID_HEADER`) and `UnsupportedMediaTypeException` (`415`).
- `Retry-After` in the form of an HTTP date.
- Resources and operations that were missing: campaigns, templates, automation rules and runs, connect sessions,
  `contacts->optOut()`, eight more webhook events (46 in all), `ErrorCode::ConnectSessionLimitReached`,
  `ErrorCode::InvalidHeader` and the rest of the new catalogue.
- Comma-list filters (`status`, `outcome`, ...) take a list or the comma-separated text.
- `MyCommFake` takes `validate: false`.

### Changed (breaking)

- The enums of the message resource are typed now: `$message->status`, `direction`, `type`, `origin`, `hold_reason`,
  `category` and `lane` are enum cases (`MessageStatus::Queued`), and stay plain strings when the value is newer than
  the SDK. Compare with the case instead of the text.
- The generated code follows the reworked contract: `BadRequestException` is no longer final, error codes and error
  `details` follow the new catalogue, and `ErrorCode` holds only the codes of the public API.
- The generator names operations from a table in `generator/config.php`: the documents use camelCase operation ids
  (`sendMessage`) and the SDK keeps `messages->send()`.

## [0.1.0] - 2026-10-14

First release. Built from the MyComm public API 1.0.0 and partner API 1.0.0.

### Added

- `MyComm\Client` with every operation of the public API (112 methods on 20 resources) and of the partner
  API (11 methods), typed arguments and typed results.
- Models for every object the API returns, with enums that tolerate values added later and the raw
  payload on `raw()`.
- Automatic retries with backoff for network errors, `429`, `503` and other `5xx` answers, honouring
  `Retry-After`; an `Idempotency-Key` on every POST, kept across retries.
- `Page` for lists, `all()` and `autoPaging()` to walk every page.
- An exception per kind of error, with the API's error code, details and request id.
- `MyComm\Webhooks\Webhook` to verify signatures (including during a secret rotation) and a class for each
  of the 38 webhook events.
- `MyComm\Testing\MyCommFake` and `FakeWebhook` for testing code that uses the SDK.
- Multipart uploads for media and CSV imports.

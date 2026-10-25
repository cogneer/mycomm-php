# Changelog

All notable changes to this package are listed here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the package follows
[semantic versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

Built from the MyComm public API and partner API `1.0.0-rc.2`. This release changes signatures and exceptions; the
package stays on `0.x` until the API is frozen at `1.0.0`, and nothing from 0.1.0 is kept as an alias.

### Added

- **Checks before sending.** Limits and rules of the OpenAPI document (ranges, lengths, formats, allowed values,
  `to` or `contact_id`, at least one content field, `variables` with `template_id`, `category` with the bulk lane)
  are checked before a call is sent, and `InvalidArgumentException` names the field and the rule. Turn off with
  `new Client(..., validate: false)`.
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

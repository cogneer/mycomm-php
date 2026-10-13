# Changelog

All notable changes to this package are listed here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the package follows
[semantic versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

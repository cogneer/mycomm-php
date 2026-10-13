# Design of the MyComm PHP SDK

Decided 2026-10-14. This records what the SDK is for and why it is shaped the way it is. For how to work on
it, see [development.md](development.md).

## Purpose

Customers' developers and partners integrate MyComm from PHP without writing HTTP code: send and track
messages, sync contacts, receive webhooks, provision managed accounts. Success means an integration that is
correct by default (no double sends, no crashes when the API grows) and that their editor can autocomplete.

In scope: every operation of the public API (`/v1`) and the partner API (`/partner/v1`), and the webhook
events. Out of scope: the device API, which only the MyComm Android app uses.

## Decisions

| Question | Decision | Why |
|---|---|---|
| Language first | PHP 8.2+ | The customers' stack; readonly properties and enums give clean typed models; 8.1 is out of support. |
| Coverage | Everything in both specs | Chosen over a core subset; it is what makes generation necessary. |
| How the code is produced | Hand-written runtime, generated API surface, own generator | The specs are regular enough for a small generator, the output reads like hand-written PHP, and the rules the API sets for clients (unknown fields and enum values must be tolerated) are followed exactly. Off-the-shelf generators handle OpenAPI 3.1 and those rules poorly; a vendor adds a subscription and a service to the release path. |
| Packaging | Framework-agnostic core (`mycomm/mycomm-php`) plus a thin Laravel bridge (`mycomm/mycomm-laravel`) | Reach beyond Laravel without giving Laravel users a worse experience. |
| Distribution | Public repositories, Packagist, MIT | The norm for API clients: readable source, issues, pinned versions. |
| HTTP | PSR-18; Guzzle with a timeout when installed, else discovery | No hard dependency on one client. PSR-18 has no timeout setting, so the SDK builds Guzzle itself when it can. |

## Shape of the API

- **One client, both APIs.** A partner key uses the same `/v1` operations with a `MyComm-Account` header,
  so `Client::forAccount($id)` returns a client that sends it, and the partner operations live under
  `$client->partner`.
- **Names mirror the reference.** Operation id `contacts.identities.block` is
  `$client->contacts->identities->block()`; arguments and properties keep the API's `snake_case` names. A
  developer reading the API reference never has to translate.
- **Arguments are named, not arrays.** Required fields are required parameters. Nested objects accept an
  array or, where the spec names the object, a small input class.
- **`null` versus not sent.** `PATCH` treats `null` as "clear". An optional argument that may be `null`
  therefore defaults to `Omit::Value`; passing `null` sends `null`. Other optional arguments default to
  `null`, meaning not sent.
- **Results are typed, immutable models** with `raw()` for the payload as sent and `meta()` for the request
  id, rate-limit numbers and replay flag.
- **Forward compatibility.** The API adds fields, enum values, error codes and event types without a new
  version. Unknown fields stay in `raw()`; an enum-typed property holds the enum case or, for a value this
  version does not know, the plain string; unknown error codes fall back on the HTTP status; an unknown
  event type becomes `UnknownEvent`.

## Runtime behaviour

- **Idempotency.** Every POST carries an `Idempotency-Key` (a UUID unless the caller supplies one), reused on
  each retry of that call. The API then replays the first answer, so a retry cannot send a message twice.
- **Retries.** Two by default. Network errors and `5xx` are retried with jittered exponential backoff; `429`
  and `503` wait for `Retry-After`; `409 IDEMPOTENCY_IN_PROGRESS` waits a second. Nothing else is retried.
- **Writes always send a JSON object**, `{}` when the operation has no fields, because the API rejects a
  write without one. Empty objects are encoded as `{}`, never `[]`.
- **Errors** are exceptions by HTTP status, carrying the API's code, details and request id.
- **Webhooks.** `MyComm-Signature: t=<unix>,v1=<hex hmac-sha256 of "<t>.<raw body>">`, several `v1` values
  during a secret rotation, 300 s tolerance, constant-time comparison.

## How correctness is checked

1. **Generated tests over the whole surface.** For every operation the generator writes example arguments,
   a full example answer and the sparsest answer the spec allows. The suite calls each method against the
   fake and checks method, path, body rules and the hydrated result. The same is done for all 38 events.
2. **Hand-written tests** for the runtime: retries, idempotency, encoding, errors, paging, signatures, the
   fake, and a real Guzzle client through PSR-18.
3. **Generator tests** on a small fixture spec that has one of each shape.
4. **Static analysis** at PHPStan level 8, and a CI job that fails when the committed code differs from what
   the spec generates.
5. **A live script** (`tests/live/smoke.php`) that drives a real API end to end.

The first live run found what the offline suite could not: one field the API returns as `null` although
the spec says it never is (`WebhookConsentData.contact_id`). Such findings are recorded as corrections in
`generator/config.php` until the spec is fixed.

## Known limits

- Message `status`, `direction`, `type` and a few similar fields are plain strings because the spec
  describes their values in prose rather than as an enum. Turning them into enums in the spec will give the
  SDK enums on the next generation, as a minor release.
- `channels.create` and `channels.connectState` belong to channel types that are not enabled yet and have no
  success answer in the spec; they return the decoded JSON.
- Filters are plain strings, as on the wire (several values are comma-separated).

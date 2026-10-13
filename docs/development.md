# Working on the SDK

## What is written by hand and what is generated

| Path | |
|---|---|
| `src/Client.php`, `src/Http/`, `src/Exceptions/`, `src/Page.php`, `src/Model.php`, `src/Input.php`, `src/Webhooks/Webhook.php`, `src/Webhooks/UnknownEvent.php`, `src/Testing/` (except `Examples.php`) | Written by hand: transport, retries, errors, paging, webhook verification, the fake. |
| `src/Resources/`, `src/Models/`, `src/Enums/`, `src/Inputs/`, `src/Webhooks/Events/`, `src/Webhooks/Event.php`, `src/Webhooks/EventMap.php`, `src/Testing/Examples.php`, `tests/Generated/` | **Generated** from `spec/public.yaml` and `spec/partner.yaml`. Never edit: the next run overwrites it. |
| `generator/` | The generator and its `config.php`. |
| `spec/` | A copy of the backend's bundled OpenAPI documents; `spec/SOURCE` is the backend commit they come from. |

Every generated file starts with a comment that says so.

## Commands

Everything runs in a container, so the host needs only Docker.

```bash
./dev composer install
./dev check            # style, static analysis, tests
./dev test --filter=Webhook
./dev generate         # regenerate from spec/ and format
./dev fresh            # fail when the generated code is stale (CI runs the same check)
```

`MYCOMM_PHP_IMAGE=php:8.2-cli ./dev test` runs on the oldest supported PHP once dependencies are installed
for it (`composer config platform.php 8.2.0 && composer update` in a scratch copy).

## When the API changes

1. In the backend, change the fragments under `openapi/` and bundle them as usual. Merge.
2. Here: `./dev spec:sync ../mycomm-backend`, then `./dev generate`.
3. Read the diff. New endpoints, fields, enum values and events appear by themselves.
4. `./dev check`. The generated tests call every operation with example arguments and read two answers per
   operation (a full one and the sparsest one the spec allows), so a spec change that the generator cannot
   express fails here, with the operation's name.
5. Add a line to `CHANGELOG.md` and tag: a **minor** version for additions, a **major** one when a method,
   argument or property was removed or changed type.

The API only makes additive changes within `/v1`, so regenerating is normally a minor release.

## What the generator decides and what you decide

The generator follows fixed rules (`generator/src/`):

- `operationId` `a.b.c` becomes `$client->a->b->c()`. Ids in the path are the leading arguments, then
  required fields, then optional ones, then `limit`/`cursor`, then `requestOptions`.
- An optional field that may be `null` defaults to `Omit::Value` so that `null` can mean "clear it".
  Other optional fields default to `null`, meaning "not sent".
- A response of `{data, next_cursor}` becomes a `Page`; a `list` operation with a `cursor` also gets `all()`.
- A list of two or more string values becomes an enum; properties typed with it accept the enum or a
  string, because the API may add values.
- Timestamps become `DateTimeImmutable` in results and accept `DateTimeInterface|string` in arguments.

`generator/config.php` holds the few things the spec cannot say:

- `enums`: a name for a list of values that appears in several places, so they share one enum.
- `models`: a name for an answer the spec describes inline.
- `nullable`: a field the real API returns as `null` although the spec says it never is. Each entry is a
  spec bug: fix the backend spec and the generator will tell you to delete the entry.
- `multipart`: the method name of the upload variant of an operation that also takes JSON.

The generator stops with a message, instead of guessing, when two schemas want one class name, when lists
that share an enum name drift apart, or when two parameters of a method would share a name.

## Before a release

Run the live check against a test account (see the header of the script for what it does):

```bash
MYCOMM_API_KEY=mc_live_... MYCOMM_PARTNER_KEY=mc_partner_... \
MYCOMM_BASE_URL=https://staging.mycomm.cogneer.tech ./dev php tests/live/smoke.php
```

The script needs HTTPS, as the API does. A local `./server` stack of the backend serves plain HTTP and
answers `400 HTTPS is required` on `/v1`, so to run the script against one, serve the app a second time
inside its container through a router that marks requests as secure, and run the script in that container:

```php
<?php // router.php
$_SERVER['HTTPS'] = 'on';
return require '/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
```

```bash
# in the app container, from /var/www/html/public
PHP_CLI_SERVER_WORKERS=4 php -S 127.0.0.1:8998 /path/to/router.php
MYCOMM_BASE_URL=http://127.0.0.1:8998 MYCOMM_API_KEY=... php /path/to/sdk/tests/live/smoke.php
```

Then tag `vX.Y.Z` and push the tag; Packagist picks it up. Bump `MyComm\Version::SDK` in the same commit.

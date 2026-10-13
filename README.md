# MyComm PHP SDK

The official PHP client for the [MyComm](https://mycomm.cogneer.tech) messaging API: send and track messages,
manage contacts and conversations, receive webhooks, and (for partners) provision customer accounts.

- Every operation of the public API and the partner API, with typed arguments and typed results
- Retries that cannot double-send: every POST carries an `Idempotency-Key`
- Lists you can `foreach` over, across pages
- Webhook signature verification and a class per event
- A fake client for your own tests

Requires PHP 8.2 or later. Using Laravel? Install `mycomm/mycomm-laravel`
instead: it adds configuration, a facade and a webhook route on top of this package.

## Install

```bash
composer require mycomm/mycomm-php
```

The SDK talks HTTP through any [PSR-18](https://www.php-fig.org/psr/psr-18/) client. If your project has none yet:

```bash
composer require guzzlehttp/guzzle
```

## Quick start

```php
use MyComm\Client;

$mycomm = new Client(getenv('MYCOMM_API_KEY'));   // mc_live_...

$message = $mycomm->messages->send(
    to: '+923001234567',
    body: 'Your code is 4821',
);

echo $message->id;       // "01923f80-5c6d-7e7f-8a90-a1b2c3d4e5f6"
echo $message->status;   // "queued"
```

Create the key in the MyComm portal under **Developers → API keys**. Keep it on your server: never ship it in
a browser or a mobile app.

## How the SDK maps to the API

Each resource of the [API reference](https://mycomm.cogneer.tech/docs) is a property of the client and each
operation a method, named exactly like the operation id in the reference:

| Operation id | Call |
|---|---|
| `messages.send` | `$mycomm->messages->send(...)` |
| `contacts.upsertByIdentity` | `$mycomm->contacts->upsertByIdentity(...)` |
| `contacts.identities.block` | `$mycomm->contacts->identities->block($contactId, $identityId)` |
| `webhookEndpoints.rotateSecret` | `$mycomm->webhookEndpoints->rotateSecret($id)` |
| `partner.accounts.provision` | `$mycomm->partner->accounts->provision(...)` |

Arguments are named like the API's fields (`reference_id`, `send_after`), so the reference and your editor's
autocompletion agree. Ids in the path come first; everything else is best passed by name.

## Sending messages

```php
use MyComm\Enums\MessageCategory;

$message = $mycomm->messages->send(
    to: '+923001234567',
    body: 'Sale ends tonight. Reply STOP to opt out.',
    category: MessageCategory::Marketing,
    send_after: new DateTimeImmutable('today 18:00'),
    reference_id: 'campaign-7-user-991',
    metadata: ['user_id' => 991],
);

foreach ($message->warnings as $warning) {
    logger()->notice($warning->message);
}
```

Check what a send would do without sending it:

```php
$preview = $mycomm->messages->send(to: '+923001234567', body: $text, dry_run: true);

$preview->segments;      // how many SMS parts the text needs
$preview->hold_reason;   // why it would wait, if it would
```

Send up to 1,000 messages in one call. Items are accepted or rejected one by one:

```php
use MyComm\Inputs\SendBatchItem;

$batch = $mycomm->messages->sendBatch(
    messages: [
        new SendBatchItem(to: '+923001234567', body: 'Hi Ada'),
        new SendBatchItem(to: '+923331112222', body: 'Hi Grace'),
    ],
    defaults: ['category' => 'transactional'],
);

foreach ($batch->results as $result) {
    if ($result->error !== null) {
        report("Message {$result->index} was rejected: {$result->error->code}");
    }
}
```

Follow a message afterwards with `$mycomm->messages->show($id)`, or let MyComm tell you through
[webhooks](#webhooks).

## Lists and pagination

A list method returns one `Page`. Loop over it for the items of that page, or use `all()` to walk every page;
the next page is fetched only when the loop reaches it.

```php
$page = $mycomm->contacts->list(group_id: $groupId, limit: 100);

foreach ($page as $contact) {
    // the first 100
}

foreach ($mycomm->contacts->all(group_id: $groupId) as $contact) {
    // every contact of the group
}
```

`$page->next_cursor`, `$page->hasMore()` and `$page->next()` are there when you page by hand, and
`$page->autoPaging()` does what `all()` does for lists that are not named `list` (`$mycomm->conversations->messages($id)->autoPaging()`).

## Leaving a field out or clearing it

An update changes only the fields you pass. Where a field can be cleared, `null` clears it:

```php
$mycomm->contacts->update($id, first_name: 'Ada');           // email untouched
$mycomm->contacts->update($id, first_name: 'Ada', email: null);   // email cleared
```

## Errors

Every error answer of the API becomes an exception. Branch on `errorCode`, which is stable; the message is
English text for people and may change.

```php
use MyComm\Enums\ErrorCode;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\ValidationException;

try {
    $mycomm->messages->send(to: $number, body: $text, category: 'marketing');
} catch (ValidationException $e) {
    $e->fields();                 // ['to' => [['code' => 'INVALID_NUMBER', 'message' => '...']]]
} catch (ApiException $e) {
    if ($e->is(ErrorCode::RecipientOptedOut)) {
        // do not retry: the recipient unsubscribed
    }
    $e->status;                   // 422
    $e->errorCode;                // "RECIPIENT_OPTED_OUT"
    $e->details;                  // ['address' => '+923001234567', ...]
    $e->requestId;                // quote this to support
} catch (ConnectionException $e) {
    // MyComm could not be reached, even after the retries
}
```

| Exception | When |
|---|---|
| `BadRequestException` | 400: malformed request, invalid cursor |
| `AuthenticationException` | 401: key missing, unknown, revoked or expired |
| `PermissionDeniedException` | 403: ability, scope, IP allow-list, suspended account |
| `NotFoundException` | 404 |
| `ConflictException` | 409, for example `REFERENCE_ID_EXISTS` |
| `PayloadTooLargeException` | 413 |
| `UnprocessableException` | 422: a rule refuses the request |
| `ValidationException` | 422 `VALIDATION_FAILED` (extends `UnprocessableException`) |
| `RateLimitException` | 429, after the retries |
| `ServerException` | 5xx, after the retries |

All of them extend `ApiException`, which extends `MyCommException`, the base of everything the SDK throws.

## Retries and idempotency

The SDK retries a call twice (configurable) when the network fails, when MyComm answers `429` or `503`
(waiting as long as `Retry-After` asks) and on other `5xx` answers, with exponential backoff.

That is safe for sends because every POST carries an `Idempotency-Key`, and a retry repeats the same key:
MyComm then replays the first answer instead of sending a second message. Pass your own key to make a
whole operation of yours repeatable, for example across a queue job's attempts:

```php
use MyComm\RequestOptions;

$mycomm->messages->send(
    to: $order->phone,
    body: "Order {$order->number} has shipped.",
    requestOptions: new RequestOptions(idempotencyKey: "order-shipped-{$order->id}"),
);

$message->meta()->idempotentReplayed;   // true when this was a replay
```

## Webhooks

Verify each delivery with the signing secret of your webhook endpoint, then handle the typed event.
Always pass the **raw** request body.

```php
use MyComm\Exceptions\WebhookSignatureException;
use MyComm\Webhooks\Events\MessageDelivered;
use MyComm\Webhooks\Events\MessageFailed;
use MyComm\Webhooks\Events\MessageReceived;
use MyComm\Webhooks\Webhook;

$payload = file_get_contents('php://input');

try {
    $event = Webhook::constructEvent($payload, $_SERVER['HTTP_MYCOMM_SIGNATURE'] ?? null, $secret);
} catch (WebhookSignatureException) {
    http_response_code(400);
    exit;
}

match (true) {
    $event instanceof MessageDelivered => markDelivered($event->data->message->id),
    $event instanceof MessageFailed => markFailed($event->data->message->id, $event->data->message->failure?->code),
    $event instanceof MessageReceived => handleReply($event->data->message->from, $event->data->message->body),
    default => null,   // an event you do not handle, or one added to MyComm later
};

http_response_code(204);
```

Good to know:

- **Answer fast** with any 2xx and do the work in a queue. MyComm waits 10 seconds, then retries.
- **Deliveries can repeat and arrive out of order.** De-duplicate on `$event->id` and never move a message
  status backwards.
- **Rotating the secret:** during the overlap MyComm signs with both secrets, so verification keeps working
  whichever one you hold. To switch without a gap, pass both: `Webhook::constructEvent($payload, $header, [$new, $old])`.
- An event type this SDK version does not know arrives as `MyComm\Webhooks\UnknownEvent` with `data` as an
  array. Answer 2xx.

Create the endpoint in the portal or with the API. The secret is shown once:

```php
$endpoint = $mycomm->webhookEndpoints->create(
    url: 'https://example.com/webhooks/mycomm',
    events: ['message.delivered', 'message.failed', 'message.received'],
);

$endpoint->secret;
```

## Partners

A partner key (`mc_partner_...`) provisions managed accounts and then acts as them on the normal API:

```php
$partner = new Client(getenv('MYCOMM_PARTNER_KEY'));

$account = $partner->partner->accounts->provision(
    external_ref: 'erp-business-20418',
    name: 'Acme Traders',
    country_iso: 'PK',
    timezone: 'Asia/Karachi',
);

$acme = $partner->forAccount($account->id);    // every call now carries MyComm-Account
$acme->messages->send(to: '+923001234567', body: 'Welcome to Acme');

$usage = $partner->partner->usage->get(from: '2026-10-01', to: '2026-10-31');
```

Or mint a key for the customer's own integration: `$partner->partner->accountKeys->create($account->id, name: 'ERP')`.

## Uploading files

```php
use MyComm\FileUpload;

$media = $mycomm->media->upload('/path/to/photo.jpg');
$media = $mycomm->media->upload(FileUpload::fromString($bytes, 'photo.jpg', 'image/jpeg'));

$mycomm->messages->send(to: '+923001234567', media_ids: [$media->id]);
```

## Testing your code

`MyCommFake` gives you a real client that never touches the network. Every operation answers with the example
of the API reference until you say otherwise.

```php
use MyComm\Http\Request;
use MyComm\Testing\FakeResponse;
use MyComm\Testing\MyCommFake;

$fake = new MyCommFake;
$notifier = new OrderNotifier($fake->client());

$fake->respond('messages.send', ['status' => 'queued']);                 // your values over the example
$fake->respondOnce('messages.send', FakeResponse::error(422, 'RECIPIENT_OPTED_OUT'));

$notifier->orderShipped($order);

$fake->assertCalled('messages.send', fn (Request $request) => $request->json['to'] === $order->phone);
$fake->assertNotCalled('messages.sendBatch');
```

`FakeResponse` also has `page()`, `validationError()`, `noContent()`, `connectionError()` and `json()`.
To test your webhook receiver, `MyComm\Testing\FakeWebhook::make('message.delivered', secret: $secret)` gives
you a signed payload and its `MyComm-Signature` header.

## Configuration

```php
$mycomm = new Client(
    apiKey: getenv('MYCOMM_API_KEY'),
    baseUrl: 'https://mycomm.cogneer.tech',   // without /v1
    httpClient: $yourPsr18Client,              // optional
    maxRetries: 2,
    timeout: 30.0,                             // seconds; applies to the built-in Guzzle client
);
```

When you pass your own HTTP client, set its timeout yourself: PSR-18 has no setting for it.

## What every result carries

```php
$message->meta()->requestId;            // quote to support
$message->meta()->rateLimitRemaining;   // requests left in the window
$message->raw();                        // the JSON as MyComm sent it
```

MyComm adds fields and values without a new API version. The SDK is built for that: a field it does not know
yet is still in `raw()`, and an enum value it does not know arrives as a plain string instead of an enum case,
so `$channel->status === ChannelStatus::Active` keeps working and nothing throws.

## Versioning

The SDK follows [semantic versioning](https://semver.org). It is generated from the same OpenAPI documents
that the API is tested against; [CHANGELOG.md](CHANGELOG.md) lists what each release added.

## Contributing

Most of `src/` is generated. See [docs/development.md](docs/development.md) before changing anything.

## License

MIT. See [LICENSE](LICENSE).

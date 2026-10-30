<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use MyComm\Client;
use MyComm\Enums\HostedLinkPurpose;
use MyComm\Enums\MessageCategory;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\MyCommException;
use MyComm\Exceptions\NotFoundException;
use MyComm\FileUpload;
use MyComm\Models\CreatedHostedLink;
use MyComm\Models\PartnerLine;
use MyComm\Models\PartnerLineStatus;
use MyComm\Models\SendMessageDryRunResponse;
use MyComm\Models\SendMessageResponse;
use MyComm\RequestOptions;
use MyComm\Testing\Examples;
use MyComm\Testing\FakeResponse;
use MyComm\Testing\MyCommFake;

/**
 * A real Guzzle client that answers from a queue, to run the SDK through PSR-18 end to end.
 *
 * @param  list<GuzzleResponse|Throwable>  $answers
 * @param  array<int, array<string, mixed>>  $history
 */
function guzzle(array $answers, array &$history): GuzzleClient
{
    $stack = HandlerStack::create(new MockHandler($answers));
    $stack->push(Middleware::history($history));

    return new GuzzleClient(['handler' => $stack, 'http_errors' => false]);
}

it('refuses an empty API key', function () {
    expect(fn () => new Client(' '))->toThrow(MyCommException::class, 'The MyComm API key is empty.');
});

it('sends a message through a PSR-18 client', function () {
    $history = [];
    $client = new Client('mc_live_abc', 'https://mycomm.test/', guzzle([
        new GuzzleResponse(202, ['MyComm-Request-Id' => 'req-7', 'RateLimit-Remaining' => '599'], (string) json_encode(Examples::RESPONSES['messages.send']['body'])),
    ], $history));

    $message = $client->messages->send(
        to: '+923001234567',
        body: 'Your code is 4821',
        category: MessageCategory::Transactional,
        send_after: new DateTimeImmutable('2026-10-01T09:15:02Z'),
        metadata: ['order' => 42],
        requestOptions: new RequestOptions(idempotencyKey: 'order-42'),
    );

    /** @var GuzzleRequest $request */
    $request = $history[0]['request'];
    expect($request->getMethod())->toBe('POST')
        ->and((string) $request->getUri())->toBe('https://mycomm.test/v1/messages')
        ->and($request->getHeaderLine('Authorization'))->toBe('Bearer mc_live_abc')
        ->and($request->getHeaderLine('Content-Type'))->toBe('application/json')
        ->and($request->getHeaderLine('Idempotency-Key'))->toBe('order-42')
        ->and((string) $request->getBody())->toBe('{"to":"+923001234567","body":"Your code is 4821","category":"transactional","send_after":"2026-10-01T09:15:02Z","metadata":{"order":42}}')
        ->and($message)->toBeInstanceOf(SendMessageResponse::class)
        ->and($message->meta()?->requestId)->toBe('req-7')
        ->and($message->meta()?->rateLimitRemaining)->toBe(599);
});

it('returns the dry-run answer when the API says 200', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', FakeResponse::json(['dry_run' => true, 'id' => null, 'status' => null, 'segments' => 2, 'warnings' => []], 200));

    $preview = $fake->client()->messages->send(to: '+923001234567', body: 'Hi', dry_run: true);

    expect($preview)->toBeInstanceOf(SendMessageDryRunResponse::class)
        ->and($preview->segments)->toBe(2)
        ->and($preview->id)->toBeNull();
});

it('maps an error of the API through a PSR-18 client', function () {
    $history = [];
    $client = new Client('mc_live_abc', httpClient: guzzle([
        new GuzzleResponse(404, [], '{"error":{"code":"NOT_FOUND","message":"No such message.","details":{},"request_id":"req-1"}}'),
    ], $history));

    expect(fn () => $client->messages->show('missing'))->toThrow(NotFoundException::class, 'No such message.');
});

it('turns a transport failure into a connection exception after its retries', function () {
    $history = [];
    $refused = fn () => new ConnectException('Connection refused', new GuzzleRequest('GET', 'https://mycomm.test'));
    $client = new Client('mc_live_abc', httpClient: guzzle([$refused(), $refused()], $history), maxRetries: 1);

    expect(fn () => $client->account->get())->toThrow(ConnectionException::class, 'Could not reach MyComm: Connection refused');
    expect($history)->toHaveCount(2);
})->skip(fn () => getenv('MYCOMM_SKIP_SLOW') === '1', 'waits out one backoff');

test('sends_with_the_line_header_for_a_partner_key', function () {
    $fake = new MyCommFake('mc_partner_fake');
    $partner = $fake->client();

    $line = $partner->partner->lines->create(external_ref: 'erp-tenant-20418', name: 'Acme Traders');
    $acme = $partner->forLine($line->id);
    $acme->messages->send(to: '+923001234567', body: 'Hi');
    $acme->partner->usage->get(from: '2026-10-01', to: '2026-10-31');

    $create = $fake->calls('partner.lines.create')[0];
    $send = $fake->calls('messages.send')[0];
    expect($create->path)->toBe('/partner/v1/lines')
        ->and($create->header('MyComm-Line'))->toBeNull()
        ->and($create->header('Authorization'))->toBe('Bearer mc_partner_fake')
        ->and($send->header('MyComm-Line'))->toBe($line->id)
        ->and($send->header('MyComm-Account'))->toBeNull()
        ->and($fake->calls('partner.usage.get')[0]->header('MyComm-Line'))->toBeNull()
        ->and($fake->calls('partner.usage.get')[0]->query)->toBe(['from' => '2026-10-01', 'to' => '2026-10-31']);

    $preset = new MyCommFake('mc_partner_fake', lineId: 'line-1');
    $preset->client()->messages->list();
    expect($preset->calls('messages.list')[0]->header('MyComm-Line'))->toBe('line-1');
});

test('creates_a_partner_line_and_a_connect_link', function () {
    $fake = new MyCommFake('mc_partner_fake');
    $partner = $fake->client();

    $line = $partner->partner->lines->create(external_ref: 'erp-tenant-20418', name: 'Acme Traders', user_number: '+923001234567');
    $link = $partner->partner->lines->links->create(
        $line->id,
        purpose: HostedLinkPurpose::Connect,
        return_url: 'https://erp.example.com/settings/messaging',
        allowed_origins: ['https://erp.example.com'],
    );
    $status = $partner->partner->lines->status($line->id);

    $create = $fake->calls('partner.lines.create')[0];
    $mint = $fake->calls('partner.lines.links.create')[0];
    expect($line)->toBeInstanceOf(PartnerLine::class)
        ->and($create->method)->toBe('POST')
        ->and($create->json)->toBe(['external_ref' => 'erp-tenant-20418', 'name' => 'Acme Traders', 'user_number' => '+923001234567'])
        ->and($mint->method)->toBe('POST')
        ->and($mint->path)->toBe('/partner/v1/lines/'.$line->id.'/links')
        ->and($mint->json)->toBe(['purpose' => 'connect', 'return_url' => 'https://erp.example.com/settings/messaging', 'allowed_origins' => ['https://erp.example.com']])
        ->and($link)->toBeInstanceOf(CreatedHostedLink::class)
        ->and($link->url)->toBe(Examples::RESPONSES['partner.lines.links.create']['body']['url'])
        ->and($link->purpose)->toBe(HostedLinkPurpose::Connect)
        ->and($status)->toBeInstanceOf(PartnerLineStatus::class)
        ->and($fake->calls('partner.lines.status')[0]->path)->toBe('/partner/v1/lines/'.$line->id.'/status');
});

it('accepts a base URL with or without the version path', function (string $baseUrl) {
    $history = [];
    $client = new Client('mc_live_abc', $baseUrl, guzzle([new GuzzleResponse(200, [], (string) json_encode(Examples::RESPONSES['account.get']['body']))], $history));

    $client->account->get();

    expect((string) $history[0]['request']->getUri())->toBe('https://mycomm.test/v1/account');
})->with(['https://mycomm.test', 'https://mycomm.test/', 'https://mycomm.test/v1', 'https://mycomm.test/v1/', 'https://mycomm.test/partner/v1']);

it('escapes path arguments', function () {
    $fake = new MyCommFake;

    $fake->client()->contacts->identities->delete('a/b', 'c d');

    expect($fake->calls('contacts.identities.delete')[0]->path)->toBe('/v1/contacts/a%2Fb/identities/c%20d');
});

it('uploads a file from a path or from memory', function () {
    $fake = new MyCommFake;
    $path = tempnam(sys_get_temp_dir(), 'mycomm').'.txt';
    file_put_contents($path, 'hello');

    $fake->client()->media->upload($path);
    $fake->client()->media->upload(FileUpload::fromString('PNG', 'logo.png', 'image/png'));
    unlink($path);

    [$fromPath, $fromMemory] = $fake->calls('media.upload');
    expect($fromPath->multipart['file']->contents)->toBe('hello')
        ->and($fromPath->multipart['file']->filename)->toBe(basename($path))
        ->and($fromMemory->multipart['file']->contentType)->toBe('image/png')
        ->and($fromMemory->body)->toContain('filename="logo.png"');
    expect(fn () => $fake->client()->media->upload('/no/such/file'))->toThrow(MyCommException::class, 'cannot be read');
});

it('sends an import as JSON or as an upload', function () {
    $fake = new MyCommFake;

    $fake->client()->imports->create(type: 'phone_history', channel_id: '01923f80-5c6a-7b10-9c21-0d3e4f5a6b7c', options: ['days' => 30]);
    $fake->client()->imports->createFromFile(type: 'contacts_csv', file: FileUpload::fromString("phone\n", 'c.csv', 'text/csv'), options: '{"mapping":{"phone":"phone"}}');

    [$json, $upload] = $fake->calls('imports.create');
    expect($json->json)->toBe(['type' => 'phone_history', 'channel_id' => '01923f80-5c6a-7b10-9c21-0d3e4f5a6b7c', 'options' => ['days' => 30]])
        ->and($upload->multipart)->toHaveKeys(['type', 'file', 'options'])
        ->and($upload->header('Content-Type'))->toStartWith('multipart/form-data');
});

it('clears a field with null and leaves it alone when omitted', function () {
    $fake = new MyCommFake;

    $fake->client()->contacts->update('c-1', first_name: 'Ada', email: null);

    expect($fake->calls('contacts.update')[0]->json)->toBe(['first_name' => 'Ada', 'email' => null]);
});

<?php

declare(strict_types=1);

/*
 * Runs the SDK against a real MyComm API: every resource, uploads, paging, idempotent replays, error
 * mapping and the partner flow. Not part of the test suite: run it before a release, against a test
 * account (it creates contacts, groups, messages and a managed account, and tidies up most of them).
 *
 *   MYCOMM_API_KEY=mc_live_... MYCOMM_PARTNER_KEY=mc_partner_... MYCOMM_BASE_URL=https://staging.mycomm.cogneer.tech \
 *       php tests/live/smoke.php
 *
 * The account needs one connected channel; nothing reaches a handset unless its phone is online, and the
 * one real message is cancelled right away. MYCOMM_PARTNER_KEY is optional: without it the partner steps
 * are skipped. Exits non-zero when a step fails.
 */

require dirname(__DIR__, 2).'/vendor/autoload.php';

use MyComm\Client;
use MyComm\Enums\AccountStatus;
use MyComm\Enums\ContactFieldType;
use MyComm\Enums\ExportStatus;
use MyComm\Enums\ExportType;
use MyComm\Enums\MessageCategory;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\AuthenticationException;
use MyComm\Exceptions\BadRequestException;
use MyComm\Exceptions\ConflictException;
use MyComm\Exceptions\NotFoundException;
use MyComm\Exceptions\PermissionDeniedException;
use MyComm\Exceptions\ValidationException;
use MyComm\FileUpload;
use MyComm\Inputs\ContactUpsert;
use MyComm\Inputs\SendBatchItem;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

$apiKey = getenv('MYCOMM_API_KEY') ?: exit("Set MYCOMM_API_KEY.\n");
$partnerKey = getenv('MYCOMM_PARTNER_KEY') ?: null;
$base = getenv('MYCOMM_BASE_URL') ?: Client::DEFAULT_BASE_URL;
$mycomm = new Client($apiKey, $base, maxRetries: 0);

$results = ['ok' => 0, 'api' => [], 'sdk' => []];

function step(string $name, Closure $fn): mixed
{
    global $results;
    try {
        $value = $fn();
        $results['ok']++;
        echo "  ok    {$name}\n";

        return $value;
    } catch (ApiException $e) {
        $results['api'][] = $name;
        echo "  API   {$name}: {$e->status} {$e->errorCode} {$e->getMessage()} ".json_encode($e->details)."\n";
    } catch (Throwable $e) {
        $results['sdk'][] = $name;
        echo "  SDK!  {$name}: ".$e::class.": {$e->getMessage()} @ ".basename($e->getFile()).':'.$e->getLine()."\n";
    }

    return null;
}

$GLOBALS['run'] = random_int(1000, 9999);

function num(string $suffix): string
{
    return '+92300'.$GLOBALS['run'].'0'.$suffix;
}

function check(bool $condition, string $what): void
{
    if (! $condition) {
        throw new RuntimeException("expectation failed: {$what}");
    }
}

function expectThrows(string $class, Closure $fn, ?string $code = null): ApiException
{
    try {
        $fn();
    } catch (ApiException $e) {
        check($e instanceof $class, "expected {$class}, got ".$e::class." ({$e->status} {$e->errorCode})");
        check($code === null || $e->errorCode === $code, "expected code {$code}, got {$e->errorCode}");
        check($e->requestId !== null, 'error carries a request id');

        return $e;
    }
    throw new RuntimeException("expected {$class}, nothing was thrown");
}

echo "account, channels, devices\n";
$account = step('account.get', function () use ($mycomm) {
    $account = $mycomm->account->get();
    check($account->meta()?->requestId !== null && $account->meta()->rateLimitLimit !== null, 'meta headers');

    return $account;
});
step('account.update', fn () => check($mycomm->account->update(name: (string) $account?->name)->id === $account?->id, 'same account'));
$keys = [
    'channel_id' => step('find a channel', fn () => $mycomm->channels->list(limit: 1)->data[0]->id ?? throw new RuntimeException('the account has no channel')),
    'device_id' => step('find a device', fn () => $mycomm->devices->list(limit: 1)->data[0]->id ?? throw new RuntimeException('the account has no device')),
];
$channel = step('channels.get', fn () => $mycomm->channels->get($keys['channel_id']));
step('channels.list', fn () => check(count($mycomm->channels->list()) >= 1, 'has a channel'));
step('channels.all', fn () => check(iterator_count($mycomm->channels->all()) >= 1, 'iterates'));
step('channels.types', fn () => check($mycomm->channels->types() instanceof Page, 'page'));
step('channels.pause+resume', function () use ($mycomm, $keys) {
    $mycomm->channels->pause($keys['channel_id']);
    $mycomm->channels->resume($keys['channel_id']);
});
step('channels.update', fn () => $mycomm->channels->update($keys['channel_id'], name: (string) $channel?->name));
step('devices.list', fn () => check(count($mycomm->devices->list()) >= 1, 'has a device'));
step('devices.get', fn () => $mycomm->devices->get($keys['device_id']));
step('devices.sims.list', fn () => $mycomm->devices->sims->list($keys['device_id']));
step('devices.update', fn () => $mycomm->devices->update($keys['device_id'], name: $mycomm->devices->get($keys['device_id'])->name));
$inboxes = step('inboxes.list', fn () => $mycomm->inboxes->list());
$inboxId = $inboxes?->data[0]->id ?? null;
step('inboxes.get', fn () => $mycomm->inboxes->get((string) $inboxId));
step('pairingCodes create/get/delete', function () use ($mycomm) {
    $code = $mycomm->pairingCodes->create(device_name: 'Spare phone');
    $mycomm->pairingCodes->get($code->id);
    $mycomm->pairingCodes->delete($code->id);
});

echo "contacts, groups, fields\n";
$contact = step('contacts.create', fn () => $mycomm->contacts->create(
    identities: [['type' => 'phone', 'address' => num('01')]],
    first_name: 'Ada', last_name: 'Lovelace'.$GLOBALS['run'], email: 'ada@example.com',
));
step('contacts.show', fn () => check($mycomm->contacts->show($contact->id)->first_name === 'Ada', 'first name'));
step('contacts.update clears with null', function () use ($mycomm, $contact) {
    $updated = $mycomm->contacts->update($contact->id, timezone: 'Asia/Karachi', email: null);
    check($updated->timezone === 'Asia/Karachi' && $updated->email === null && $updated->first_name === 'Ada', 'null clears, omitted stays');
});
step('contacts.upsertByIdentity', function () use ($mycomm) {
    $one = $mycomm->contacts->upsertByIdentity(identity: ['type' => 'phone', 'address' => num('02')], first_name: 'Grace');
    $two = $mycomm->contacts->upsertByIdentity(identity: ['type' => 'phone', 'address' => num('02')], first_name: 'Grace H.');
    check($one->id === $two->id && $two->first_name === 'Grace H.', 'same contact updated');
});
step('contacts.bulkUpsert', function () use ($mycomm) {
    $bulk = $mycomm->contacts->bulkUpsert(contacts: [
        new ContactUpsert(identity: ['type' => 'phone', 'address' => num('03')], first_name: 'Linus'),
        ['identity' => ['type' => 'phone', 'address' => 'not-a-number']],
    ]);
    check(count($bulk->results) === 2, 'two results');
});
step('contacts.list paging', function () use ($mycomm) {
    $page = $mycomm->contacts->list(limit: 1);
    check(count($page) === 1 && $page->hasMore(), 'one per page, more follow');
    $ids = [];
    foreach ($mycomm->contacts->all(limit: 1) as $c) {
        $ids[] = $c->id;
    }
    check(count($ids) >= 3 && count($ids) === count(array_unique($ids)), 'walked '.count($ids).' distinct contacts across pages');
});
step('contacts.list filters', fn () => check(count($mycomm->contacts->list(q: 'Lovelace'.$GLOBALS['run'], created_after: new DateTimeImmutable('-1 hour'), sort: '-created_at')) === 1, 'q + created_after'));
step('contacts.identities create/update/block/unblock/delete', function () use ($mycomm, $contact) {
    $identity = $mycomm->contacts->identities->create($contact->id, type: 'phone', address: num('09'));
    $mycomm->contacts->identities->update($contact->id, $identity->id, is_primary: true);
    $mycomm->contacts->identities->block($contact->id, $identity->id);
    $mycomm->contacts->identities->unblock($contact->id, $identity->id);
});
step('contacts.conversations', fn () => $mycomm->contacts->conversations($contact->id));
$group = step('groups.create', fn () => $mycomm->groups->create(name: 'VIP '.bin2hex(random_bytes(3)), description: 'Top customers'));
step('groups.update clears description', fn () => check($mycomm->groups->update($group->id, description: null)->description === null, 'cleared'));
step('groups.members add/list/remove', function () use ($mycomm, $group, $contact) {
    check($mycomm->groups->members->add($group->id, contact_ids: [$contact->id])->added === 1, 'added');
    check(count($mycomm->groups->members->list($group->id)) === 1, 'listed');
    check(iterator_count($mycomm->groups->members->all($group->id)) === 1, 'all()');
    check($mycomm->groups->members->remove($group->id, contact_ids: [$contact->id])->removed === 1, 'removed');
});
step('groups.show/list/delete', function () use ($mycomm, $group) {
    $mycomm->groups->show($group->id);
    $mycomm->groups->list(q: 'VIP');
    $mycomm->groups->delete($group->id);
    expectThrows(NotFoundException::class, fn () => $mycomm->groups->show($group->id), 'NOT_FOUND');
});
step('contactFields create/list/update/delete', function () use ($mycomm) {
    $field = $mycomm->contactFields->create(key: 'tier_'.bin2hex(random_bytes(2)), label: 'Tier', type: ContactFieldType::Select, options: ['gold', 'silver']);
    $mycomm->contactFields->list();
    $mycomm->contactFields->update($field->id, label: 'Customer tier');
    $mycomm->contactFields->delete($field->id);
});
step('smartLists preview/create/count/members/delete', function () use ($mycomm) {
    $filter = ['match' => 'all', 'conditions' => [['field' => 'first_name', 'op' => 'contains', 'value' => 'Ada']]];
    $mycomm->smartLists->preview(filter: $filter);
    $list = $mycomm->smartLists->create(name: 'Adas '.bin2hex(random_bytes(2)), filter: $filter);
    $mycomm->smartLists->count($list->id);
    $mycomm->smartLists->members->list($list->id);
    $mycomm->smartLists->delete($list->id);
});

echo "messages\n";
step('messages.send dry run', function () use ($mycomm, $inboxId) {
    $preview = $mycomm->messages->send(inbox_id: $inboxId, to: num('01'), body: 'Dry run from the SDK', category: MessageCategory::Transactional, dry_run: true);
    check($preview instanceof Models\SendMessageDryRunResponse, 'dry-run model, got '.$preview::class);
    check($preview->id === null && $preview->segments === 1, 'no id, one segment');
});
$message = step('messages.send', function () use ($mycomm, $inboxId) {
    $message = $mycomm->messages->send(
        inbox_id: $inboxId, to: num('01'), body: 'Hello from the PHP SDK — سلام', category: MessageCategory::Transactional,
        reference_id: 'smoke-'.bin2hex(random_bytes(4)), metadata: ['order' => 42, 'vip' => true],
    );
    check($message instanceof Models\SendMessageResponse, 'accepted model, got '.$message::class);
    check($message->meta()?->status === 202, 'status 202');
    check($message->created_at instanceof DateTimeImmutable, 'created_at is a date');
    check(($message->metadata['order'] ?? null) === 42 && ($message->metadata['vip'] ?? null) === true, 'metadata echoed: '.json_encode($message->metadata));
    echo '        metadata='.json_encode($message->metadata)."\n";
    echo "        status={$message->status} hold_reason=".json_encode($message->hold_reason)." encoding={$message->encoding} segments={$message->segments}\n";

    return $message;
});
step('idempotent replay', function () use ($mycomm, $inboxId) {
    $options = new RequestOptions(idempotencyKey: 'smoke-'.bin2hex(random_bytes(6)));
    $first = $mycomm->messages->send(inbox_id: $inboxId, to: num('01'), body: 'Only once', category: 'transactional', requestOptions: $options);
    $second = $mycomm->messages->send(inbox_id: $inboxId, to: num('01'), body: 'Only once', category: 'transactional', requestOptions: $options);
    check($first->id === $second->id, 'same message');
    check($first->meta()?->idempotentReplayed === false && $second->meta()?->idempotentReplayed === true, 'second is a replay');
    expectThrows(ConflictException::class, fn () => $mycomm->messages->send(inbox_id: $inboxId, to: num('01'), body: 'Different body', category: 'transactional', requestOptions: $options), 'IDEMPOTENCY_KEY_REUSED');
});
step('reference_id conflict', function () use ($mycomm, $message, $inboxId) {
    $e = expectThrows(ConflictException::class, fn () => $mycomm->messages->send(inbox_id: $inboxId, to: num('01'), body: 'Again', category: 'transactional', reference_id: $message->reference_id), 'REFERENCE_ID_EXISTS');
    check(($e->details['message_id'] ?? null) === $message->id, 'details name the existing message');
});
step('validation error', function () use ($mycomm, $inboxId) {
    $e = expectThrows(ApiException::class, fn () => $mycomm->messages->send(inbox_id: $inboxId, to: num('01'), category: 'transactional', metadata: ['nested' => ['a' => 1]]));
    check($e instanceof ValidationException && $e->fields() !== [], 'validation exception with fields: '.$e::class.' '.$e->errorCode);
});
step('messages.sendBatch', function () use ($mycomm, $inboxId) {
    $batch = $mycomm->messages->sendBatch(
        messages: [new SendBatchItem(to: num('02'), body: 'Batch one'), ['to' => 'nonsense', 'body' => 'Batch two']],
        defaults: ['category' => 'transactional', 'inbox_id' => $inboxId],
    );
    check($batch->summary->accepted === 1 && $batch->summary->rejected === 1, 'one accepted, one rejected');
    check($batch->results[0]->message instanceof Models\Message, 'accepted item is a Message');
    check($batch->results[1]->error !== null && $batch->results[1]->message === null, 'rejected item has an error: '.json_encode($batch->results[1]->raw()));
});
step('messages.sendBatch dry run', function () use ($mycomm, $inboxId) {
    $batch = $mycomm->messages->sendBatch(messages: [['inbox_id' => $inboxId, 'to' => num('02'), 'body' => 'Preview', 'category' => 'transactional']], dry_run: true);
    check($batch->results[0]->message instanceof Models\SendBatchPreview, 'preview model, got '.get_debug_type($batch->results[0]->message));
});
step('messages.show/list/events', function () use ($mycomm, $message) {
    check($mycomm->messages->show($message->id)->id === $message->id, 'show');
    check(count($mycomm->messages->list(reference_id: $message->reference_id)) === 1, 'filter by reference_id');
    check($mycomm->messages->events($message->id) instanceof Page, 'events');
    check(iterator_count($mycomm->messages->all(limit: 2, direction: 'outbound')) >= 3, 'all() across pages');
});
step('messages.cancel', fn () => $mycomm->messages->cancel($message->id));
step('conversations list/show/messages/read/archive', function () use ($mycomm) {
    $conversations = $mycomm->conversations->list();
    check(count($conversations) >= 1, 'has a conversation');
    $id = $conversations->data[0]->id;
    $mycomm->conversations->show($id);
    check(count($mycomm->conversations->messages($id)) >= 1, 'messages');
    $mycomm->conversations->read($id);
    $mycomm->conversations->unread($id);
    $mycomm->conversations->archive($id);
    $mycomm->conversations->unarchive($id);
});
step('conversations.reply', function () use ($mycomm) {
    $id = $mycomm->conversations->list()->data[0]->id;
    check($mycomm->conversations->reply($id, body: 'A reply', category: 'transactional') instanceof Models\SendMessageResponse, 'reply accepted');
});

echo "uploads\n";
step('media.upload + get (multipart)', function () use ($mycomm) {
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    $media = $mycomm->media->upload(FileUpload::fromString($png, 'pixel.png', 'image/png'));
    check($media->mime === 'image/png' && $media->size_bytes === strlen($png), 'stored as sent');
    $mycomm->media->get($media->id);
});
step('imports.createFromFile (multipart) + get/list', function () use ($mycomm) {
    $csv = "phone,first_name\n".num('20').",Csv One\n".num('21').",Csv Two\n";
    $import = $mycomm->imports->createFromFile(
        type: 'contacts_csv',
        file: FileUpload::fromString($csv, 'contacts.csv', 'text/csv'),
        options: (string) json_encode(['mapping' => ['phone' => 'phone', 'first_name' => 'first_name'], 'consent_assertion' => ['asserted' => true, 'source' => 'smoke test']]),
    );
    $mycomm->imports->get($import->id);
    $mycomm->imports->list();
});
step('exports create/get/list', function () use ($mycomm) {
    $export = $mycomm->exports->create(type: ExportType::ContactsCsv);
    check($export->type === ExportType::ContactsCsv && $export->status instanceof ExportStatus, 'enums hydrate');
    $mycomm->exports->create(type: 'messages_csv', params: ['created_after' => '2026-10-01T00:00:00Z', 'created_before' => '2026-10-31T00:00:00Z']);
    $mycomm->exports->get($export->id);
    $mycomm->exports->list();
});

echo "webhooks, keys, compliance, stats\n";
step('webhookEndpoints lifecycle', function () use ($mycomm) {
    $endpoint = $mycomm->webhookEndpoints->create(url: 'https://example.com/hooks/mycomm', events: ['message.sent', 'message.failed'], description: 'Smoke');
    check(strlen($endpoint->secret) >= 32, 'secret shown once');
    $mycomm->webhookEndpoints->get($endpoint->id);
    $mycomm->webhookEndpoints->list();
    $mycomm->webhookEndpoints->update($endpoint->id, events: null, description: null);
    $rotated = $mycomm->webhookEndpoints->rotateSecret($endpoint->id);
    check($rotated->secret !== $endpoint->secret, 'new secret');
    $test = $mycomm->webhookEndpoints->test($endpoint->id);
    echo '        test: success='.json_encode($test->success).' status='.json_encode($test->status_code).' error='.json_encode($test->error)."\n";
    $deliveries = $mycomm->webhookDeliveries->list($endpoint->id);
    if (count($deliveries) > 0) {
        $mycomm->webhookDeliveries->get($deliveries->data[0]->id);
    }
    $mycomm->webhookDeliveries->bulkReplay($endpoint->id);
    $mycomm->webhookEndpoints->delete($endpoint->id);
});
step('apiKeys create/list/revoke', function () use ($mycomm, $base) {
    $created = $mycomm->apiKeys->create(name: 'Smoke key', abilities: ['messages:read'], expires_at: new DateTimeImmutable('+1 day'));
    check(str_starts_with($created->token, 'mc_live_'), 'token shown once');
    (new Client($created->token, $base, maxRetries: 0))->messages->list(limit: 1);
    $e = expectThrows(PermissionDeniedException::class, fn () => (new Client($created->token, $base, maxRetries: 0))->contacts->list(), 'ABILITY_MISSING');
    $mycomm->apiKeys->list();
    $mycomm->apiKeys->revoke($created->id);
    expectThrows(AuthenticationException::class, fn () => (new Client($created->token, $base, maxRetries: 0))->messages->list(), 'UNAUTHENTICATED');
});
step('unknown key is rejected', fn () => expectThrows(AuthenticationException::class, fn () => (new Client('mc_live_'.str_repeat('0', 40), $base, maxRetries: 0))->account->get(), 'UNAUTHENTICATED'));
step('suppressions create/check/list/remove', function () use ($mycomm) {
    $suppression = $mycomm->suppressions->create(address: num('99'), reason: 'api', note: 'Asked by phone');
    check($mycomm->suppressions->check(address: num('99'))->suppressed === true, 'suppressed');
    $mycomm->suppressions->list();
    $mycomm->suppressions->remove($suppression->id, consent_note: 'Opted back in by phone');
});
step('consentEvents.list', fn () => $mycomm->consentEvents->list());
step('stats summary/daily', function () use ($mycomm) {
    $today = (new DateTimeImmutable('now', new DateTimeZone('Asia/Karachi')))->format('Y-m-d');
    $summary = $mycomm->stats->summary(from: $today, to: $today);
    check($summary->totals->sent_messages >= 0, 'totals');
    $mycomm->stats->daily(from: $today, to: $today);
});
step('contacts.delete', fn () => $mycomm->contacts->delete($contact->id));

if ($partnerKey !== null) {
    echo "partner\n";
    $partner = new Client($partnerKey, $base, maxRetries: 0);
    $managed = step('partner.accounts.provision', function () use ($partner) {
        $ref = 'erp-'.bin2hex(random_bytes(4));
        $account = $partner->partner->accounts->provision(external_ref: $ref, name: 'Acme Traders', country_iso: 'PK', timezone: 'Asia/Karachi');
        $again = $partner->partner->accounts->provision(external_ref: $ref, name: 'Acme Traders', country_iso: 'PK', timezone: 'Asia/Karachi');
        check($account->id === $again->id, 'provisioning is idempotent by external_ref');

        return $account;
    });
    step('partner.accounts get/list/update', function () use ($partner, $managed) {
        $partner->partner->accounts->get($managed->id);
        check(count($partner->partner->accounts->list(external_ref: $managed->external_ref)) === 1, 'filter by external_ref');
        check(iterator_count($partner->partner->accounts->all()) >= 1, 'all()');
        check($partner->partner->accounts->update($managed->id, name: 'Acme Traders Ltd')->name === 'Acme Traders Ltd', 'renamed');
    });
    step('acting as the managed account', function () use ($partner, $managed) {
        $acme = $partner->forAccount($managed->id);
        check($acme->account->get()->id === $managed->id, 'account.get answers for the managed account');
        $c = $acme->contacts->create(identities: [['type' => 'phone', 'address' => num('77')]], first_name: 'Managed');
        check($acme->contacts->show($c->id)->first_name === 'Managed', 'contact lives in the managed account');
        expectThrows(BadRequestException::class, fn () => $partner->account->get(), 'ACCOUNT_CONTEXT_REQUIRED');
    });
    step('partner.accountKeys create/list/revoke', function () use ($partner, $managed, $base) {
        $key = $partner->partner->accountKeys->create($managed->id, name: 'ERP integration');
        check((new Client($key->token, $base, maxRetries: 0))->account->get()->id === $managed->id, 'minted key works for the managed account');
        check(count($partner->partner->accountKeys->list($managed->id)) >= 1, 'listed');
        $partner->partner->accountKeys->revoke($managed->id, $key->id);
    });
    step('partner.usage.get', function () use ($partner) {
        $today = (new DateTimeImmutable('now', new DateTimeZone('Asia/Karachi')))->format('Y-m-d');
        $partner->partner->usage->get(from: $today, to: $today);
    });
    step('partner.accounts suspend/reactivate/delete', function () use ($partner, $managed) {
        check($partner->partner->accounts->suspend($managed->id, reason: 'Subscription lapsed')->status === AccountStatus::Suspended, 'suspended');
        $partner->partner->accounts->reactivate($managed->id);
        $deleted = $partner->partner->accounts->delete($managed->id);
        check($deleted->status === AccountStatus::PendingDeletion, 'scheduled for deletion');
    });
}

echo "\n{$results['ok']} ok, ".count($results['api']).' refused by the API, '.count($results['sdk'])." SDK failures\n";
if ($results['api'] !== []) {
    echo 'API refusals: '.implode('; ', $results['api'])."\n";
}
if ($results['sdk'] !== []) {
    echo 'SDK failures: '.implode('; ', $results['sdk'])."\n";
}

exit($results['api'] === [] && $results['sdk'] === [] ? 0 : 1);

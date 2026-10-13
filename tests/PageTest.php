<?php

declare(strict_types=1);

use MyComm\Models\Contact;
use MyComm\Testing\Examples;
use MyComm\Testing\FakeResponse;
use MyComm\Testing\MyCommFake;

function contactPayload(string $id): array
{
    return ['id' => $id] + Examples::RESPONSES['contacts.show']['body'];
}

it('iterates one page and knows whether more follow', function () {
    $fake = new MyCommFake;
    $fake->respond('contacts.list', FakeResponse::page([contactPayload('a'), contactPayload('b')], 'cursor-2'));

    $page = $fake->client()->contacts->list(limit: 2);

    expect($page)->toHaveCount(2)
        ->and($page->next_cursor)->toBe('cursor-2')
        ->and($page->hasMore())->toBeTrue()
        ->and(array_map(static fn (Contact $contact): string => $contact->id, iterator_to_array($page)))->toBe(['a', 'b']);
});

it('follows the cursor with the same filters through all()', function () {
    $fake = new MyCommFake;
    $fake->respondOnce('contacts.list', FakeResponse::page([contactPayload('a'), contactPayload('b')], 'cursor-2'))
        ->respondOnce('contacts.list', FakeResponse::page([contactPayload('c')], 'cursor-3'))
        ->respondOnce('contacts.list', FakeResponse::page([], null));

    $ids = [];
    foreach ($fake->client()->contacts->all(group_id: 'g-1', limit: 2) as $contact) {
        $ids[] = $contact->id;
    }

    $calls = $fake->calls('contacts.list');
    expect($ids)->toBe(['a', 'b', 'c'])
        ->and($calls)->toHaveCount(3)
        ->and($calls[0]->query)->toBe(['group_id' => 'g-1', 'limit' => '2'])
        ->and($calls[1]->query)->toBe(['group_id' => 'g-1', 'limit' => '2', 'cursor' => 'cursor-2'])
        ->and($calls[2]->query)->toBe(['group_id' => 'g-1', 'limit' => '2', 'cursor' => 'cursor-3']);
});

it('fetches lazily: a loop that stops early makes no further call', function () {
    $fake = new MyCommFake;
    $fake->respond('contacts.list', FakeResponse::page([contactPayload('a')], 'more'));

    foreach ($fake->client()->contacts->all() as $contact) {
        break;
    }

    $fake->assertCalledTimes('contacts.list', 1);
});

it('pages a nested list with its path argument', function () {
    $fake = new MyCommFake;
    $fake->respondOnce('groups.members.list', FakeResponse::page([contactPayload('a')], 'n'))
        ->respondOnce('groups.members.list', FakeResponse::page([contactPayload('b')], null));

    $ids = array_map(static fn (Contact $contact): string => $contact->id, iterator_to_array($fake->client()->groups->members->all('g-9'), false));

    expect($ids)->toBe(['a', 'b'])
        ->and($fake->calls('groups.members.list')[1]->path)->toBe('/v1/groups/g-9/members');
});

it('returns a list without a cursor as a page that has no next', function () {
    $fake = new MyCommFake;

    $page = $fake->client()->messages->events('m-1');

    expect($page->hasMore())->toBeFalse()
        ->and($page->next())->toBeNull()
        ->and(iterator_to_array($page->autoPaging(), false))->toHaveCount(1);
});

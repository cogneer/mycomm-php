<?php

declare(strict_types=1);

use MyComm\Generator\Emitter;
use MyComm\Generator\Examples;
use MyComm\Generator\Names;
use MyComm\Generator\Operations;
use MyComm\Generator\Spec;
use MyComm\Generator\Types;

/*
 * The rules of the generator, on a small spec that has one of each shape the real ones use.
 */

function miniSpec(): Spec
{
    return new Spec('mini', __DIR__.'/fixtures/mini.yaml');
}

/**
 * @return array<string, string> Property => declared type, of the Pet model.
 */
function petTypes(Types $types): array
{
    $types->output(miniSpec(), ['$ref' => '#/components/schemas/Pet'], 'Pet', 'Pet');
    $declared = [];
    foreach ($types->models['Pet']['properties'] as $name => $property) {
        $type = clone $property['type'];
        $type->nullable = $type->nullable || ! $property['required'];
        $declared[$name] = $type->declaration();
    }

    return $declared;
}

/**
 * The source of the resource class with the mini spec's operations of one resource path.
 */
function petResource(string $path = 'pets'): string
{
    $operations = (new Operations(new Types))->read(miniSpec());
    $own = array_values(array_filter($operations, static fn (array $operation): bool => implode('.', $operation['resource']) === $path));

    return (new Emitter)->resource(explode('.', $path), $own, []);
}

it('maps the schemas of an answer to PHP types', function () {
    expect(petTypes(new Types))->toBe([
        'id' => 'string',
        'kind' => 'Enums\PetKind|string',
        'hours' => '?Models\Hours',                              // a named object that allows null
        'born_at' => '?DateTimeImmutable',
        'tags' => 'array',
        'owner' => 'Models\PetOwner',                            // an inline object gets a class of its own
        'status' => 'string',                                    // one allowed value is not an enum
        'score' => 'int',                                        // nor is a list of numbers
        'mood' => 'Enums\PetMood|string|null',
        'home' => 'Models\Address',                              // allOf with one member is that member
        'twin' => 'Models\Address|Models\Preview|array|null',    // objects without a discriminator
        'limits' => 'array',
        'sold_to' => 'string',
        'nickname' => '?string',                                 // not required
    ]);
});

it('collects the enums and nested models it met', function () {
    $types = new Types;
    petTypes($types);

    expect(array_keys($types->enums))->toBe(['PetKind', 'PetMood'])
        ->and($types->enums['PetMood']['values'])->toBe(['happy', 'sad'])
        ->and(array_keys($types->models))->toEqualCanonicalizing(['Pet', 'Hours', 'PetOwner', 'Address', 'Preview']);
});

it('names an enum from the config and refuses lists that drifted apart', function () {
    $types = new Types(['Pet.kind' => 'Species', 'Pet.mood' => 'Species']);

    expect(fn () => petTypes($types))->toThrow(RuntimeException::class, 'Enum Species at Pet.mood has other values');
});

it('applies a correction and reports one the spec no longer needs', function () {
    expect(petTypes(new Types(nullable: ['Pet.sold_to' => 'sold to nobody yet']))['sold_to'])->toBe('?string');

    expect(fn () => petTypes(new Types(nullable: ['Pet.born_at' => 'already nullable'])))
        ->toThrow(RuntimeException::class, 'The spec now allows null for Pet.born_at');
});

it('orders the arguments and tells null from omitted', function () {
    $code = petResource();

    expect($code)->toContain(<<<'PHP'
    public function create(
        string $name,
        Enums\CreatePetKind|string $kind,
        string|Omit|null $nickname = Omit::Value,
        DateTimeInterface|string|null $born_at = null,
        Inputs\Address|array|null $home = null,
        ?array $metadata = null,
        ?array $owner = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Preview|Models\Pet {
PHP)
        ->toContain("'nickname' => \$nickname,")
        ->toContain("'born_at' => \$born_at ?? Omit::Value,")
        ->toContain("'metadata' => Params::object(\$metadata ?? Omit::Value),")
        ->toContain('@param  array{name: string, mood?: string}|null  $owner')
        ->toContain("200 => Models\Preview::fromArray(\$response->json(), \$response->meta()),\n            default => Models\Pet::fromArray(");
});

it('turns a list into a page with all(), filters before the paging pair', function () {
    $code = petResource();

    expect($code)->toContain(<<<'PHP'
    public function list(
        ?string $kind = null,
        DateTimeInterface|string|null $born_after = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
PHP)
        ->toContain('@param  ?string  $kind  One of: `cat`, `dog`.')
        ->toContain('@return Page<Models\Pet>')
        ->toContain('fn (string $next): Page => $this->list(kind: $kind, born_after: $born_after, limit: $limit, cursor: $next, requestOptions: $requestOptions)')
        ->toContain('public function all(')
        ->toContain('return $this->list(kind: $kind, born_after: $born_after, limit: $limit, requestOptions: $requestOptions)->autoPaging();');
});

it('returns null for an answer without a body and prefixes the path', function () {
    $code = petResource();

    expect($code)->toContain('): ?Models\PetsDeleteResponse {')
        ->toContain("if (\$response->body === '') {\n            return null;")
        ->toContain("'/v9/pets/'.rawurlencode(\$id)");
});

it('nests a resource and escapes every path argument', function () {
    $code = petResource('pets.photos');

    expect($code)->toContain('namespace MyComm\Resources\Pets;')
        ->toContain('final class Photos')
        ->toContain("public function tag(\n        string \$id,\n        string \$photo_id,\n        ?RequestOptions \$requestOptions = null,\n    ): void {")
        ->toContain("'/v9/pets/'.rawurlencode(\$id).'/photos/'.rawurlencode(\$photo_id).'/tags'");
});

it('builds a full and a sparse example of a schema', function () {
    $spec = miniSpec();
    $pet = ['$ref' => '#/components/schemas/Pet'];

    $full = Examples::of($spec, $pet);
    $sparse = Examples::minimal($spec, $pet);

    expect($full['id'])->toBe(Examples::UUID)
        ->and($full['kind'])->toBe('cat')
        ->and($full['hours'])->toBe(['start' => '09:00'])
        ->and($full['born_at'])->toBe('2026-10-01T09:15:02Z')
        ->and($full['limits'])->toBe(['example' => ['city' => 'Example city']])
        ->and($full)->toHaveKey('nickname')
        ->and($sparse['hours'])->toBeNull()
        ->and($sparse['born_at'])->toBeNull()
        ->and($sparse['mood'])->toBeNull()
        ->and($sparse['tags'])->toBe([])
        ->and($sparse['home'])->toBe(['city' => 'Example city'])
        ->and($sparse)->not->toHaveKey('nickname');
});

it('derives names', function (string $method, string $input, string $expected) {
    expect(Names::{$method}($input))->toBe($expected);
})->with([
    ['pascal', 'webhook-endpoints', 'WebhookEndpoints'],
    ['pascal', 'apiKeys.rotate_secret', 'ApiKeysRotateSecret'],
    ['pascal', 'message.sent', 'MessageSent'],
    ['enumCase', 'android_sim', 'AndroidSim'],
    ['enumCase', 'auto:health_critical', 'AutoHealthCritical'],
    ['enumCase', 'VALIDATION_FAILED', 'ValidationFailed'],
    ['enumCase', '24h', 'V24h'],
    ['singular', 'identities', 'identity'],
    ['singular', 'addresses', 'address'],
    ['singular', 'connect_methods', 'connect_method'],
    ['singular', 'status', 'status'],
]);

it('keeps internal section pointers and docblock terminators out of the docs', function () {
    expect(Names::docLines('Queues one message (03 section 3.1, 07 section 5). Ends a */ comment.'))
        ->toBe(['Queues one message. Ends a * / comment.'])
        ->and(Names::inline("Page size, 1 to 200 (03 section 2.5).\nSecond line."))->toBe('Page size, 1 to 200. Second line.');
});

it('reads the prefix and version of a spec', function () {
    expect(miniSpec()->prefix)->toBe('/v9')
        ->and(miniSpec()->version())->toBe('1.2.3');
});

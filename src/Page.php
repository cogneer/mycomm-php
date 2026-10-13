<?php

declare(strict_types=1);

namespace MyComm;

use ArrayIterator;
use Closure;
use Countable;
use Generator;
use IteratorAggregate;
use MyComm\Http\Response;

/**
 * One page of a list. Iterating it yields the items of this page; `autoPaging()` continues through the
 * following pages.
 *
 * @template T
 *
 * @implements IteratorAggregate<int, T>
 */
final class Page implements Countable, IteratorAggregate
{
    /**
     * @param  list<T>  $data
     * @param  string|null  $next_cursor  Cursor of the next page; null on the last page.
     * @param  (Closure(string): Page<T>)|null  $fetchNext
     */
    public function __construct(
        public readonly array $data,
        public readonly ?string $next_cursor = null,
        private readonly ?ResponseMeta $responseMeta = null,
        private readonly ?Closure $fetchNext = null,
    ) {}

    /**
     * @template TItem
     *
     * @param  Closure(array<string, mixed>): TItem  $map
     * @param  (Closure(string): Page<TItem>)|null  $fetchNext
     * @return Page<TItem>
     */
    public static function fromResponse(Response $response, Closure $map, ?Closure $fetchNext = null): self
    {
        $body = $response->json();
        $items = is_array($body['data'] ?? null) ? array_values($body['data']) : [];
        $cursor = $body['next_cursor'] ?? null;

        return new self(array_map($map, $items), is_string($cursor) ? $cursor : null, $response->meta(), $fetchNext);
    }

    public function hasMore(): bool
    {
        return $this->next_cursor !== null && $this->fetchNext !== null;
    }

    /**
     * Fetches the next page, or returns null on the last one.
     *
     * @return Page<T>|null
     */
    public function next(): ?self
    {
        if ($this->next_cursor === null || $this->fetchNext === null) {
            return null;
        }

        return ($this->fetchNext)($this->next_cursor);
    }

    /**
     * Every item from this page to the last, fetching pages as the loop reaches them.
     *
     * @return Generator<int, T>
     */
    public function autoPaging(): Generator
    {
        for ($page = $this; $page !== null; $page = $page->next()) {
            foreach ($page->data as $item) {
                yield $item;
            }
        }
    }

    /**
     * @return ArrayIterator<int, T>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function meta(): ?ResponseMeta
    {
        return $this->responseMeta;
    }
}

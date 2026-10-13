<?php

declare(strict_types=1);

namespace MyComm\Testing;

use Closure;
use MyComm\Http\Request;
use MyComm\Http\Response;
use MyComm\Http\Transport;

/**
 * Answers requests from memory and records them.
 *
 * @internal Use `MyCommFake`.
 */
final class FakeTransport implements Transport
{
    /** @var list<Request> */
    public array $requests = [];

    /** @var array<string, FakeResponse|Closure(Request): (FakeResponse|array<string, mixed>)> */
    public array $stubs = [];

    /** @var array<string, list<FakeResponse>> */
    public array $queues = [];

    public function send(Request $request): Response
    {
        $this->requests[] = $request;

        $answer = isset($this->queues[$request->operation]) ? array_shift($this->queues[$request->operation]) : null;
        $answer ??= $this->stubs[$request->operation] ?? FakeResponse::example();
        if ($answer instanceof Closure) {
            $answer = $answer($request);
        }
        if (is_array($answer)) {
            $answer = FakeResponse::example($answer);
        }

        return $answer->resolve($request->operation);
    }
}

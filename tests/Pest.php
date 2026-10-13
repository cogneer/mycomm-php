<?php

declare(strict_types=1);

use MyComm\Http\Connection;
use MyComm\Http\Request;
use MyComm\Http\Response;
use MyComm\Http\Transport;

/**
 * A transport that answers from a queue, records what it was sent and the seconds the connection waited.
 */
final class ScriptedTransport implements Transport
{
    /** @var list<Request> */
    public array $requests = [];

    /** @var list<float> */
    public array $slept = [];

    /**
     * @param  list<Response|Throwable>  $answers
     */
    public function __construct(private array $answers) {}

    public function send(Request $request): Response
    {
        $this->requests[] = $request;
        $answer = array_shift($this->answers) ?? new Response(200, [], '{}');
        if ($answer instanceof Throwable) {
            throw $answer;
        }

        return $answer;
    }
}

/**
 * @param  list<Response|Throwable>  $answers
 * @return array{Connection, ScriptedTransport}
 */
function scripted(array $answers = [], int $maxRetries = 2, ?string $accountId = null): array
{
    $transport = new ScriptedTransport($answers);
    $connection = new Connection($transport, 'mc_live_test', 'https://api.test', $accountId, $maxRetries, function (float $seconds) use ($transport): void {
        $transport->slept[] = $seconds;
    });

    return [$connection, $transport];
}

/**
 * @param  array<string, mixed>  $details
 * @param  array<string, string>  $headers
 */
function errorResponse(int $status, string $code, array $details = [], array $headers = []): Response
{
    return new Response($status, $headers, (string) json_encode(['error' => [
        'code' => $code,
        'message' => "Failed with {$code}.",
        'details' => $details,
        'request_id' => '01923f80-5c6a-7b10-9c21-0d3e4f5a6b7d',
    ]]));
}

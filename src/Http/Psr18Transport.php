<?php

declare(strict_types=1);

namespace MyComm\Http;

use MyComm\Exceptions\ConnectionException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Sends requests through any PSR-18 client.
 */
final class Psr18Transport implements Transport
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {}

    public function send(Request $request): Response
    {
        $psrRequest = $this->requestFactory->createRequest($request->method, $request->url);
        foreach ($request->headers as $name => $value) {
            $psrRequest = $psrRequest->withHeader($name, $value);
        }
        if ($request->body !== null) {
            $psrRequest = $psrRequest->withBody($this->streamFactory->createStream($request->body));
        }

        try {
            $psrResponse = $this->client->sendRequest($psrRequest);
        } catch (ClientExceptionInterface $e) {
            throw new ConnectionException("Could not reach MyComm: {$e->getMessage()}", 0, $e);
        }

        $headers = [];
        foreach (array_keys($psrResponse->getHeaders()) as $name) {
            $headers[(string) $name] = $psrResponse->getHeaderLine((string) $name);
        }

        return new Response($psrResponse->getStatusCode(), $headers, (string) $psrResponse->getBody());
    }
}

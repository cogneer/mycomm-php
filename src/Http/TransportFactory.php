<?php

declare(strict_types=1);

namespace MyComm\Http;

use GuzzleHttp\Client as GuzzleClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;

/**
 * Builds the transport of a client: around the PSR-18 client the caller passed, around Guzzle with the
 * SDK's timeout when Guzzle is installed, or around whichever PSR-18 client discovery finds.
 */
final class TransportFactory
{
    public static function make(?ClientInterface $httpClient, float $timeout): Transport
    {
        if ($httpClient === null && class_exists(GuzzleClient::class)) {
            $httpClient = new GuzzleClient([
                'timeout' => $timeout,
                'connect_timeout' => min($timeout, 10.0),
                'http_errors' => false,
                'allow_redirects' => false,
            ]);
        }

        return new Psr18Transport(
            $httpClient ?? Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
        );
    }
}

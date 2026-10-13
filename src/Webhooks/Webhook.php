<?php

declare(strict_types=1);

namespace MyComm\Webhooks;

use MyComm\Exceptions\WebhookSignatureException;
use SensitiveParameter;

/**
 * Verifies and reads the webhooks MyComm sends.
 *
 *     $event = Webhook::constructEvent($rawBody, $request->header('MyComm-Signature'), $secret);
 *
 * Always pass the raw request body, exactly as received: a re-encoded body no longer matches its signature.
 */
final class Webhook
{
    public const SIGNATURE_HEADER = 'MyComm-Signature';

    /** Seconds a signature stays acceptable, which stops replays of a captured request. */
    public const DEFAULT_TOLERANCE = 300;

    /**
     * Verifies the signature and returns the typed event.
     *
     * @param  string|list<string>  $secret  The endpoint's signing secret; several while you roll over to a new one.
     * @param  int|null  $now  Unix time to compare the signature's timestamp with; the current time when null.
     *
     * @throws WebhookSignatureException when the request must be rejected (answer 400)
     */
    public static function constructEvent(
        string $payload,
        ?string $signature,
        #[SensitiveParameter] string|array $secret,
        int $tolerance = self::DEFAULT_TOLERANCE,
        ?int $now = null,
    ): Event {
        self::verify($payload, $signature, $secret, $tolerance, $now);

        return self::parse($payload);
    }

    /**
     * @param  string|list<string>  $secret
     *
     * @throws WebhookSignatureException
     */
    public static function verify(
        string $payload,
        ?string $signature,
        #[SensitiveParameter] string|array $secret,
        int $tolerance = self::DEFAULT_TOLERANCE,
        ?int $now = null,
    ): void {
        $timestamp = null;
        $signatures = [];
        foreach (explode(',', (string) $signature) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');
            if ($key === 't' && ctype_digit($value)) {
                $timestamp = (int) $value;
            } elseif ($key === 'v1' && $value !== '') {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || $signatures === []) {
            throw new WebhookSignatureException('The MyComm-Signature header is missing or malformed.');
        }
        if (abs(($now ?? time()) - $timestamp) > $tolerance) {
            throw new WebhookSignatureException('The webhook timestamp is outside the tolerance.');
        }

        // During a secret rotation the header carries one signature per secret: any match is enough.
        foreach ((array) $secret as $candidate) {
            $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $candidate);
            foreach ($signatures as $given) {
                if (hash_equals($expected, $given)) {
                    return;
                }
            }
        }

        throw new WebhookSignatureException('No signature of the webhook matches the secret.');
    }

    /**
     * Reads an event without checking its signature. Only for a body that was verified already.
     *
     * @throws WebhookSignatureException when the body is not an event
     */
    public static function parse(string $payload): Event
    {
        $data = json_decode($payload, true);
        if (! is_array($data) || ! is_string($data['type'] ?? null) || ! is_string($data['id'] ?? null)) {
            throw new WebhookSignatureException('The webhook body is not a MyComm event.');
        }

        // An event type this SDK version does not know is still an event: acknowledge it with a 2xx.
        $class = EventMap::EVENTS[$data['type']] ?? UnknownEvent::class;

        return $class::fromArray($data);
    }

    /**
     * The `MyComm-Signature` value MyComm would send for a body, for testing your own receiver.
     */
    public static function sign(string $payload, #[SensitiveParameter] string $secret, ?int $timestamp = null): string
    {
        $timestamp ??= time();

        return 't='.$timestamp.',v1='.hash_hmac('sha256', $timestamp.'.'.$payload, $secret);
    }
}

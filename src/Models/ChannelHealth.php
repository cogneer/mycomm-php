<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

/**
 * The SIM safety score.
 */
final class ChannelHealth extends Model
{
    /**
     * 0 to 100; `null` until the first evaluation.
     */
    public readonly ?int $score;

    public readonly Enums\ChannelHealthBand|string|null $band;

    public readonly ?DateTimeImmutable $evaluated_at;

    /**
     * Set while the channel is quarantined.
     */
    public readonly ?DateTimeImmutable $quarantine_until;

    /**
     * The inputs of the last evaluation under the keys the evaluation stored (failure rate, delivery-report rate, opt-out rate, reply ratio, consecutive failures, carrier barring, carrier notice, delivery-report silence, cana…
     *
     * @var array<string, mixed>
     */
    public readonly array $components;

    protected function hydrate(array $data): void
    {
        $this->score = $data['score'] ?? null;
        $this->band = isset($data['band']) ? self::enum(Enums\ChannelHealthBand::class, $data['band']) : null;
        $this->evaluated_at = isset($data['evaluated_at']) ? self::dateTime($data['evaluated_at']) : null;
        $this->quarantine_until = isset($data['quarantine_until']) ? self::dateTime($data['quarantine_until']) : null;
        $this->components = $data['components'];
    }
}

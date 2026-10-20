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
 * One channel type and its static shape.
 */
final class ChannelTypeInfo extends Model
{
    public readonly Enums\ChannelType|string $type;

    public readonly string $label;

    /**
     * Enabled on the platform and allowed by `settings.channels.allowed_types`.
     */
    public readonly bool $available;

    /**
     * The release a type that has not shipped yet arrives in (`v2`, `v2.1`); absent once shipped.
     */
    public readonly ?string $available_from;

    /**
     * @var list<Enums\ChannelConnectMethod|string>
     */
    public readonly array $connect_methods;

    /**
     * Identity types this type sends to, in preference order.
     *
     * @var list<string>
     */
    public readonly array $identity_types;

    public readonly Enums\ChannelAddressFormat|string $address_format;

    public readonly ChannelTypeCapabilities $capabilities;

    /**
     * @var list<Enums\ChannelContentType|string>
     */
    public readonly array $content_types;

    /**
     * @var list<Enums\ChannelDeliveryState|string>
     */
    public readonly array $delivery_states;

    protected function hydrate(array $data): void
    {
        $this->type = self::enum(Enums\ChannelType::class, $data['type']);
        $this->label = $data['label'];
        $this->available = $data['available'];
        $this->available_from = $data['available_from'] ?? null;
        $this->connect_methods = array_map(static fn (string $item): Enums\ChannelConnectMethod|string => self::enum(Enums\ChannelConnectMethod::class, $item), array_values($data['connect_methods']));
        $this->identity_types = $data['identity_types'];
        $this->address_format = self::enum(Enums\ChannelAddressFormat::class, $data['address_format']);
        $this->capabilities = ChannelTypeCapabilities::fromArray($data['capabilities']);
        $this->content_types = array_map(static fn (string $item): Enums\ChannelContentType|string => self::enum(Enums\ChannelContentType::class, $item), array_values($data['content_types']));
        $this->delivery_states = array_map(static fn (string $item): Enums\ChannelDeliveryState|string => self::enum(Enums\ChannelDeliveryState::class, $item), array_values($data['delivery_states']));
    }
}

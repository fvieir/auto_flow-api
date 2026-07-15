<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Entities;

final class ChannelContact
{
    private function __construct(
        private ?int $id,
        private string $channel,
        private string $phone,
        private ?string $externalId,
    ) {
    }

    public static function createNew(string $channel, string $phone, ?string $externalId): self
    {
        return new self(null, $channel, $phone, $externalId);
    }

    public static function fromPersistence(int $id, string $channel, string $phone, ?string $externalId): self
    {
        return new self($id, $channel, $phone, $externalId);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function channel(): string
    {
        return $this->channel;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function externalId(): ?string
    {
        return $this->externalId;
    }
}

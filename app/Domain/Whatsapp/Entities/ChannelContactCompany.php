<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Entities;

final class ChannelContactCompany
{
    private function __construct(
        private ?int $id,
        private int $channelContactId,
        private int $tenantId,
        private ?int $clientId,
    ) {
    }

    public static function createNew(int $channelContactId, int $tenantId, ?int $clientId): self
    {
        return new self(null, $channelContactId, $tenantId, $clientId);
    }

    public static function fromPersistence(int $id, int $channelContactId, int $tenantId, ?int $clientId): self
    {
        return new self($id, $channelContactId, $tenantId, $clientId);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function channelContactId(): int
    {
        return $this->channelContactId;
    }

    public function tenantId(): int
    {
        return $this->tenantId;
    }

    public function clientId(): ?int
    {
        return $this->clientId;
    }
}

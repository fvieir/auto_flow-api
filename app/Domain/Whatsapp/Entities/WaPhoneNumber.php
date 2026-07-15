<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Entities;

final class WaPhoneNumber
{
    private function __construct(
        private ?int $id,
        private int $tenantId,
        private string $phoneNumberId,
        private string $displayPhoneNumber,
        private ?string $verifiedName,
        private ?string $qualityRating,
        private string $accessToken,
        private bool $isActive,
    ) {
    }

    public static function fromPersistence(
        int $id,
        int $tenantId,
        string $phoneNumberId,
        string $displayPhoneNumber,
        ?string $verifiedName,
        ?string $qualityRating,
        string $accessToken,
        bool $isActive,
    ): self {
        return new self($id, $tenantId, $phoneNumberId, $displayPhoneNumber, $verifiedName, $qualityRating, $accessToken, $isActive);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function tenantId(): int
    {
        return $this->tenantId;
    }

    public function phoneNumberId(): string
    {
        return $this->phoneNumberId;
    }

    public function displayPhoneNumber(): string
    {
        return $this->displayPhoneNumber;
    }

    public function verifiedName(): ?string
    {
        return $this->verifiedName;
    }

    public function qualityRating(): ?string
    {
        return $this->qualityRating;
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}

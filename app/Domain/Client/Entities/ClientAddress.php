<?php

declare(strict_types=1);

namespace App\Domain\Client\Entities;

final class ClientAddress
{
    private function __construct(
        private ?int $id,
        private int $clientId,
        private string $postalCode,
        private string $street,
        private string $number,
        private ?string $complement,
        private string $neighborhood,
        private string $city,
        private string $state,
        private bool $isPrimary,
    ) {
    }

    public static function createNew(
        int $clientId,
        string $postalCode,
        string $street,
        string $number,
        ?string $complement,
        string $neighborhood,
        string $city,
        string $state,
        bool $isPrimary,
    ): self {
        return new self(null, $clientId, $postalCode, $street, $number, $complement, $neighborhood, $city, $state, $isPrimary);
    }

    public static function fromPersistence(
        int $id,
        int $clientId,
        string $postalCode,
        string $street,
        string $number,
        ?string $complement,
        string $neighborhood,
        string $city,
        string $state,
        bool $isPrimary,
    ): self {
        return new self($id, $clientId, $postalCode, $street, $number, $complement, $neighborhood, $city, $state, $isPrimary);
    }

    public function withDetails(
        string $postalCode,
        string $street,
        string $number,
        ?string $complement,
        string $neighborhood,
        string $city,
        string $state,
        bool $isPrimary,
    ): self {
        return new self($this->id, $this->clientId, $postalCode, $street, $number, $complement, $neighborhood, $city, $state, $isPrimary);
    }

    public function withPrimary(bool $isPrimary): self
    {
        return new self(
            $this->id,
            $this->clientId,
            $this->postalCode,
            $this->street,
            $this->number,
            $this->complement,
            $this->neighborhood,
            $this->city,
            $this->state,
            $isPrimary,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function clientId(): int
    {
        return $this->clientId;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function street(): string
    {
        return $this->street;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function complement(): ?string
    {
        return $this->complement;
    }

    public function neighborhood(): string
    {
        return $this->neighborhood;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Service\Entities;

final class Service
{
    private function __construct(
        private ?int $id,
        private string $name,
        private int $durationMinutes,
        private float $price,
    ) {
    }

    public static function createNew(string $name, int $durationMinutes, float $price): self
    {
        return new self(null, $name, $durationMinutes, $price);
    }

    public static function fromPersistence(int $id, string $name, int $durationMinutes, float $price): self
    {
        return new self($id, $name, $durationMinutes, $price);
    }

    public function withDetails(string $name, int $durationMinutes, float $price): self
    {
        return new self($this->id, $name, $durationMinutes, $price);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function durationMinutes(): int
    {
        return $this->durationMinutes;
    }

    public function price(): float
    {
        return $this->price;
    }
}

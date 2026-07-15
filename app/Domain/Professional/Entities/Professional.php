<?php

declare(strict_types=1);

namespace App\Domain\Professional\Entities;

final class Professional
{
    private function __construct(
        private ?int $id,
        private string $name,
        private string $phone,
        private ?string $email,
    ) {
    }

    public static function createNew(string $name, string $phone, ?string $email): self
    {
        return new self(null, $name, $phone, $email);
    }

    public static function fromPersistence(int $id, string $name, string $phone, ?string $email): self
    {
        return new self($id, $name, $phone, $email);
    }

    public function withDetails(string $name, string $phone, ?string $email): self
    {
        return new self($this->id, $name, $phone, $email);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function email(): ?string
    {
        return $this->email;
    }
}

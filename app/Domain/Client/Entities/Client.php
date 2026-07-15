<?php

declare(strict_types=1);

namespace App\Domain\Client\Entities;

final class Client
{
    private function __construct(
        private ?int $id,
        private string $phone,
        private ?string $name,
        private ?string $email,
        private ?string $document,
    ) {
    }

    public static function createNew(
        string $phone,
        ?string $name,
        ?string $email,
        ?string $document,
    ): self {
        return new self(null, $phone, $name, $email, $document);
    }

    public static function fromPersistence(
        int $id,
        string $phone,
        ?string $name,
        ?string $email,
        ?string $document,
    ): self {
        return new self($id, $phone, $name, $email, $document);
    }

    public function withDetails(string $phone, ?string $name, ?string $email, ?string $document): self
    {
        return new self($this->id, $phone, $name, $email, $document);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function document(): ?string
    {
        return $this->document;
    }
}

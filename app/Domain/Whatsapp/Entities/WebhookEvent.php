<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Entities;

use DateTimeImmutable;

final class WebhookEvent
{
    private function __construct(
        private ?int $id,
        private ?int $tenantId,
        private string $eventType,
        private array $payload,
        private bool $processed,
        private ?DateTimeImmutable $processedAt,
        private ?DateTimeImmutable $notifiedN8nAt,
        private ?string $errorMessage,
        private int $attempts,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function createNew(string $eventType, array $payload): self
    {
        return new self(null, null, $eventType, $payload, false, null, null, null, 0);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPersistence(
        int $id,
        ?int $tenantId,
        string $eventType,
        array $payload,
        bool $processed,
        ?DateTimeImmutable $processedAt,
        ?DateTimeImmutable $notifiedN8nAt,
        ?string $errorMessage,
        int $attempts,
    ): self {
        return new self($id, $tenantId, $eventType, $payload, $processed, $processedAt, $notifiedN8nAt, $errorMessage, $attempts);
    }

    public function withTenantId(int $tenantId): self
    {
        return new self($this->id, $tenantId, $this->eventType, $this->payload, $this->processed, $this->processedAt, $this->notifiedN8nAt, $this->errorMessage, $this->attempts);
    }

    public function markProcessed(DateTimeImmutable $at): self
    {
        return new self($this->id, $this->tenantId, $this->eventType, $this->payload, true, $at, $this->notifiedN8nAt, null, $this->attempts);
    }

    public function markFailed(string $errorMessage): self
    {
        return new self($this->id, $this->tenantId, $this->eventType, $this->payload, false, $this->processedAt, $this->notifiedN8nAt, $errorMessage, $this->attempts + 1);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function tenantId(): ?int
    {
        return $this->tenantId;
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    public function processed(): bool
    {
        return $this->processed;
    }

    public function processedAt(): ?DateTimeImmutable
    {
        return $this->processedAt;
    }

    public function notifiedN8nAt(): ?DateTimeImmutable
    {
        return $this->notifiedN8nAt;
    }

    public function errorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }
}

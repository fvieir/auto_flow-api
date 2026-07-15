<?php

declare(strict_types=1);

namespace App\Domain\Professional\Entities;

use DateTimeImmutable;
use InvalidArgumentException;

final class ScheduleBlock
{
    private function __construct(
        private ?int $id,
        private int $professionalId,
        private DateTimeImmutable $startAt,
        private DateTimeImmutable $endAt,
        private ?string $reason,
    ) {
        if ($endAt <= $startAt) {
            throw new InvalidArgumentException('end_at deve ser maior que start_at.');
        }
    }

    public static function createNew(
        int $professionalId,
        DateTimeImmutable $startAt,
        DateTimeImmutable $endAt,
        ?string $reason,
    ): self {
        return new self(null, $professionalId, $startAt, $endAt, $reason);
    }

    public static function fromPersistence(
        int $id,
        int $professionalId,
        DateTimeImmutable $startAt,
        DateTimeImmutable $endAt,
        ?string $reason,
    ): self {
        return new self($id, $professionalId, $startAt, $endAt, $reason);
    }

    public function withDetails(DateTimeImmutable $startAt, DateTimeImmutable $endAt, ?string $reason): self
    {
        return new self($this->id, $this->professionalId, $startAt, $endAt, $reason);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function professionalId(): int
    {
        return $this->professionalId;
    }

    public function startAt(): DateTimeImmutable
    {
        return $this->startAt;
    }

    public function endAt(): DateTimeImmutable
    {
        return $this->endAt;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }
}

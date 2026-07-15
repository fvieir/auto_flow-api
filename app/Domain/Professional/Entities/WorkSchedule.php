<?php

declare(strict_types=1);

namespace App\Domain\Professional\Entities;

use InvalidArgumentException;

final class WorkSchedule
{
    private function __construct(
        private ?int $id,
        private int $professionalId,
        private int $weekday,
        private string $startTime,
        private string $endTime,
    ) {
        if ($weekday < 0 || $weekday > 6) {
            throw new InvalidArgumentException('weekday deve estar entre 0 (domingo) e 6 (sábado).');
        }

        if ($endTime <= $startTime) {
            throw new InvalidArgumentException('end_time deve ser maior que start_time.');
        }
    }

    public static function createNew(int $professionalId, int $weekday, string $startTime, string $endTime): self
    {
        return new self(null, $professionalId, $weekday, $startTime, $endTime);
    }

    public static function fromPersistence(
        int $id,
        int $professionalId,
        int $weekday,
        string $startTime,
        string $endTime,
    ): self {
        return new self($id, $professionalId, $weekday, $startTime, $endTime);
    }

    public function withDetails(int $weekday, string $startTime, string $endTime): self
    {
        return new self($this->id, $this->professionalId, $weekday, $startTime, $endTime);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function professionalId(): int
    {
        return $this->professionalId;
    }

    public function weekday(): int
    {
        return $this->weekday;
    }

    public function startTime(): string
    {
        return $this->startTime;
    }

    public function endTime(): string
    {
        return $this->endTime;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Appointment\Entities;

use App\Domain\Appointment\Enums\AppointmentStatus;
use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

final class Appointment
{
    private function __construct(
        private ?int $id,
        private int $clientId,
        private int $professionalId,
        private int $serviceId,
        private DateTimeImmutable $startsAt,
        private int $durationMinutes,
        private DateTimeImmutable $endsAt,
        private AppointmentStatus $status,
    ) {
        if ($durationMinutes < 1) {
            throw new InvalidArgumentException('duration_minutes deve ser maior que zero.');
        }

        if ($endsAt <= $startsAt) {
            throw new InvalidArgumentException('ends_at deve ser maior que starts_at.');
        }
    }

    public static function createNew(
        int $clientId,
        int $professionalId,
        int $serviceId,
        DateTimeImmutable $startsAt,
        int $durationMinutes,
    ): self {
        $endsAt = $startsAt->add(new DateInterval("PT{$durationMinutes}M"));

        return new self(null, $clientId, $professionalId, $serviceId, $startsAt, $durationMinutes, $endsAt, AppointmentStatus::Scheduled);
    }

    public static function fromPersistence(
        int $id,
        int $clientId,
        int $professionalId,
        int $serviceId,
        DateTimeImmutable $startsAt,
        int $durationMinutes,
        DateTimeImmutable $endsAt,
        AppointmentStatus $status,
    ): self {
        return new self($id, $clientId, $professionalId, $serviceId, $startsAt, $durationMinutes, $endsAt, $status);
    }

    public function withStatus(AppointmentStatus $status): self
    {
        return new self(
            $this->id,
            $this->clientId,
            $this->professionalId,
            $this->serviceId,
            $this->startsAt,
            $this->durationMinutes,
            $this->endsAt,
            $status,
        );
    }

    public function withNewSchedule(DateTimeImmutable $startsAt, int $durationMinutes): self
    {
        $endsAt = $startsAt->add(new DateInterval("PT{$durationMinutes}M"));

        return new self(
            $this->id,
            $this->clientId,
            $this->professionalId,
            $this->serviceId,
            $startsAt,
            $durationMinutes,
            $endsAt,
            AppointmentStatus::Scheduled,
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

    public function professionalId(): int
    {
        return $this->professionalId;
    }

    public function serviceId(): int
    {
        return $this->serviceId;
    }

    public function startsAt(): DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function durationMinutes(): int
    {
        return $this->durationMinutes;
    }

    public function endsAt(): DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function status(): AppointmentStatus
    {
        return $this->status;
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use DateTimeImmutable;

final class ListAppointmentsUseCase
{
    public function __construct(private readonly AppointmentRepositoryInterface $appointments)
    {
    }

    /**
     * @return list<Appointment>
     */
    public function handle(
        ?string $status = null,
        ?int $professionalId = null,
        ?int $clientId = null,
        ?DateTimeImmutable $startDate = null,
        ?DateTimeImmutable $endDate = null,
    ): array {
        return $this->appointments->list($status, $professionalId, $clientId, $startDate, $endDate);
    }
}

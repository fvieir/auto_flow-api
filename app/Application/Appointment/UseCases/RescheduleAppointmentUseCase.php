<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Application\Appointment\DTOs\RescheduleAppointmentDTO;
use App\Application\Appointment\Services\AvailabilityService;
use App\Application\Appointment\Support\TenantClock;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Domain\Appointment\Exceptions\AppointmentNotFoundException;
use App\Domain\Appointment\Exceptions\InvalidStatusTransitionException;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;

final class RescheduleAppointmentUseCase
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointments,
        private readonly TenantRepositoryInterface $tenants,
        private readonly CurrentTenant $currentTenant,
        private readonly AvailabilityService $availability,
    ) {
    }

    public function handle(int $id, RescheduleAppointmentDTO $dto): Appointment
    {
        $appointment = $this->appointments->findById($id);

        if ($appointment === null) {
            throw new AppointmentNotFoundException($id);
        }

        if (in_array($appointment->status(), [AppointmentStatus::Cancelled, AppointmentStatus::Completed], true)) {
            throw new InvalidStatusTransitionException('Não é possível remarcar um appointment cancelado ou concluído.');
        }

        $timezone = $this->tenants->findById($this->currentTenant->id())->timezone();
        $startsAt = TenantClock::parseLocalDateTime($dto->startsAt, $timezone);

        $updated = $appointment->withNewSchedule($startsAt, $appointment->durationMinutes());

        $this->availability->ensureSlotAvailable(
            $appointment->professionalId(),
            $updated->startsAt(),
            $updated->endsAt(),
            $timezone,
            excludeAppointmentId: $id,
        );

        return $this->appointments->update($updated);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Domain\Appointment\Exceptions\AppointmentNotFoundException;
use App\Domain\Appointment\Exceptions\InvalidStatusTransitionException;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Presentation\Queue\Jobs\ManagerCancellationNotificationJob;

final class CancelAppointmentUseCase
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointments,
        private readonly CurrentTenant $currentTenant,
    ) {
    }

    public function handle(int $id): Appointment
    {
        $appointment = $this->appointments->findById($id);

        if ($appointment === null) {
            throw new AppointmentNotFoundException($id);
        }

        if (in_array($appointment->status(), [AppointmentStatus::Cancelled, AppointmentStatus::Completed], true)) {
            throw new InvalidStatusTransitionException('Não é possível cancelar um appointment já cancelado ou concluído.');
        }

        $updated = $this->appointments->update($appointment->withStatus(AppointmentStatus::Cancelled));

        ManagerCancellationNotificationJob::dispatch($updated->id(), $this->currentTenant->id()->value());

        return $updated;
    }
}

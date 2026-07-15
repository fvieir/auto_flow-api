<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Application\Appointment\DTOs\UpdateAppointmentStatusDTO;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Domain\Appointment\Exceptions\AppointmentNotFoundException;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Presentation\Queue\Jobs\PostAppointmentReviewJob;

final class UpdateAppointmentStatusUseCase
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointments,
        private readonly CurrentTenant $currentTenant,
    ) {
    }

    public function handle(int $id, UpdateAppointmentStatusDTO $dto): Appointment
    {
        $appointment = $this->appointments->findById($id);

        if ($appointment === null) {
            throw new AppointmentNotFoundException($id);
        }

        $newStatus = AppointmentStatus::from($dto->status);
        $wasCompleted = $appointment->status() === AppointmentStatus::Completed;

        $updated = $this->appointments->update($appointment->withStatus($newStatus));

        if ($newStatus === AppointmentStatus::Completed && ! $wasCompleted) {
            PostAppointmentReviewJob::dispatch($updated->id(), $this->currentTenant->id()->value());
        }

        return $updated;
    }
}

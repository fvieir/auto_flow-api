<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Application\Appointment\DTOs\UpdateAppointmentStatusDTO;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Domain\Appointment\Exceptions\AppointmentNotFoundException;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;

final class UpdateAppointmentStatusUseCase
{
    public function __construct(private readonly AppointmentRepositoryInterface $appointments)
    {
    }

    public function handle(int $id, UpdateAppointmentStatusDTO $dto): Appointment
    {
        $appointment = $this->appointments->findById($id);

        if ($appointment === null) {
            throw new AppointmentNotFoundException($id);
        }

        $updated = $appointment->withStatus(AppointmentStatus::from($dto->status));

        return $this->appointments->update($updated);
    }
}

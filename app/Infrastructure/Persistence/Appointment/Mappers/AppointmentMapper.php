<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Appointment\Mappers;

use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Infrastructure\Persistence\Appointment\Models\AppointmentModel;

final class AppointmentMapper
{
    public function toDomain(AppointmentModel $model): Appointment
    {
        return Appointment::fromPersistence(
            id: (int) $model->id,
            clientId: (int) $model->client_id,
            professionalId: (int) $model->professional_id,
            serviceId: (int) $model->service_id,
            startsAt: $model->starts_at->toDateTimeImmutable(),
            durationMinutes: (int) $model->duration_minutes,
            endsAt: $model->ends_at->toDateTimeImmutable(),
            status: AppointmentStatus::from($model->status),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Appointment\Repositories;

use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Infrastructure\Persistence\Appointment\Mappers\AppointmentMapper;
use App\Infrastructure\Persistence\Appointment\Models\AppointmentModel;
use DateTimeImmutable;

final class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function __construct(private readonly AppointmentMapper $mapper)
    {
    }

    public function findById(int $id): ?Appointment
    {
        $model = AppointmentModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function list(
        ?string $status = null,
        ?int $professionalId = null,
        ?int $clientId = null,
        ?DateTimeImmutable $startDate = null,
        ?DateTimeImmutable $endDate = null,
    ): array {
        return AppointmentModel::query()
            ->when($status !== null, fn ($query) => $query->where('status', $status))
            ->when($professionalId !== null, fn ($query) => $query->where('professional_id', $professionalId))
            ->when($clientId !== null, fn ($query) => $query->where('client_id', $clientId))
            ->when($startDate !== null, fn ($query) => $query->where('starts_at', '>=', $startDate))
            ->when($endDate !== null, fn ($query) => $query->where('starts_at', '<', $endDate))
            ->orderBy('starts_at')
            ->get()
            ->map(fn (AppointmentModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function upcomingByClient(int $clientId, DateTimeImmutable $from, int $limit = 1): array
    {
        return AppointmentModel::where('client_id', $clientId)
            ->where('starts_at', '>=', $from)
            ->where('status', '!=', AppointmentStatus::Cancelled->value)
            ->orderBy('starts_at')
            ->limit($limit)
            ->get()
            ->map(fn (AppointmentModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function create(Appointment $appointment): Appointment
    {
        $model = AppointmentModel::create([
            'client_id' => $appointment->clientId(),
            'professional_id' => $appointment->professionalId(),
            'service_id' => $appointment->serviceId(),
            'starts_at' => $appointment->startsAt(),
            'duration_minutes' => $appointment->durationMinutes(),
            'ends_at' => $appointment->endsAt(),
            'status' => $appointment->status()->value,
        ]);

        return $this->mapper->toDomain($model);
    }

    public function update(Appointment $appointment): Appointment
    {
        $model = AppointmentModel::findOrFail($appointment->id());

        $model->update([
            'starts_at' => $appointment->startsAt(),
            'duration_minutes' => $appointment->durationMinutes(),
            'ends_at' => $appointment->endsAt(),
            'status' => $appointment->status()->value,
        ]);

        return $this->mapper->toDomain($model);
    }

    public function overlapsExisting(
        int $professionalId,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        ?int $excludeAppointmentId = null,
    ): bool {
        return AppointmentModel::where('professional_id', $professionalId)
            ->where('status', '!=', AppointmentStatus::Cancelled->value)
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->when($excludeAppointmentId !== null, fn ($query) => $query->where('id', '!=', $excludeAppointmentId))
            ->exists();
    }

    public function existsForClientCreatedAfter(int $clientId, DateTimeImmutable $after): bool
    {
        return AppointmentModel::where('client_id', $clientId)
            ->where('status', '!=', AppointmentStatus::Cancelled->value)
            ->where('created_at', '>=', $after)
            ->exists();
    }

    public function dueForReminder(DateTimeImmutable $windowStart, DateTimeImmutable $windowEnd): array
    {
        return AppointmentModel::whereIn('status', [AppointmentStatus::Scheduled->value, AppointmentStatus::Confirmed->value])
            ->whereNull('reminder_sent_at')
            ->where('starts_at', '>=', $windowStart)
            ->where('starts_at', '<', $windowEnd)
            ->orderBy('starts_at')
            ->get()
            ->map(fn (AppointmentModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function markReminderSent(int $id, DateTimeImmutable $at): void
    {
        AppointmentModel::where('id', $id)->update(['reminder_sent_at' => $at]);
    }

    public function busyIntervalsFor(int $professionalId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        return AppointmentModel::where('professional_id', $professionalId)
            ->where('status', '!=', AppointmentStatus::Cancelled->value)
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->get()
            ->map(fn (AppointmentModel $model) => [
                'start' => $model->starts_at->toDateTimeImmutable(),
                'end' => $model->ends_at->toDateTimeImmutable(),
            ])
            ->all();
    }
}

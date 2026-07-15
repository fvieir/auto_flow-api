<?php

declare(strict_types=1);

namespace App\Domain\Appointment\Repositories;

use App\Domain\Appointment\Entities\Appointment;
use DateTimeImmutable;

interface AppointmentRepositoryInterface
{
    public function findById(int $id): ?Appointment;

    /**
     * @return list<Appointment>
     */
    public function list(
        ?string $status = null,
        ?int $professionalId = null,
        ?int $clientId = null,
        ?DateTimeImmutable $startDate = null,
        ?DateTimeImmutable $endDate = null,
    ): array;

    /**
     * @return list<Appointment>
     */
    public function upcomingByClient(int $clientId, DateTimeImmutable $from, int $limit = 1): array;

    public function create(Appointment $appointment): Appointment;

    public function update(Appointment $appointment): Appointment;

    public function overlapsExisting(
        int $professionalId,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        ?int $excludeAppointmentId = null,
    ): bool;

    /**
     * @return list<array{start: DateTimeImmutable, end: DateTimeImmutable}>
     */
    public function busyIntervalsFor(int $professionalId, DateTimeImmutable $start, DateTimeImmutable $end): array;

    /**
     * Usado pela regra de negócio do Kanban (mover conversa para "converted"
     * sem appointment_id explícito): existe algum agendamento não cancelado
     * desse cliente criado depois do início da conversa?
     */
    public function existsForClientCreatedAfter(int $clientId, DateTimeImmutable $after): bool;
}

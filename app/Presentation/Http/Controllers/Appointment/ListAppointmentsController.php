<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Appointment;

use App\Application\Appointment\Support\TenantClock;
use App\Application\Appointment\UseCases\ListAppointmentsUseCase;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Infrastructure\Persistence\Tenant\Models\TenantModel;
use App\Presentation\Http\Requests\Appointment\ListAppointmentsRequest;
use App\Presentation\Http\Resources\Appointment\AppointmentResource;
use DateInterval;
use DateTimeZone;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListAppointmentsController
{
    public function __construct(private readonly ListAppointmentsUseCase $useCase)
    {
    }

    public function __invoke(ListAppointmentsRequest $request): AnonymousResourceCollection
    {
        /** @var array<string, mixed> $filters */
        $filters = $request->validated();

        $startDate = null;
        $endDate = null;

        if (isset($filters['data'])) {
            $timezone = TenantModel::find(app(CurrentTenant::class)->id()->value())->timezone;
            $localStartDate = TenantClock::parseLocalDate($filters['data'], $timezone);
            $localEndDate = $localStartDate->add(new DateInterval('P1D'));
            $startDate = $localStartDate->setTimezone(new DateTimeZone('UTC'));
            $endDate = $localEndDate->setTimezone(new DateTimeZone('UTC'));
        }

        $appointments = $this->useCase->handle(
            status: $filters['status'] ?? null,
            professionalId: isset($filters['professional_id']) ? (int) $filters['professional_id'] : null,
            clientId: isset($filters['client_id']) ? (int) $filters['client_id'] : null,
            startDate: $startDate,
            endDate: $endDate,
        );

        return AppointmentResource::collection($appointments);
    }
}

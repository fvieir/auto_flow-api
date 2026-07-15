<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Appointment;

use App\Application\Appointment\UseCases\CancelAppointmentUseCase;
use App\Presentation\Http\Resources\Appointment\AppointmentResource;
use Illuminate\Http\JsonResponse;

final class CancelAppointmentController
{
    public function __construct(private readonly CancelAppointmentUseCase $useCase)
    {
    }

    public function __invoke(int $appointment): JsonResponse
    {
        $updated = $this->useCase->handle($appointment);

        return AppointmentResource::make($updated)->response();
    }
}

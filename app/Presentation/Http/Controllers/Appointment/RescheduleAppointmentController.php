<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Appointment;

use App\Application\Appointment\DTOs\RescheduleAppointmentDTO;
use App\Application\Appointment\UseCases\RescheduleAppointmentUseCase;
use App\Presentation\Http\Requests\Appointment\RescheduleAppointmentRequest;
use App\Presentation\Http\Resources\Appointment\AppointmentResource;
use Illuminate\Http\JsonResponse;

final class RescheduleAppointmentController
{
    public function __construct(private readonly RescheduleAppointmentUseCase $useCase)
    {
    }

    public function __invoke(int $appointment, RescheduleAppointmentRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($appointment, RescheduleAppointmentDTO::fromRequest($request));

        return AppointmentResource::make($updated)->response();
    }
}

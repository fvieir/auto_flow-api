<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Appointment;

use App\Application\Appointment\DTOs\UpdateAppointmentStatusDTO;
use App\Application\Appointment\UseCases\UpdateAppointmentStatusUseCase;
use App\Presentation\Http\Requests\Appointment\UpdateAppointmentStatusRequest;
use App\Presentation\Http\Resources\Appointment\AppointmentResource;
use Illuminate\Http\JsonResponse;

final class UpdateAppointmentStatusController
{
    public function __construct(private readonly UpdateAppointmentStatusUseCase $useCase)
    {
    }

    public function __invoke(int $appointment, UpdateAppointmentStatusRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($appointment, UpdateAppointmentStatusDTO::fromRequest($request));

        return AppointmentResource::make($updated)->response();
    }
}

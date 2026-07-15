<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Appointment;

use App\Application\Appointment\DTOs\CreateAppointmentDTO;
use App\Application\Appointment\UseCases\CreateAppointmentUseCase;
use App\Presentation\Http\Requests\Appointment\CreateAppointmentRequest;
use App\Presentation\Http\Resources\Appointment\AppointmentResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateAppointmentController
{
    public function __construct(private readonly CreateAppointmentUseCase $useCase)
    {
    }

    public function __invoke(CreateAppointmentRequest $request): JsonResponse
    {
        $appointment = $this->useCase->handle(CreateAppointmentDTO::fromRequest($request));

        return AppointmentResource::make($appointment)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}

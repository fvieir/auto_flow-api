<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\GetClientContextUseCase;
use App\Presentation\Http\Resources\Appointment\AppointmentResource;
use App\Presentation\Http\Resources\Client\ClientResource;
use Illuminate\Http\JsonResponse;

final class GetClientContextController
{
    public function __construct(private readonly GetClientContextUseCase $useCase)
    {
    }

    public function __invoke(int $client): JsonResponse
    {
        $context = $this->useCase->handle($client);

        return response()->json([
            'client' => ClientResource::make($context['client']),
            'upcoming_appointment' => $context['upcomingAppointment'] !== null
                ? AppointmentResource::make($context['upcomingAppointment'])
                : null,
        ]);
    }
}

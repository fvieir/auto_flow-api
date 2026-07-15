<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Appointment;

use App\Application\Appointment\UseCases\ListAvailabilityUseCase;
use App\Presentation\Http\Requests\Appointment\AvailabilityRequest;
use Illuminate\Http\JsonResponse;

final class ListAvailabilityController
{
    public function __construct(private readonly ListAvailabilityUseCase $useCase)
    {
    }

    public function __invoke(AvailabilityRequest $request): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        $availability = $this->useCase->handle(
            serviceId: (int) $validated['service_id'],
            data: $validated['data'],
            professionalId: isset($validated['professional_id']) ? (int) $validated['professional_id'] : null,
        );

        return response()->json(['data' => $availability]);
    }
}

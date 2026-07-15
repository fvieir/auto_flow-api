<?php

declare(strict_types=1);

namespace App\Application\Appointment\DTOs;

use App\Presentation\Http\Requests\Appointment\CreateAppointmentRequest;

final class CreateAppointmentDTO
{
    public function __construct(
        public readonly int $clientId,
        public readonly int $professionalId,
        public readonly int $serviceId,
        public readonly string $startsAt,
    ) {
    }

    public static function fromRequest(CreateAppointmentRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            clientId: (int) $validated['client_id'],
            professionalId: (int) $validated['professional_id'],
            serviceId: (int) $validated['service_id'],
            startsAt: $validated['starts_at'],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Appointment\DTOs;

use App\Presentation\Http\Requests\Appointment\RescheduleAppointmentRequest;

final class RescheduleAppointmentDTO
{
    public function __construct(
        public readonly string $startsAt,
    ) {
    }

    public static function fromRequest(RescheduleAppointmentRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(startsAt: $validated['starts_at']);
    }
}

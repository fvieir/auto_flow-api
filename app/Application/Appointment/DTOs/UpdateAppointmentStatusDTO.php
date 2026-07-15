<?php

declare(strict_types=1);

namespace App\Application\Appointment\DTOs;

use App\Presentation\Http\Requests\Appointment\UpdateAppointmentStatusRequest;

final class UpdateAppointmentStatusDTO
{
    public function __construct(
        public readonly string $status,
    ) {
    }

    public static function fromRequest(UpdateAppointmentStatusRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(status: $validated['status']);
    }
}

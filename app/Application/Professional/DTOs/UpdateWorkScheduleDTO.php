<?php

declare(strict_types=1);

namespace App\Application\Professional\DTOs;

use App\Presentation\Http\Requests\Professional\UpdateWorkScheduleRequest;

final class UpdateWorkScheduleDTO
{
    public function __construct(
        public readonly int $weekday,
        public readonly string $startTime,
        public readonly string $endTime,
    ) {
    }

    public static function fromRequest(UpdateWorkScheduleRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            weekday: (int) $validated['weekday'],
            startTime: $validated['start_time'],
            endTime: $validated['end_time'],
        );
    }
}

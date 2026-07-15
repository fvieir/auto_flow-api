<?php

declare(strict_types=1);

namespace App\Application\Professional\DTOs;

use App\Presentation\Http\Requests\Professional\CreateScheduleBlockRequest;
use DateTimeImmutable;
use DateTimeZone;

final class CreateScheduleBlockDTO
{
    public function __construct(
        public readonly DateTimeImmutable $startAt,
        public readonly DateTimeImmutable $endAt,
        public readonly ?string $reason,
    ) {
    }

    public static function fromRequest(CreateScheduleBlockRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            startAt: (new DateTimeImmutable($validated['start_at']))->setTimezone(new DateTimeZone('UTC')),
            endAt: (new DateTimeImmutable($validated['end_at']))->setTimezone(new DateTimeZone('UTC')),
            reason: $validated['reason'] ?? null,
        );
    }
}

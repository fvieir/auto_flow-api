<?php

declare(strict_types=1);

namespace App\Application\Service\DTOs;

use App\Presentation\Http\Requests\Service\CreateServiceRequest;

final class CreateServiceDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $durationMinutes,
        public readonly float $price,
    ) {
    }

    public static function fromRequest(CreateServiceRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'],
            durationMinutes: (int) $validated['duration_minutes'],
            price: (float) $validated['price'],
        );
    }
}

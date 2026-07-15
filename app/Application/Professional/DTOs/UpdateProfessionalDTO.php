<?php

declare(strict_types=1);

namespace App\Application\Professional\DTOs;

use App\Presentation\Http\Requests\Professional\UpdateProfessionalRequest;

final class UpdateProfessionalDTO
{
    /**
     * @param  list<int>  $serviceIds
     */
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly ?string $email,
        public readonly array $serviceIds,
    ) {
    }

    public static function fromRequest(UpdateProfessionalRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'],
            phone: $validated['phone'],
            email: $validated['email'] ?? null,
            serviceIds: $validated['service_ids'] ?? [],
        );
    }
}

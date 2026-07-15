<?php

declare(strict_types=1);

namespace App\Application\Client\DTOs;

use App\Presentation\Http\Requests\Client\CreateAddressRequest;

final class CreateAddressDTO
{
    public function __construct(
        public readonly string $postalCode,
        public readonly string $street,
        public readonly string $number,
        public readonly ?string $complement,
        public readonly string $neighborhood,
        public readonly string $city,
        public readonly string $state,
        public readonly bool $isPrimary,
    ) {
    }

    public static function fromRequest(CreateAddressRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            postalCode: $validated['postal_code'],
            street: $validated['street'],
            number: $validated['number'],
            complement: $validated['complement'] ?? null,
            neighborhood: $validated['neighborhood'],
            city: $validated['city'],
            state: $validated['state'],
            isPrimary: (bool) ($validated['is_primary'] ?? false),
        );
    }
}

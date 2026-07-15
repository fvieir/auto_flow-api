<?php

declare(strict_types=1);

namespace App\Application\Client\DTOs;

use App\Presentation\Http\Requests\Client\CreateClientRequest;

final class CreateClientDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $document,
    ) {
    }

    public static function fromRequest(CreateClientRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            phone: $validated['phone'],
            name: $validated['name'] ?? null,
            email: $validated['email'] ?? null,
            document: $validated['document'] ?? null,
        );
    }
}

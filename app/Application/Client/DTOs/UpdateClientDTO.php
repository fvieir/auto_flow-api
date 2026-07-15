<?php

declare(strict_types=1);

namespace App\Application\Client\DTOs;

use App\Presentation\Http\Requests\Client\UpdateClientRequest;

final class UpdateClientDTO
{
    public function __construct(
        public readonly ?string $phone,
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $document,
    ) {
    }

    public static function fromRequest(UpdateClientRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            phone: $validated['phone'] ?? null,
            name: $validated['name'] ?? null,
            email: $validated['email'] ?? null,
            document: $validated['document'] ?? null,
        );
    }
}

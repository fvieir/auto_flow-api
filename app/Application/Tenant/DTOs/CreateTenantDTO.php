<?php

declare(strict_types=1);

namespace App\Application\Tenant\DTOs;

use App\Presentation\Http\Requests\Tenant\CreateTenantRequest;

final class CreateTenantDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly string $timezone,
        public readonly string $adminName,
        public readonly string $adminEmail,
        public readonly string $adminPassword,
    ) {
    }

    public static function fromRequest(CreateTenantRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            name: $validated['name'],
            slug: $validated['slug'],
            timezone: $validated['timezone'] ?? 'America/Sao_Paulo',
            adminName: $validated['admin']['name'],
            adminEmail: $validated['admin']['email'],
            adminPassword: $validated['admin']['password'],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Tenant\UseCases;

use App\Application\Tenant\DTOs\CreateTenantDTO;
use App\Domain\Tenant\Entities\Tenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use App\Domain\Tenant\ValueObjects\TenantSlug;

/**
 * Onboarding básico: cria um tenant + seu usuário admin. A unicidade de
 * slug/email é validada no CreateTenantRequest (422); aqui só orquestramos.
 */
final class CreateTenantUseCase
{
    public function __construct(private readonly TenantRepositoryInterface $tenants)
    {
    }

    public function handle(CreateTenantDTO $dto): Tenant
    {
        $tenant = Tenant::createNew(
            name: $dto->name,
            slug: new TenantSlug($dto->slug),
            timezone: $dto->timezone,
        );

        return $this->tenants->createWithAdmin(
            $tenant,
            $dto->adminName,
            $dto->adminEmail,
            $dto->adminPassword,
        );
    }
}

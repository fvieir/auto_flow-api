<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Repositories;

use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Entities\Tenant;
use App\Domain\Tenant\ValueObjects\TenantSlug;

/**
 * Port do domínio Tenant. A implementação (adapter Eloquent) vive em
 * Infrastructure\Persistence\Tenant\Repositories e é ligada via ServiceProvider.
 */
interface TenantRepositoryInterface
{
    public function findById(TenantId $id): ?Tenant;

    public function existsBySlug(TenantSlug $slug): bool;

    /**
     * @return list<Tenant>
     */
    public function listActive(): array;

    /**
     * Persiste o tenant + o usuário admin + o vínculo em tenant_users (role=admin),
     * numa única transação. Retorna a entidade Tenant já com id.
     */
    public function createWithAdmin(
        Tenant $tenant,
        string $adminName,
        string $adminEmail,
        string $adminPassword,
    ): Tenant;
}

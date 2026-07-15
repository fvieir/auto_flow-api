<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tenant\Repositories;

use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Entities\Tenant;
use App\Domain\Tenant\Events\TenantCreated;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use App\Domain\Tenant\ValueObjects\TenantSlug;
use App\Infrastructure\Persistence\Tenant\Mappers\TenantMapper;
use App\Infrastructure\Persistence\Tenant\Models\TenantModel;
use App\Infrastructure\Persistence\User\Models\UserModel;
use Illuminate\Support\Facades\DB;

final class TenantRepository implements TenantRepositoryInterface
{
    public function __construct(private readonly TenantMapper $mapper)
    {
    }

    public function findById(TenantId $id): ?Tenant
    {
        $model = TenantModel::find($id->value());

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function existsBySlug(TenantSlug $slug): bool
    {
        return TenantModel::where('slug', $slug->value())->exists();
    }

    public function createWithAdmin(
        Tenant $tenant,
        string $adminName,
        string $adminEmail,
        string $adminPassword,
    ): Tenant {
        return DB::transaction(function () use ($tenant, $adminName, $adminEmail, $adminPassword): Tenant {
            $model = TenantModel::create([
                'name' => $tenant->name(),
                'slug' => $tenant->slug()->value(),
                'status' => $tenant->status()->value,
                'timezone' => $tenant->timezone(),
            ]);

            // O cast 'password' => 'hashed' no UserModel faz o hashing.
            $admin = UserModel::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => $adminPassword,
            ]);

            $model->users()->attach($admin->id, ['role' => 'admin']);

            $domainTenant = $this->mapper->toDomain($model);

            event(new TenantCreated($domainTenant->id(), (int) $admin->id));

            return $domainTenant;
        });
    }
}

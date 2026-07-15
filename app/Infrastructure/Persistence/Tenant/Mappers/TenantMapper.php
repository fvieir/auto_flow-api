<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tenant\Mappers;

use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Entities\Tenant;
use App\Domain\Tenant\Enums\TenantStatus;
use App\Domain\Tenant\ValueObjects\TenantSlug;
use App\Infrastructure\Persistence\Tenant\Models\TenantModel;

final class TenantMapper
{
    public function toDomain(TenantModel $model): Tenant
    {
        return Tenant::fromPersistence(
            id: new TenantId((int) $model->id),
            name: $model->name,
            slug: new TenantSlug($model->slug),
            status: TenantStatus::from($model->status),
            timezone: $model->timezone,
        );
    }
}

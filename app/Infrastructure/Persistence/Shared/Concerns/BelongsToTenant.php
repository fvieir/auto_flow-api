<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Shared\Concerns;

use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Infrastructure\Persistence\Shared\Scopes\TenantScope;
use App\Infrastructure\Persistence\Tenant\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Aplicada aos Models Eloquent "filhos" de um tenant. Injeta o tenant_id ao
 * criar e aplica o TenantScope (filtro default-on). Ver docs/multitenancy.md.
 *
 * NÃO usar no TenantModel: o Tenant é o nó raiz do isolamento, não pertence a
 * outro tenant.
 */
trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            $currentTenant = app(CurrentTenant::class);

            if (! $model->getAttribute('tenant_id') && $currentTenant->isResolved()) {
                $model->setAttribute('tenant_id', $currentTenant->id()->value());
            }
        });

        static::addGlobalScope(new TenantScope());
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(TenantModel::class, 'tenant_id');
    }
}

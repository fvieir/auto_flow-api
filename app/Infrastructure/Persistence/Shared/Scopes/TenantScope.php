<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Shared\Scopes;

use App\Domain\Shared\Tenancy\CurrentTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Injeta `WHERE tenant_id = ...` automaticamente em toda query dos Models que
 * usam a trait BelongsToTenant, com base no CurrentTenant resolvido. Isolamento
 * "default-on". Ver docs/multitenancy.md.
 */
final class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $currentTenant = app(CurrentTenant::class);

        if ($currentTenant->isResolved()) {
            $builder->where(
                $model->getTable() . '.tenant_id',
                $currentTenant->id()->value(),
            );
        }
    }
}

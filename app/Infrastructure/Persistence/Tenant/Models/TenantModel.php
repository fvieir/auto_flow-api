<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tenant\Models;

use App\Infrastructure\Persistence\User\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Eloquent da tabela `tenants`. NÃO usa BelongsToTenant: o Tenant é o nó
 * raiz do isolamento multi-tenant, não pertence a outro tenant.
 */
final class TenantModel extends Model
{
    use SoftDeletes;

    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'timezone',
        'gateway_customer_id',
        'coleta_entrega_habilitado',
    ];

    protected $casts = [
        'coleta_entrega_habilitado' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'tenant_users', 'tenant_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }
}

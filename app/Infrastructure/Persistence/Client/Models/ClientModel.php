<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Client\Models;

use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ClientModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'tenant_id',
        'phone',
        'name',
        'email',
        'document',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(ClientAddressModel::class, 'client_id');
    }
}

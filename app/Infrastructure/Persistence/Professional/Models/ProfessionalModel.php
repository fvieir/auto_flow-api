<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Models;

use App\Infrastructure\Persistence\Service\Models\ServiceModel;
use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ProfessionalModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'professionals';

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'email',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ServiceModel::class, 'professional_service', 'professional_id', 'service_id')
            ->withTimestamps();
    }
}

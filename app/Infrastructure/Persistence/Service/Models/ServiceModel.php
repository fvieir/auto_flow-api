<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Service\Models;

use App\Infrastructure\Persistence\Professional\Models\ProfessionalModel;
use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ServiceModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'tenant_id',
        'name',
        'duration_minutes',
        'price',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'price' => 'decimal:2',
    ];

    public function professionals(): BelongsToMany
    {
        return $this->belongsToMany(ProfessionalModel::class, 'professional_service', 'service_id', 'professional_id')
            ->withTimestamps();
    }
}

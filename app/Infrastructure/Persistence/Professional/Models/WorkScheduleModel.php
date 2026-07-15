<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Models;

use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WorkScheduleModel extends Model
{
    use BelongsToTenant;

    protected $table = 'professional_schedules';

    protected $fillable = [
        'tenant_id',
        'professional_id',
        'weekday',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'weekday' => 'integer',
    ];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(ProfessionalModel::class, 'professional_id');
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Models;

use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ScheduleBlockModel extends Model
{
    use BelongsToTenant;

    protected $table = 'schedule_blocks';

    protected $fillable = [
        'tenant_id',
        'professional_id',
        'start_at',
        'end_at',
        'reason',
    ];

    protected $casts = [
        'start_at' => 'immutable_datetime',
        'end_at' => 'immutable_datetime',
    ];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(ProfessionalModel::class, 'professional_id');
    }
}

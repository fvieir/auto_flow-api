<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Appointment\Models;

use App\Infrastructure\Persistence\Client\Models\ClientModel;
use App\Infrastructure\Persistence\Professional\Models\ProfessionalModel;
use App\Infrastructure\Persistence\Service\Models\ServiceModel;
use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AppointmentModel extends Model
{
    use BelongsToTenant;

    protected $table = 'appointments';

    protected $fillable = [
        'tenant_id',
        'client_id',
        'professional_id',
        'service_id',
        'starts_at',
        'duration_minutes',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'duration_minutes' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(ProfessionalModel::class, 'professional_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id');
    }
}

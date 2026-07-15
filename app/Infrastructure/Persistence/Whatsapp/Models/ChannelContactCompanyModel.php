<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Models;

use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

final class ChannelContactCompanyModel extends Model
{
    use BelongsToTenant;

    protected $table = 'channel_contact_companies';

    protected $fillable = [
        'channel_contact_id',
        'tenant_id',
        'client_id',
        'last_interaction_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_interaction_at' => 'datetime',
    ];
}

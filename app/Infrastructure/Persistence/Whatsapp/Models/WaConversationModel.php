<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Models;

use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class WaConversationModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'wa_conversations';

    protected $fillable = [
        'tenant_id',
        'channel_contact_id',
        'wa_phone_number_id',
        'status',
        'stage',
        'last_attendant_id',
        'resolved_at',
        'metadata',
        'pending_handoff_at',
        'pending_handoff_subject',
    ];

    protected $casts = [
        'metadata' => 'array',
        'resolved_at' => 'datetime',
        'pending_handoff_at' => 'datetime',
    ];
}

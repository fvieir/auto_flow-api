<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabela global (sem tenant_id/BelongsToTenant) — ver docs/multitenancy.md,
 * seção de exceção à multitenancy default-on.
 */
final class ChannelContactModel extends Model
{
    protected $table = 'channel_contacts';

    protected $fillable = [
        'channel',
        'phone',
        'external_id',
        'metadata',
        'last_interaction_at',
        'unlinked_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_interaction_at' => 'datetime',
        'unlinked_at' => 'datetime',
    ];
}

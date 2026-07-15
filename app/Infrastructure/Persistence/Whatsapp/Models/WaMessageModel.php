<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Sem tenant_id próprio: escopo herda de wa_conversations.tenant_id
 * (mesmo padrão de client_addresses em relação a clients).
 */
final class WaMessageModel extends Model
{
    use SoftDeletes;

    protected $table = 'wa_messages';

    protected $fillable = [
        'conversation_id',
        'wamid',
        'direction',
        'type',
        'body',
        'payload',
        'context_wamid',
        'sender_type',
        'sender_id',
        'received_at',
        'status',
        'status_updated_at',
        'status_error',
        'appointment_id',
    ];

    protected $casts = [
        'payload' => 'array',
        'received_at' => 'immutable_datetime',
        'status_updated_at' => 'immutable_datetime',
    ];
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * tenant_id é nullable e resolvido só durante o processamento (ver
 * ProcessWebhookEventJob) — não usa BelongsToTenant/TenantScope.
 */
final class WebhookEventModel extends Model
{
    protected $table = 'webhook_events';

    protected $fillable = [
        'tenant_id',
        'event_type',
        'payload',
        'processed',
        'processed_at',
        'notified_n8n_at',
        'error_message',
        'attempts',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
        'notified_n8n_at' => 'datetime',
    ];
}

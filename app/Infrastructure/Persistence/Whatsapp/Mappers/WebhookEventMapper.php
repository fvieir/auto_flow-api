<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Mappers;

use App\Domain\Whatsapp\Entities\WebhookEvent;
use App\Infrastructure\Persistence\Whatsapp\Models\WebhookEventModel;

final class WebhookEventMapper
{
    public function toDomain(WebhookEventModel $model): WebhookEvent
    {
        return WebhookEvent::fromPersistence(
            id: (int) $model->id,
            tenantId: $model->tenant_id !== null ? (int) $model->tenant_id : null,
            eventType: $model->event_type,
            payload: $model->payload,
            processed: (bool) $model->processed,
            processedAt: $model->processed_at?->toDateTimeImmutable(),
            notifiedN8nAt: $model->notified_n8n_at?->toDateTimeImmutable(),
            errorMessage: $model->error_message,
            attempts: (int) $model->attempts,
        );
    }
}

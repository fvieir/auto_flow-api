<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WebhookEvent;
use App\Domain\Whatsapp\Repositories\WebhookEventRepositoryInterface;
use App\Infrastructure\Persistence\Whatsapp\Mappers\WebhookEventMapper;
use App\Infrastructure\Persistence\Whatsapp\Models\WebhookEventModel;

final class WebhookEventRepository implements WebhookEventRepositoryInterface
{
    public function __construct(private readonly WebhookEventMapper $mapper)
    {
    }

    public function create(WebhookEvent $event): WebhookEvent
    {
        $model = WebhookEventModel::create([
            'tenant_id' => $event->tenantId(),
            'event_type' => $event->eventType(),
            'payload' => $event->payload(),
            'processed' => $event->processed(),
            'processed_at' => $event->processedAt(),
            'notified_n8n_at' => $event->notifiedN8nAt(),
            'error_message' => $event->errorMessage(),
            'attempts' => $event->attempts(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function findById(int $id): ?WebhookEvent
    {
        $model = WebhookEventModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function update(WebhookEvent $event): WebhookEvent
    {
        $model = WebhookEventModel::findOrFail($event->id());

        $model->update([
            'tenant_id' => $event->tenantId(),
            'processed' => $event->processed(),
            'processed_at' => $event->processedAt(),
            'notified_n8n_at' => $event->notifiedN8nAt(),
            'error_message' => $event->errorMessage(),
            'attempts' => $event->attempts(),
        ]);

        return $this->mapper->toDomain($model);
    }
}

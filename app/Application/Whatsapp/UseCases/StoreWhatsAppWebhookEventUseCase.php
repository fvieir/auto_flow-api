<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Domain\Whatsapp\Entities\WebhookEvent;
use App\Domain\Whatsapp\Repositories\WebhookEventRepositoryInterface;

final class StoreWhatsAppWebhookEventUseCase
{
    public function __construct(private readonly WebhookEventRepositoryInterface $webhookEvents)
    {
    }

    /**
     * @param  array<string, mixed>  $payload  payload bruto do Meta, sem parsing
     */
    public function handle(array $payload): WebhookEvent
    {
        $eventType = $payload['entry'][0]['changes'][0]['field'] ?? 'unknown';

        return $this->webhookEvents->create(WebhookEvent::createNew($eventType, $payload));
    }
}

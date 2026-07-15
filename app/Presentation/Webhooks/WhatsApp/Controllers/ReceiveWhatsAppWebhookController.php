<?php

declare(strict_types=1);

namespace App\Presentation\Webhooks\WhatsApp\Controllers;

use App\Application\Whatsapp\UseCases\StoreWhatsAppWebhookEventUseCase;
use App\Presentation\Queue\Jobs\ProcessWebhookEventJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Recebe o payload bruto do webhook da Meta, grava em webhook_events (sem
 * parsing síncrono) e enfileira o processamento — responde rápido para
 * evitar timeout/retry desnecessário do lado da Meta.
 */
final class ReceiveWhatsAppWebhookController
{
    public function __construct(private readonly StoreWhatsAppWebhookEventUseCase $useCase)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $payload */
        $payload = $request->json()->all();

        $event = $this->useCase->handle($payload);

        ProcessWebhookEventJob::dispatch($event->id());

        return response()->json(['status' => 'received']);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Integrations\Agent;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class N8nAgentNotifier
{
    public function __construct(
        private readonly ?string $clientAgentWebhookUrl,
        private readonly ?string $managerAgentWebhookUrl,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function notifyClientAgent(array $payload): void
    {
        $this->post($this->clientAgentWebhookUrl, $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function notifyManagerAgent(array $payload): void
    {
        $this->post($this->managerAgentWebhookUrl, $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function post(?string $webhookUrl, array $payload): void
    {
        if ($webhookUrl === null || $webhookUrl === '') {
            Log::info('N8nAgentNotifier: webhook não configurado, notificação descartada.', $payload);

            return;
        }

        Http::post($webhookUrl, $payload)->throw();
    }
}

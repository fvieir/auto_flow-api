<?php

declare(strict_types=1);

namespace App\Infrastructure\Integrations\Agent;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class N8nAgentNotifier
{
    public function __construct(private readonly ?string $webhookUrl)
    {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function notify(array $payload): void
    {
        if ($this->webhookUrl === null || $this->webhookUrl === '') {
            Log::info('N8nAgentNotifier: webhook não configurado, notificação descartada.', $payload);

            return;
        }

        Http::post($this->webhookUrl, $payload)->throw();
    }
}

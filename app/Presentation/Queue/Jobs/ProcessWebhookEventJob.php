<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Application\Whatsapp\UseCases\ProcessWebhookEventUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class ProcessWebhookEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public readonly int $webhookEventId)
    {
    }

    public function handle(ProcessWebhookEventUseCase $useCase): void
    {
        $useCase->handle($this->webhookEventId);
    }
}

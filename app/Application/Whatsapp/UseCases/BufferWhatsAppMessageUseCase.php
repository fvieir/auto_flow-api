<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\Support\MessageCompletenessClassifier;
use App\Infrastructure\Cache\WhatsAppMessageBuffer;
use App\Presentation\Queue\Jobs\FlushMessageBufferJob;

final class BufferWhatsAppMessageUseCase
{
    public function __construct(
        private readonly WhatsAppMessageBuffer $buffer,
        private readonly MessageCompletenessClassifier $classifier,
    ) {
    }

    public function handle(int $tenantId, int $conversationId, int $waMessageId, ?string $body): void
    {
        $now = microtime(true);

        $length = $this->buffer->push($conversationId, [
            'wa_message_id' => $waMessageId,
            'body' => $body,
            'received_at' => $now,
        ]);

        $hasPriorBufferedMessage = $length > 1;
        $version = $this->buffer->incrementVersion($conversationId);

        $classification = $this->classifier->classify((string) $body, $hasPriorBufferedMessage);

        $shortWaitMs = (int) config('services.whatsapp.buffer.short_wait_ms');
        $maxWaitMs = (int) config('services.whatsapp.buffer.max_wait_ms');

        $firstMessageAt = $this->buffer->firstMessageAt($conversationId) ?? $now;
        $elapsedMs = ($now - $firstMessageAt) * 1000;

        $candidateWaitMs = $classification === 'complete' ? $shortWaitMs : $maxWaitMs;
        $ceilingRemainingMs = max(0, $maxWaitMs - $elapsedMs);
        $delayMs = (int) round(min($candidateWaitMs, $ceilingRemainingMs));

        FlushMessageBufferJob::dispatch($tenantId, $conversationId, $version)
            ->delay(now()->addMilliseconds($delayMs));
    }
}

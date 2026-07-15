<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Events;

final class WhatsAppMessageReceived
{
    public function __construct(
        public readonly int $tenantId,
        public readonly int $conversationId,
        public readonly int $waMessageId,
    ) {
    }
}

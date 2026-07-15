<?php

declare(strict_types=1);

namespace App\Infrastructure\Broadcasting\WhatsApp;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

final class WaMessageStatusBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public readonly int $tenantId,
        public readonly int $conversationId,
        public readonly int $waMessageId,
        public readonly ?string $wamid,
        public readonly string $status,
        public readonly ?string $statusUpdatedAt,
    ) {
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("tenant.{$this->tenantId}.wa-conversations.{$this->conversationId}");
    }

    public function broadcastAs(): string
    {
        return 'message.status.updated';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'wa_message_id' => $this->waMessageId,
            'wamid' => $this->wamid,
            'status' => $this->status,
            'status_updated_at' => $this->statusUpdatedAt,
        ];
    }
}

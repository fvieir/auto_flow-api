<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Whatsapp;

use App\Domain\Whatsapp\Entities\WaMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read WaMessage $resource
 */
final class WaMessageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $message = $this->resource;

        return [
            'id' => $message->id(),
            'conversation_id' => $message->conversationId(),
            'wamid' => $message->wamid(),
            'direction' => $message->direction()->value,
            'type' => $message->type(),
            'body' => $message->body(),
            'sender_type' => $message->senderType()->value,
            'sender_id' => $message->senderId(),
        ];
    }
}

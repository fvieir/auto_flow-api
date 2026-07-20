<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Whatsapp;

use App\Domain\Whatsapp\Entities\WaConversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read WaConversation $resource
 */
final class WaConversationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $conversation = $this->resource;

        return [
            'id' => $conversation->id(),
            'tenant_id' => $conversation->tenantId(),
            'channel_contact_id' => $conversation->channelContactId(),
            'wa_phone_number_id' => $conversation->waPhoneNumberId(),
            'status' => $conversation->status()->value,
            'stage' => $conversation->stage()->value,
            'last_attendant_id' => $conversation->lastAttendantId(),
            'created_at' => $conversation->createdAt()?->format(DATE_ATOM),
            'resolved_at' => $conversation->resolvedAt()?->format(DATE_ATOM),
            'metadata' => $conversation->metadata(),
            'pending_handoff_at' => $conversation->pendingHandoffAt()?->format(DATE_ATOM),
            'pending_handoff_subject' => $conversation->pendingHandoffSubject(),
        ];
    }
}

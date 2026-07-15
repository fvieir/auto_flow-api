<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Mappers;

use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Enums\WaConversationStage;
use App\Domain\Whatsapp\Enums\WaConversationStatus;
use App\Infrastructure\Persistence\Whatsapp\Models\WaConversationModel;

final class WaConversationMapper
{
    public function toDomain(WaConversationModel $model): WaConversation
    {
        return WaConversation::fromPersistence(
            id: (int) $model->id,
            tenantId: (int) $model->tenant_id,
            channelContactId: (int) $model->channel_contact_id,
            waPhoneNumberId: (int) $model->wa_phone_number_id,
            status: WaConversationStatus::from($model->status),
            stage: WaConversationStage::from($model->stage),
            lastAttendantId: $model->last_attendant_id !== null ? (int) $model->last_attendant_id : null,
            createdAt: $model->created_at?->toDateTimeImmutable(),
            resolvedAt: $model->resolved_at?->toDateTimeImmutable(),
            metadata: $model->metadata,
        );
    }
}

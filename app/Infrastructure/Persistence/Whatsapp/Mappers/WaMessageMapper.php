<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Mappers;

use App\Domain\Whatsapp\Entities\WaMessage;
use App\Domain\Whatsapp\Enums\WaMessageDirection;
use App\Domain\Whatsapp\Enums\WaMessageSenderType;
use App\Domain\Whatsapp\Enums\WaMessageStatus;
use App\Infrastructure\Persistence\Whatsapp\Models\WaMessageModel;

final class WaMessageMapper
{
    public function toDomain(WaMessageModel $model): WaMessage
    {
        return WaMessage::fromPersistence(
            id: (int) $model->id,
            conversationId: (int) $model->conversation_id,
            wamid: $model->wamid,
            direction: WaMessageDirection::from($model->direction),
            type: $model->type,
            body: $model->body,
            payload: $model->payload,
            contextWamid: $model->context_wamid,
            senderType: WaMessageSenderType::from($model->sender_type),
            senderId: $model->sender_id !== null ? (int) $model->sender_id : null,
            receivedAt: $model->received_at?->toDateTimeImmutable(),
            status: $model->status !== null ? WaMessageStatus::from($model->status) : null,
            statusUpdatedAt: $model->status_updated_at?->toDateTimeImmutable(),
            statusError: $model->status_error,
        );
    }
}

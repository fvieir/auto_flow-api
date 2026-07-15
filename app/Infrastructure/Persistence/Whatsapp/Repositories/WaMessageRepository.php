<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WaMessage;
use App\Domain\Whatsapp\Enums\WaMessageStatus;
use App\Domain\Whatsapp\Repositories\WaMessageRepositoryInterface;
use App\Infrastructure\Persistence\Whatsapp\Mappers\WaMessageMapper;
use App\Infrastructure\Persistence\Whatsapp\Models\WaMessageModel;
use DateTimeImmutable;

final class WaMessageRepository implements WaMessageRepositoryInterface
{
    public function __construct(private readonly WaMessageMapper $mapper)
    {
    }

    public function create(WaMessage $message): WaMessage
    {
        $model = WaMessageModel::create([
            'conversation_id' => $message->conversationId(),
            'wamid' => $message->wamid(),
            'direction' => $message->direction()->value,
            'type' => $message->type(),
            'body' => $message->body(),
            'payload' => $message->payload(),
            'context_wamid' => $message->contextWamid(),
            'sender_type' => $message->senderType()->value,
            'sender_id' => $message->senderId(),
            'received_at' => $message->receivedAt(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function findByWamid(string $wamid): ?WaMessage
    {
        $model = WaMessageModel::where('wamid', $wamid)->first();

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function updateStatus(int $id, WaMessageStatus $status, DateTimeImmutable $updatedAt, ?string $error): WaMessage
    {
        $model = WaMessageModel::findOrFail($id);

        $model->update([
            'status' => $status->value,
            'status_updated_at' => $updatedAt,
            'status_error' => $error,
        ]);

        return $this->mapper->toDomain($model);
    }
}

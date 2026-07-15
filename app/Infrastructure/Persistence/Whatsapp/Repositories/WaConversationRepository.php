<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Enums\WaConversationStage;
use App\Domain\Whatsapp\Enums\WaConversationStatus;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Infrastructure\Persistence\Whatsapp\Mappers\WaConversationMapper;
use App\Infrastructure\Persistence\Whatsapp\Models\WaConversationModel;
use DateTimeImmutable;

final class WaConversationRepository implements WaConversationRepositoryInterface
{
    public function __construct(private readonly WaConversationMapper $mapper)
    {
    }

    public function findById(int $id): ?WaConversation
    {
        $model = WaConversationModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function findOpenByContactAndPhoneNumber(int $channelContactId, int $waPhoneNumberId): ?WaConversation
    {
        $model = WaConversationModel::where('channel_contact_id', $channelContactId)
            ->where('wa_phone_number_id', $waPhoneNumberId)
            ->where('status', WaConversationStatus::Open->value)
            ->latest('id')
            ->first();

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function create(WaConversation $conversation): WaConversation
    {
        $model = WaConversationModel::create([
            'tenant_id' => $conversation->tenantId(),
            'channel_contact_id' => $conversation->channelContactId(),
            'wa_phone_number_id' => $conversation->waPhoneNumberId(),
            'status' => $conversation->status()->value,
            'stage' => $conversation->stage()->value,
            'last_attendant_id' => $conversation->lastAttendantId(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function updateLastAttendant(int $id, ?int $attendantId): WaConversation
    {
        $model = WaConversationModel::findOrFail($id);

        $model->update(['last_attendant_id' => $attendantId]);

        return $this->mapper->toDomain($model);
    }

    public function updateStage(
        int $id,
        WaConversationStage $stage,
        WaConversationStatus $status,
        ?DateTimeImmutable $resolvedAt,
        ?array $metadata,
    ): WaConversation {
        $model = WaConversationModel::findOrFail($id);

        $model->update([
            'stage' => $stage->value,
            'status' => $status->value,
            'resolved_at' => $resolvedAt,
            'metadata' => $metadata,
        ]);

        return $this->mapper->toDomain($model);
    }

    public function list(?WaConversationStage $stage = null): array
    {
        return WaConversationModel::query()
            ->when($stage !== null, fn ($query) => $query->where('stage', $stage->value))
            ->latest('updated_at')
            ->get()
            ->map(fn (WaConversationModel $model) => $this->mapper->toDomain($model))
            ->all();
    }
}

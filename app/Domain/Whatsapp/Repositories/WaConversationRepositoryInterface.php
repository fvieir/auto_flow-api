<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Enums\WaConversationStage;
use App\Domain\Whatsapp\Enums\WaConversationStatus;
use DateTimeImmutable;

interface WaConversationRepositoryInterface
{
    public function findById(int $id): ?WaConversation;

    public function findOpenByContactAndPhoneNumber(int $channelContactId, int $waPhoneNumberId): ?WaConversation;

    public function create(WaConversation $conversation): WaConversation;

    public function updateLastAttendant(int $id, ?int $attendantId): WaConversation;

    /**
     * @param  ?array<string, mixed>  $metadata
     */
    public function updateStage(
        int $id,
        WaConversationStage $stage,
        WaConversationStatus $status,
        ?DateTimeImmutable $resolvedAt,
        ?array $metadata,
    ): WaConversation;

    /**
     * @return list<WaConversation>
     */
    public function list(?WaConversationStage $stage = null): array;
}

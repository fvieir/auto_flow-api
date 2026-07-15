<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\UpdateWhatsAppMessageStatusDTO;
use App\Domain\Whatsapp\Enums\WaMessageStatus;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaMessageRepositoryInterface;
use App\Infrastructure\Broadcasting\WhatsApp\WaMessageStatusBroadcastEvent;
use DateTimeImmutable;

final class UpdateWhatsAppMessageStatusUseCase
{
    public function __construct(
        private readonly WaMessageRepositoryInterface $messages,
        private readonly WaConversationRepositoryInterface $conversations,
    ) {
    }

    public function handle(UpdateWhatsAppMessageStatusDTO $dto): void
    {
        $status = WaMessageStatus::tryFrom($dto->status);

        if ($status === null) {
            return;
        }

        $message = $this->messages->findByWamid($dto->wamid);

        if ($message === null || $message->id() === null) {
            return;
        }

        $updated = $this->messages->updateStatus(
            $message->id(),
            $status,
            $dto->updatedAt ?? new DateTimeImmutable(),
            $dto->errorMessage,
        );

        $conversation = $this->conversations->findById($updated->conversationId());

        if ($conversation === null) {
            return;
        }

        broadcast(new WaMessageStatusBroadcastEvent(
            tenantId: $conversation->tenantId(),
            conversationId: $conversation->id(),
            waMessageId: $updated->id(),
            wamid: $updated->wamid(),
            status: $status->value,
            statusUpdatedAt: $updated->statusUpdatedAt()?->format(DATE_ATOM),
        ));
    }
}

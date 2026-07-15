<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\ReceiveWhatsAppMessageDTO;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Entities\WaMessage;
use App\Domain\Whatsapp\Entities\WaPhoneNumber;
use App\Domain\Whatsapp\Enums\WaMessageDirection;
use App\Domain\Whatsapp\Enums\WaMessageSenderType;
use App\Domain\Whatsapp\Events\WhatsAppMessageReceived;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaMessageRepositoryInterface;
use App\Presentation\Queue\Jobs\ProcessConfirmationResponseJob;

final class ReceiveWhatsAppMessageUseCase
{
    private const APPOINTMENT_BUTTON_IDS = ['appointment_confirm', 'appointment_cancel'];

    public function __construct(
        private readonly WaConversationRepositoryInterface $conversations,
        private readonly WaMessageRepositoryInterface $messages,
        private readonly ResolveOrCreateLeadUseCase $resolveOrCreateLead,
        private readonly BufferWhatsAppMessageUseCase $bufferMessage,
        private readonly CurrentTenant $currentTenant,
    ) {
    }

    public function handle(WaPhoneNumber $phoneNumber, ReceiveWhatsAppMessageDTO $dto): WaMessage
    {
        $tenantId = $phoneNumber->tenantId();
        $this->currentTenant->set(new TenantId($tenantId));

        $contact = $this->resolveOrCreateLead->handle('whatsapp', $dto->from, $dto->externalId, $tenantId);

        $conversation = $this->conversations->findOpenByContactAndPhoneNumber($contact->id(), $phoneNumber->id());

        if ($conversation === null) {
            $conversation = $this->conversations->create(
                WaConversation::createNew($tenantId, $contact->id(), $phoneNumber->id()),
            );
        }

        $message = $this->messages->create(WaMessage::createNew(
            conversationId: $conversation->id(),
            wamid: $dto->wamid,
            direction: WaMessageDirection::Inbound,
            type: $dto->type,
            body: $dto->body,
            payload: $dto->payload,
            contextWamid: $dto->contextWamid,
            senderType: WaMessageSenderType::Contact,
            senderId: null,
            receivedAt: $dto->receivedAt,
        ));

        event(new WhatsAppMessageReceived($tenantId, $conversation->id(), $message->id()));

        $buttonId = $dto->payload['interactive']['button_reply']['id'] ?? null;

        if ($dto->type === 'interactive' && in_array($buttonId, self::APPOINTMENT_BUTTON_IDS, true) && $message->contextWamid() !== null) {
            ProcessConfirmationResponseJob::dispatch($message->id());

            return $message;
        }

        $this->bufferMessage->handle($tenantId, $conversation->id(), $message->id(), $message->body());

        return $message;
    }
}

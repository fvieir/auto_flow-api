<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\SendWhatsAppMessageDTO;
use App\Domain\Whatsapp\Entities\WaMessage;
use App\Domain\Whatsapp\Enums\WaMessageDirection;
use App\Domain\Whatsapp\Enums\WaMessageSenderType;
use App\Domain\Whatsapp\Exceptions\WaConversationNotFoundException;
use App\Domain\Whatsapp\Exceptions\WaPhoneNumberNotFoundException;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaMessageRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;
use App\Infrastructure\Integrations\WhatsApp\WhatsAppClient;
use InvalidArgumentException;

final class SendWhatsAppMessageUseCase
{
    public function __construct(
        private readonly WaConversationRepositoryInterface $conversations,
        private readonly WaPhoneNumberRepositoryInterface $phoneNumbers,
        private readonly ChannelContactRepositoryInterface $channelContacts,
        private readonly WaMessageRepositoryInterface $messages,
        private readonly WhatsAppClient $client,
    ) {
    }

    public function handle(SendWhatsAppMessageDTO $dto): WaMessage
    {
        $conversation = $this->conversations->findById($dto->conversationId);

        if ($conversation === null) {
            throw new WaConversationNotFoundException($dto->conversationId);
        }

        $phoneNumber = $this->phoneNumbers->findById($conversation->waPhoneNumberId());

        if ($phoneNumber === null) {
            throw new WaPhoneNumberNotFoundException((string) $conversation->waPhoneNumberId());
        }

        $contact = $this->channelContacts->findById($conversation->channelContactId());

        if ($contact === null) {
            throw new WaConversationNotFoundException($dto->conversationId);
        }

        $response = match ($dto->type) {
            'text' => $this->client->sendText(
                $phoneNumber->phoneNumberId(),
                $phoneNumber->accessToken(),
                $contact->phone(),
                (string) $dto->body,
                $dto->previewUrl,
            ),
            'image' => $this->client->sendImage(
                $phoneNumber->phoneNumberId(),
                $phoneNumber->accessToken(),
                $contact->phone(),
                $dto->mediaId,
                $dto->mediaLink,
                $dto->caption,
            ),
            'document' => $this->client->sendDocument(
                $phoneNumber->phoneNumberId(),
                $phoneNumber->accessToken(),
                $contact->phone(),
                $dto->mediaId,
                $dto->mediaLink,
                $dto->caption,
                $dto->filename,
            ),
            'audio' => $this->client->sendAudio(
                $phoneNumber->phoneNumberId(),
                $phoneNumber->accessToken(),
                $contact->phone(),
                $dto->mediaId,
                $dto->mediaLink,
                $dto->voice,
            ),
            'sticker' => $this->client->sendSticker(
                $phoneNumber->phoneNumberId(),
                $phoneNumber->accessToken(),
                $contact->phone(),
                $dto->mediaId,
                $dto->mediaLink,
            ),
            'interactive' => $this->client->sendInteractive(
                $phoneNumber->phoneNumberId(),
                $phoneNumber->accessToken(),
                $contact->phone(),
                $dto->interactive ?? [],
            ),
            default => throw new InvalidArgumentException("Tipo de mensagem WhatsApp não suportado: {$dto->type}"),
        };

        $wamid = $response['messages'][0]['id'] ?? null;

        $message = WaMessage::createNew(
            conversationId: $conversation->id(),
            wamid: $wamid,
            direction: WaMessageDirection::Outbound,
            type: $dto->type,
            body: $dto->body,
            payload: $response,
            contextWamid: null,
            senderType: WaMessageSenderType::from($dto->senderType),
            senderId: $dto->senderId,
            receivedAt: null,
            appointmentId: $dto->appointmentId,
        );

        return $this->messages->create($message);
    }
}

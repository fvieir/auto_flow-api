<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\StartConversationDTO;
use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Whatsapp\Entities\ChannelContact;
use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Entities\WaPhoneNumber;
use App\Domain\Whatsapp\Exceptions\ChannelContactNotFoundException;
use App\Domain\Whatsapp\Exceptions\WaPhoneNumberNotFoundException;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;

final class StartConversationUseCase
{
    public function __construct(
        private readonly ChannelContactRepositoryInterface $channelContacts,
        private readonly ClientRepositoryInterface $clients,
        private readonly WaConversationRepositoryInterface $conversations,
        private readonly WaPhoneNumberRepositoryInterface $phoneNumbers,
        private readonly ResolveOrCreateLeadUseCase $resolveOrCreateLead,
        private readonly CurrentTenant $currentTenant,
    ) {
    }

    public function handle(StartConversationDTO $dto): WaConversation
    {
        $tenantId = $this->currentTenant->id()->value();

        $phoneNumber = $this->resolvePhoneNumber($dto->waPhoneNumberId, $tenantId);
        $contact = $this->resolveContact($dto, $tenantId);

        $conversation = $this->conversations->findOpenByContactAndPhoneNumber($contact->id(), $phoneNumber->id());

        if ($conversation === null) {
            return $this->conversations->create(
                WaConversation::createNew($tenantId, $contact->id(), $phoneNumber->id(), $dto->attendantId),
            );
        }

        return $this->conversations->updateLastAttendant($conversation->id(), $dto->attendantId);
    }

    private function resolveContact(StartConversationDTO $dto, int $tenantId): ChannelContact
    {
        if ($dto->channelContactId !== null) {
            $contact = $this->channelContacts->findById($dto->channelContactId);

            if ($contact === null) {
                throw new ChannelContactNotFoundException($dto->channelContactId);
            }

            $this->resolveOrCreateLead->ensureLinkedToTenant($contact, $contact->phone(), $tenantId);

            return $contact;
        }

        if ($dto->clientId !== null) {
            $client = $this->clients->findById($dto->clientId);

            if ($client === null) {
                throw new ClientNotFoundException($dto->clientId);
            }

            return $this->resolveOrCreateLead->handle(
                'whatsapp',
                $client->phone(),
                $client->phone(),
                $tenantId,
                $client->name(),
                $client->document(),
            );
        }

        return $this->resolveOrCreateLead->handle('whatsapp', (string) $dto->phone, $dto->phone, $tenantId, $dto->name, $dto->document);
    }

    private function resolvePhoneNumber(?int $waPhoneNumberId, int $tenantId): WaPhoneNumber
    {
        if ($waPhoneNumberId !== null) {
            $phoneNumber = $this->phoneNumbers->findById($waPhoneNumberId);

            if ($phoneNumber === null || $phoneNumber->tenantId() !== $tenantId) {
                throw new WaPhoneNumberNotFoundException((string) $waPhoneNumberId);
            }

            return $phoneNumber;
        }

        $active = $this->phoneNumbers->listActive();

        if (count($active) !== 1) {
            throw new WaPhoneNumberNotFoundException('(nenhum wa_phone_number_id informado, e o tenant não tem exatamente 1 número ativo)');
        }

        return $active[0];
    }
}

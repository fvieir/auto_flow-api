<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Whatsapp\Entities\ChannelContact;
use App\Domain\Whatsapp\Entities\ChannelContactCompany;
use App\Domain\Whatsapp\Repositories\ChannelContactCompanyRepositoryInterface;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;

/**
 * Compartilhado entre o webhook inbound (ReceiveWhatsAppMessageUseCase) e o
 * início manual de conversa pelo atendente (StartConversationUseCase): dado
 * um canal+telefone, resolve/cria o channel_contact (global) e garante o
 * vínculo channel_contact_companies + Client mínimo para o tenant atual —
 * mesma regra de "cadastro do lead no primeiro contato" nos dois casos.
 */
final class ResolveOrCreateLeadUseCase
{
    public function __construct(
        private readonly ChannelContactRepositoryInterface $channelContacts,
        private readonly ChannelContactCompanyRepositoryInterface $channelContactCompanies,
        private readonly ClientRepositoryInterface $clients,
    ) {
    }

    public function handle(
        string $channel,
        string $phone,
        ?string $externalId,
        int $tenantId,
        ?string $name = null,
        ?string $document = null,
    ): ChannelContact {
        $contact = $this->channelContacts->findActiveByChannelAndPhone($channel, $phone);

        if ($contact === null) {
            $contact = $this->channelContacts->create(ChannelContact::createNew($channel, $phone, $externalId));
        }

        $this->ensureLinkedToTenant($contact, $phone, $tenantId, $name, $document);

        return $contact;
    }

    public function ensureLinkedToTenant(
        ChannelContact $contact,
        string $phone,
        int $tenantId,
        ?string $name = null,
        ?string $document = null,
    ): void {
        if ($this->channelContactCompanies->findByContactAndTenant($contact->id(), $tenantId) !== null) {
            return;
        }

        $client = $this->clients->findByPhone($phone);

        if ($client === null) {
            $client = $this->clients->create(Client::createNew($phone, $name, null, $document));
        }

        $this->channelContactCompanies->create(
            ChannelContactCompany::createNew($contact->id(), $tenantId, $client->id()),
        );
    }
}

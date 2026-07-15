<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Whatsapp\Exceptions\WaPhoneNumberNotFoundException;
use App\Domain\Whatsapp\Repositories\ChannelContactCompanyRepositoryInterface;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;

final class ResolveWhatsAppContextUseCase
{
    public function __construct(
        private readonly WaPhoneNumberRepositoryInterface $waPhoneNumbers,
        private readonly ChannelContactRepositoryInterface $channelContacts,
        private readonly ChannelContactCompanyRepositoryInterface $channelContactCompanies,
        private readonly ClientRepositoryInterface $clients,
    ) {
    }

    /**
     * @return array{tenantId: int, client: ?Client}
     */
    public function handle(string $phone, string $phoneNumberId): array
    {
        $waPhoneNumber = $this->waPhoneNumbers->findByPhoneNumberId($phoneNumberId);

        if ($waPhoneNumber === null) {
            throw new WaPhoneNumberNotFoundException($phoneNumberId);
        }

        $tenantId = $waPhoneNumber->tenantId();

        $contact = $this->channelContacts->findActiveByChannelAndPhone('whatsapp', $phone);

        if ($contact === null || $contact->id() === null) {
            return ['tenantId' => $tenantId, 'client' => null];
        }

        $company = $this->channelContactCompanies->findByContactAndTenant($contact->id(), $tenantId);

        if ($company === null || $company->clientId() === null) {
            return ['tenantId' => $tenantId, 'client' => null];
        }

        return ['tenantId' => $tenantId, 'client' => $this->clients->findById($company->clientId())];
    }
}

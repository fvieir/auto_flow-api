<?php

declare(strict_types=1);

namespace App\Application\Notification\UseCases;

use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;
use App\Infrastructure\Integrations\WhatsApp\WhatsAppClient;
use Illuminate\Support\Facades\Log;

/**
 * Alerta direto ao dono do tenant (resumo matinal, cancelamento) — não passa
 * por wa_conversations/wa_messages porque não é uma conversa com cliente.
 */
final class NotifyManagerUseCase
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly WaPhoneNumberRepositoryInterface $phoneNumbers,
        private readonly WhatsAppClient $client,
    ) {
    }

    public function handle(int $tenantId, string $text): void
    {
        $tenant = $this->tenants->findById(new TenantId($tenantId));

        if ($tenant === null || $tenant->managerPhone() === null) {
            Log::warning('NotifyManagerUseCase: tenant sem manager_phone configurado.', ['tenant_id' => $tenantId]);

            return;
        }

        $activeNumbers = $this->phoneNumbers->listActive();

        if ($activeNumbers === []) {
            Log::warning('NotifyManagerUseCase: tenant sem número WhatsApp ativo.', ['tenant_id' => $tenantId]);

            return;
        }

        $phoneNumber = $activeNumbers[0];

        $this->client->sendText($phoneNumber->phoneNumberId(), $phoneNumber->accessToken(), $tenant->managerPhone(), $text);
    }
}

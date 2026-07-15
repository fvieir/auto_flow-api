<?php

declare(strict_types=1);

namespace App\Application\Notification\UseCases;

use App\Application\Whatsapp\UseCases\ResolveOrCreateLeadUseCase;
use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;

/**
 * Resolve/cria a wa_conversation de um Cliente fora do fluxo HTTP (lembrete,
 * avaliação pós-atendimento) — não usa StartConversationUseCase porque seu DTO
 * exige um attendantId (só faz sentido para atendente humano via HTTP).
 * Assume que o CurrentTenant já foi setado pelo caller.
 */
final class ResolveClientConversationUseCase
{
    public function __construct(
        private readonly WaPhoneNumberRepositoryInterface $phoneNumbers,
        private readonly WaConversationRepositoryInterface $conversations,
        private readonly ResolveOrCreateLeadUseCase $resolveOrCreateLead,
    ) {
    }

    public function handle(int $tenantId, string $phone, ?string $name = null, ?string $document = null): ?WaConversation
    {
        $activeNumbers = $this->phoneNumbers->listActive();

        if ($activeNumbers === []) {
            return null;
        }

        $phoneNumber = $activeNumbers[0];

        $contact = $this->resolveOrCreateLead->handle('whatsapp', $phone, $phone, $tenantId, $name, $document);

        $conversation = $this->conversations->findOpenByContactAndPhoneNumber($contact->id(), $phoneNumber->id());

        return $conversation ?? $this->conversations->create(
            WaConversation::createNew($tenantId, $contact->id(), $phoneNumber->id()),
        );
    }
}

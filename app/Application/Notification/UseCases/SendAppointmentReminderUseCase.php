<?php

declare(strict_types=1);

namespace App\Application\Notification\UseCases;

use App\Application\Appointment\Support\TenantClock;
use App\Application\Whatsapp\DTOs\SendWhatsAppMessageDTO;
use App\Application\Whatsapp\UseCases\SendWhatsAppMessageUseCase;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Log;

final class SendAppointmentReminderUseCase
{
    public function __construct(
        private readonly ClientRepositoryInterface $clients,
        private readonly ProfessionalRepositoryInterface $professionals,
        private readonly ServiceRepositoryInterface $services,
        private readonly ResolveClientConversationUseCase $resolveConversation,
        private readonly SendWhatsAppMessageUseCase $sendMessage,
    ) {
    }

    public function handle(Appointment $appointment, int $tenantId, string $tenantTimezone): void
    {
        $client = $this->clients->findById($appointment->clientId());

        if ($client === null) {
            Log::warning('SendAppointmentReminderUseCase: cliente não encontrado.', ['appointment_id' => $appointment->id()]);

            return;
        }

        $conversation = $this->resolveConversation->handle($tenantId, $client->phone(), $client->name(), $client->document());

        if ($conversation === null) {
            Log::warning('SendAppointmentReminderUseCase: tenant sem número WhatsApp ativo.', ['tenant_id' => $tenantId]);

            return;
        }

        $professional = $this->professionals->findById($appointment->professionalId());
        $service = $this->services->findById($appointment->serviceId());
        $localDateTime = TenantClock::formatLocal($appointment->startsAt(), $tenantTimezone);

        $body = sprintf(
            "Olá! Passando para lembrar do seu agendamento de %s com %s, amanhã às %s. Confirma?",
            $service?->name() ?? 'serviço',
            $professional?->name() ?? 'profissional',
            substr($localDateTime, 11, 5),
        );

        $this->sendMessage->handle(new SendWhatsAppMessageDTO(
            conversationId: $conversation->id(),
            type: 'interactive',
            body: null,
            previewUrl: false,
            mediaId: null,
            mediaLink: null,
            caption: null,
            filename: null,
            voice: false,
            interactive: [
                'type' => 'button',
                'body' => ['text' => $body],
                'action' => [
                    'buttons' => [
                        ['type' => 'reply', 'reply' => ['id' => 'appointment_confirm', 'title' => 'Confirmar']],
                        ['type' => 'reply', 'reply' => ['id' => 'appointment_cancel', 'title' => 'Cancelar']],
                    ],
                ],
            ],
            senderType: 'system',
            senderId: null,
            appointmentId: $appointment->id(),
        ));
    }
}

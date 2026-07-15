<?php

declare(strict_types=1);

namespace App\Application\Notification\UseCases;

use App\Application\Whatsapp\DTOs\SendWhatsAppMessageDTO;
use App\Application\Whatsapp\UseCases\SendWhatsAppMessageUseCase;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use Illuminate\Support\Facades\Log;

final class SendAppointmentReviewRequestUseCase
{
    public function __construct(
        private readonly ClientRepositoryInterface $clients,
        private readonly ResolveClientConversationUseCase $resolveConversation,
        private readonly SendWhatsAppMessageUseCase $sendMessage,
    ) {
    }

    public function handle(Appointment $appointment, int $tenantId): void
    {
        $client = $this->clients->findById($appointment->clientId());

        if ($client === null) {
            Log::warning('SendAppointmentReviewRequestUseCase: cliente não encontrado.', ['appointment_id' => $appointment->id()]);

            return;
        }

        $conversation = $this->resolveConversation->handle($tenantId, $client->phone(), $client->name(), $client->document());

        if ($conversation === null) {
            Log::warning('SendAppointmentReviewRequestUseCase: tenant sem número WhatsApp ativo.', ['tenant_id' => $tenantId]);

            return;
        }

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
                'type' => 'list',
                'body' => ['text' => 'Como você avalia o atendimento de hoje?'],
                'action' => [
                    'button' => 'Avaliar',
                    'sections' => [
                        [
                            'title' => 'Nota',
                            'rows' => [
                                ['id' => 'appointment_review_1', 'title' => '1 - Muito ruim'],
                                ['id' => 'appointment_review_2', 'title' => '2 - Ruim'],
                                ['id' => 'appointment_review_3', 'title' => '3 - Regular'],
                                ['id' => 'appointment_review_4', 'title' => '4 - Bom'],
                                ['id' => 'appointment_review_5', 'title' => '5 - Ótimo'],
                            ],
                        ],
                    ],
                ],
            ],
            senderType: 'system',
            senderId: null,
            appointmentId: $appointment->id(),
        ));
    }
}

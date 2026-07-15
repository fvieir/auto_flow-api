<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Application\Appointment\DTOs\UpdateAppointmentStatusDTO;
use App\Application\Appointment\UseCases\CancelAppointmentUseCase;
use App\Application\Appointment\UseCases\UpdateAppointmentStatusUseCase;
use App\Application\Whatsapp\DTOs\SendWhatsAppMessageDTO;
use App\Application\Whatsapp\UseCases\SendWhatsAppMessageUseCase;
use App\Domain\Appointment\Exceptions\AppointmentNotFoundException;
use App\Domain\Appointment\Exceptions\InvalidStatusTransitionException;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaMessageRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

final class ProcessConfirmationResponseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public readonly int $waMessageId)
    {
    }

    public function handle(
        WaMessageRepositoryInterface $messages,
        WaConversationRepositoryInterface $conversations,
        UpdateAppointmentStatusUseCase $updateStatus,
        CancelAppointmentUseCase $cancelAppointment,
        SendWhatsAppMessageUseCase $sendMessage,
        CurrentTenant $currentTenant,
    ): void {
        $inbound = $messages->findById($this->waMessageId);

        if ($inbound === null || $inbound->contextWamid() === null) {
            return;
        }

        $reminder = $messages->findByWamid($inbound->contextWamid());

        if ($reminder === null || $reminder->appointmentId() === null) {
            return;
        }

        $conversation = $conversations->findById($reminder->conversationId());

        if ($conversation === null) {
            return;
        }

        $currentTenant->set(new TenantId($conversation->tenantId()));

        $buttonId = $inbound->payload()['interactive']['button_reply']['id'] ?? null;
        $appointmentId = $reminder->appointmentId();

        try {
            $ackText = match ($buttonId) {
                'appointment_confirm' => $this->confirm($updateStatus, $appointmentId),
                'appointment_cancel' => $this->cancel($cancelAppointment, $appointmentId),
                default => null,
            };
        } catch (AppointmentNotFoundException|InvalidStatusTransitionException $e) {
            Log::warning('ProcessConfirmationResponseJob: transição inválida.', [
                'appointment_id' => $appointmentId,
                'button_id' => $buttonId,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        if ($ackText === null) {
            return;
        }

        $sendMessage->handle(new SendWhatsAppMessageDTO(
            conversationId: $conversation->id(),
            type: 'text',
            body: $ackText,
            previewUrl: false,
            mediaId: null,
            mediaLink: null,
            caption: null,
            filename: null,
            voice: false,
            interactive: null,
            senderType: 'system',
            senderId: null,
            appointmentId: $appointmentId,
        ));
    }

    private function confirm(UpdateAppointmentStatusUseCase $updateStatus, int $appointmentId): string
    {
        $updateStatus->handle($appointmentId, new UpdateAppointmentStatusDTO('confirmed'));

        return 'Agendamento confirmado! Te esperamos.';
    }

    private function cancel(CancelAppointmentUseCase $cancelAppointment, int $appointmentId): string
    {
        $cancelAppointment->handle($appointmentId);

        return 'Agendamento cancelado.';
    }
}

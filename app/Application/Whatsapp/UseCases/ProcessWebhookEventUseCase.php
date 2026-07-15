<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\ReceiveWhatsAppMessageDTO;
use App\Application\Whatsapp\DTOs\UpdateWhatsAppMessageStatusDTO;
use App\Domain\Whatsapp\Exceptions\WaPhoneNumberNotFoundException;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WebhookEventRepositoryInterface;
use DateTimeImmutable;
use Throwable;

final class ProcessWebhookEventUseCase
{
    public function __construct(
        private readonly WebhookEventRepositoryInterface $webhookEvents,
        private readonly WaPhoneNumberRepositoryInterface $phoneNumbers,
        private readonly ReceiveWhatsAppMessageUseCase $receiveMessage,
        private readonly UpdateWhatsAppMessageStatusUseCase $updateStatus,
    ) {
    }

    public function handle(int $webhookEventId): void
    {
        $event = $this->webhookEvents->findById($webhookEventId);

        if ($event === null) {
            return;
        }

        $tenantId = $event->tenantId();

        try {
            foreach ($event->payload()['entry'] ?? [] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    if (($change['field'] ?? null) !== 'messages') {
                        continue;
                    }

                    $value = $change['value'] ?? [];

                    if (! empty($value['messages'])) {
                        $phoneNumberId = $value['metadata']['phone_number_id'] ?? null;

                        if ($phoneNumberId === null) {
                            continue;
                        }

                        $phoneNumber = $this->phoneNumbers->findByPhoneNumberId((string) $phoneNumberId);

                        if ($phoneNumber === null) {
                            throw new WaPhoneNumberNotFoundException((string) $phoneNumberId);
                        }

                        $tenantId ??= $phoneNumber->tenantId();

                        foreach ($value['messages'] as $message) {
                            $this->receiveMessage->handle(
                                $phoneNumber,
                                ReceiveWhatsAppMessageDTO::fromMetaMessage($message, $value['contacts'] ?? []),
                            );
                        }
                    }

                    foreach ($value['statuses'] ?? [] as $status) {
                        $this->updateStatus->handle(UpdateWhatsAppMessageStatusDTO::fromMetaStatus($status));
                    }
                }
            }

            if ($tenantId !== null) {
                $event = $event->withTenantId($tenantId);
            }

            $event = $event->markProcessed(new DateTimeImmutable());
        } catch (Throwable $e) {
            $event = $tenantId !== null ? $event->withTenantId($tenantId) : $event;
            $event = $event->markFailed($e->getMessage());
        }

        $this->webhookEvents->update($event);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\UpdateConversationStageDTO;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Enums\WaConversationStage;
use App\Domain\Whatsapp\Enums\WaConversationStatus;
use App\Domain\Whatsapp\Exceptions\ConversionRequiresAppointmentException;
use App\Domain\Whatsapp\Exceptions\WaConversationNotFoundException;
use App\Domain\Whatsapp\Repositories\ChannelContactCompanyRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use DateTimeImmutable;

final class UpdateConversationStageUseCase
{
    public function __construct(
        private readonly WaConversationRepositoryInterface $conversations,
        private readonly ChannelContactCompanyRepositoryInterface $channelContactCompanies,
        private readonly AppointmentRepositoryInterface $appointments,
    ) {
    }

    public function handle(int $id, UpdateConversationStageDTO $dto): WaConversation
    {
        $conversation = $this->conversations->findById($id);

        if ($conversation === null) {
            throw new WaConversationNotFoundException($id);
        }

        $stage = WaConversationStage::from($dto->stage);

        [$status, $resolvedAt, $metadata] = match ($stage) {
            WaConversationStage::Converted => $this->prepareConverted($conversation, $dto),
            WaConversationStage::Lost => [
                WaConversationStatus::Resolved,
                new DateTimeImmutable(),
                $dto->reason !== null ? ['reason' => $dto->reason] : null,
            ],
            default => [WaConversationStatus::Open, null, null],
        };

        return $this->conversations->updateStage($id, $stage, $status, $resolvedAt, $metadata);
    }

    /**
     * @return array{0: WaConversationStatus, 1: DateTimeImmutable, 2: ?array<string, mixed>}
     */
    private function prepareConverted(WaConversation $conversation, UpdateConversationStageDTO $dto): array
    {
        if ($dto->appointmentId !== null) {
            return [WaConversationStatus::Resolved, new DateTimeImmutable(), ['appointment_id' => $dto->appointmentId]];
        }

        $company = $this->channelContactCompanies->findByContactAndTenant($conversation->channelContactId(), $conversation->tenantId());
        $since = $conversation->createdAt() ?? new DateTimeImmutable('@0');

        if (
            $company === null
            || $company->clientId() === null
            || ! $this->appointments->existsForClientCreatedAfter($company->clientId(), $since)
        ) {
            throw new ConversionRequiresAppointmentException();
        }

        return [WaConversationStatus::Resolved, new DateTimeImmutable(), null];
    }
}

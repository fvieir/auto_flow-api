<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Entities;

use App\Domain\Whatsapp\Enums\WaConversationStage;
use App\Domain\Whatsapp\Enums\WaConversationStatus;
use DateTimeImmutable;

final class WaConversation
{
    private function __construct(
        private ?int $id,
        private int $tenantId,
        private int $channelContactId,
        private int $waPhoneNumberId,
        private WaConversationStatus $status,
        private WaConversationStage $stage,
        private ?int $lastAttendantId,
        private ?DateTimeImmutable $createdAt = null,
        private ?DateTimeImmutable $resolvedAt = null,
        private ?array $metadata = null,
    ) {
    }

    public static function createNew(int $tenantId, int $channelContactId, int $waPhoneNumberId, ?int $lastAttendantId = null): self
    {
        return new self(null, $tenantId, $channelContactId, $waPhoneNumberId, WaConversationStatus::Open, WaConversationStage::New, $lastAttendantId);
    }

    /**
     * @param  ?array<string, mixed>  $metadata
     */
    public static function fromPersistence(
        int $id,
        int $tenantId,
        int $channelContactId,
        int $waPhoneNumberId,
        WaConversationStatus $status,
        WaConversationStage $stage,
        ?int $lastAttendantId,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $resolvedAt = null,
        ?array $metadata = null,
    ): self {
        return new self($id, $tenantId, $channelContactId, $waPhoneNumberId, $status, $stage, $lastAttendantId, $createdAt, $resolvedAt, $metadata);
    }

    /**
     * @param  ?array<string, mixed>  $metadata
     */
    public function withStageChange(WaConversationStage $stage, WaConversationStatus $status, ?DateTimeImmutable $resolvedAt, ?array $metadata): self
    {
        return new self(
            $this->id,
            $this->tenantId,
            $this->channelContactId,
            $this->waPhoneNumberId,
            $status,
            $stage,
            $this->lastAttendantId,
            $this->createdAt,
            $resolvedAt,
            $metadata,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function tenantId(): int
    {
        return $this->tenantId;
    }

    public function channelContactId(): int
    {
        return $this->channelContactId;
    }

    public function waPhoneNumberId(): int
    {
        return $this->waPhoneNumberId;
    }

    public function status(): WaConversationStatus
    {
        return $this->status;
    }

    public function stage(): WaConversationStage
    {
        return $this->stage;
    }

    public function lastAttendantId(): ?int
    {
        return $this->lastAttendantId;
    }

    public function createdAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function resolvedAt(): ?DateTimeImmutable
    {
        return $this->resolvedAt;
    }

    /**
     * @return ?array<string, mixed>
     */
    public function metadata(): ?array
    {
        return $this->metadata;
    }
}

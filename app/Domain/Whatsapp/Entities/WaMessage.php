<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Entities;

use App\Domain\Whatsapp\Enums\WaMessageDirection;
use App\Domain\Whatsapp\Enums\WaMessageSenderType;
use App\Domain\Whatsapp\Enums\WaMessageStatus;
use DateTimeImmutable;

final class WaMessage
{
    private function __construct(
        private ?int $id,
        private int $conversationId,
        private ?string $wamid,
        private WaMessageDirection $direction,
        private string $type,
        private ?string $body,
        private ?array $payload,
        private ?string $contextWamid,
        private WaMessageSenderType $senderType,
        private ?int $senderId,
        private ?DateTimeImmutable $receivedAt,
        private ?WaMessageStatus $status = null,
        private ?DateTimeImmutable $statusUpdatedAt = null,
        private ?string $statusError = null,
        private ?int $appointmentId = null,
    ) {
    }

    /**
     * @param  ?array<string, mixed>  $payload
     */
    public static function createNew(
        int $conversationId,
        ?string $wamid,
        WaMessageDirection $direction,
        string $type,
        ?string $body,
        ?array $payload,
        ?string $contextWamid,
        WaMessageSenderType $senderType,
        ?int $senderId,
        ?DateTimeImmutable $receivedAt,
        ?int $appointmentId = null,
    ): self {
        return new self(null, $conversationId, $wamid, $direction, $type, $body, $payload, $contextWamid, $senderType, $senderId, $receivedAt, null, null, null, $appointmentId);
    }

    /**
     * @param  ?array<string, mixed>  $payload
     */
    public static function fromPersistence(
        int $id,
        int $conversationId,
        ?string $wamid,
        WaMessageDirection $direction,
        string $type,
        ?string $body,
        ?array $payload,
        ?string $contextWamid,
        WaMessageSenderType $senderType,
        ?int $senderId,
        ?DateTimeImmutable $receivedAt,
        ?WaMessageStatus $status = null,
        ?DateTimeImmutable $statusUpdatedAt = null,
        ?string $statusError = null,
        ?int $appointmentId = null,
    ): self {
        return new self($id, $conversationId, $wamid, $direction, $type, $body, $payload, $contextWamid, $senderType, $senderId, $receivedAt, $status, $statusUpdatedAt, $statusError, $appointmentId);
    }

    public function withStatus(WaMessageStatus $status, DateTimeImmutable $updatedAt, ?string $error): self
    {
        return new self(
            $this->id,
            $this->conversationId,
            $this->wamid,
            $this->direction,
            $this->type,
            $this->body,
            $this->payload,
            $this->contextWamid,
            $this->senderType,
            $this->senderId,
            $this->receivedAt,
            $status,
            $updatedAt,
            $error,
            $this->appointmentId,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function conversationId(): int
    {
        return $this->conversationId;
    }

    public function wamid(): ?string
    {
        return $this->wamid;
    }

    public function direction(): WaMessageDirection
    {
        return $this->direction;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function body(): ?string
    {
        return $this->body;
    }

    /**
     * @return ?array<string, mixed>
     */
    public function payload(): ?array
    {
        return $this->payload;
    }

    public function contextWamid(): ?string
    {
        return $this->contextWamid;
    }

    public function senderType(): WaMessageSenderType
    {
        return $this->senderType;
    }

    public function senderId(): ?int
    {
        return $this->senderId;
    }

    public function receivedAt(): ?DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function status(): ?WaMessageStatus
    {
        return $this->status;
    }

    public function statusUpdatedAt(): ?DateTimeImmutable
    {
        return $this->statusUpdatedAt;
    }

    public function statusError(): ?string
    {
        return $this->statusError;
    }

    public function appointmentId(): ?int
    {
        return $this->appointmentId;
    }
}

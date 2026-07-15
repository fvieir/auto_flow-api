<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\DTOs;

use DateTimeImmutable;

final class UpdateWhatsAppMessageStatusDTO
{
    public function __construct(
        public readonly string $wamid,
        public readonly string $status,
        public readonly ?DateTimeImmutable $updatedAt,
        public readonly ?string $errorMessage,
    ) {
    }

    /**
     * @param  array<string, mixed>  $status  item de value.statuses[] do payload do Meta
     */
    public static function fromMetaStatus(array $status): self
    {
        $timestamp = isset($status['timestamp'])
            ? (new DateTimeImmutable())->setTimestamp((int) $status['timestamp'])
            : null;

        return new self(
            wamid: (string) $status['id'],
            status: (string) $status['status'],
            updatedAt: $timestamp,
            errorMessage: $status['errors'][0]['title'] ?? null,
        );
    }
}

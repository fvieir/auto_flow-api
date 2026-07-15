<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\DTOs;

use DateTimeImmutable;

final class ReceiveWhatsAppMessageDTO
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly string $from,
        public readonly string $externalId,
        public readonly string $wamid,
        public readonly string $type,
        public readonly ?string $body,
        public readonly array $payload,
        public readonly ?string $contextWamid,
        public readonly ?DateTimeImmutable $receivedAt,
    ) {
    }

    /**
     * @param  array<string, mixed>  $message  item de value.messages[] do payload do Meta
     * @param  list<array<string, mixed>>  $contacts  value.contacts[] do payload do Meta
     */
    public static function fromMetaMessage(array $message, array $contacts): self
    {
        $from = (string) $message['from'];

        $waId = $from;
        foreach ($contacts as $contact) {
            if (($contact['wa_id'] ?? null) === $from) {
                $waId = (string) $contact['wa_id'];
                break;
            }
        }

        $timestamp = isset($message['timestamp'])
            ? (new DateTimeImmutable())->setTimestamp((int) $message['timestamp'])
            : null;

        return new self(
            from: $from,
            externalId: $waId,
            wamid: (string) $message['id'],
            type: (string) $message['type'],
            body: $message['type'] === 'text' ? ($message['text']['body'] ?? null) : null,
            payload: $message,
            contextWamid: $message['context']['id'] ?? null,
            receivedAt: $timestamp,
        );
    }
}

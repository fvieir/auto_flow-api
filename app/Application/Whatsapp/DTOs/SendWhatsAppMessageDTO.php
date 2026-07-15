<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\DTOs;

use App\Domain\Whatsapp\Enums\WaMessageSenderType;
use App\Presentation\Http\Requests\Whatsapp\SendWhatsAppMessageRequest;

final class SendWhatsAppMessageDTO
{
    /**
     * @param  ?array<string, mixed>  $interactive
     */
    public function __construct(
        public readonly int $conversationId,
        public readonly string $type,
        public readonly ?string $body,
        public readonly bool $previewUrl,
        public readonly ?string $mediaId,
        public readonly ?string $mediaLink,
        public readonly ?string $caption,
        public readonly ?string $filename,
        public readonly bool $voice,
        public readonly ?array $interactive,
        public readonly string $senderType,
        public readonly ?int $senderId,
    ) {
    }

    public static function fromRequest(SendWhatsAppMessageRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        $type = $validated['type'];
        $media = $validated[$type] ?? [];

        return new self(
            conversationId: (int) $validated['conversation_id'],
            type: $type,
            body: $type === 'text' ? $media['body'] : ($media['caption'] ?? null),
            previewUrl: (bool) ($media['preview_url'] ?? false),
            mediaId: $media['id'] ?? null,
            mediaLink: $media['link'] ?? null,
            caption: $media['caption'] ?? null,
            filename: $media['filename'] ?? null,
            voice: (bool) ($media['voice'] ?? false),
            interactive: $type === 'interactive' ? $media : null,
            senderType: $validated['sender_type'],
            senderId: $validated['sender_type'] === WaMessageSenderType::Employee->value
                ? (int) $request->user('sanctum')->id
                : (isset($validated['sender_id']) ? (int) $validated['sender_id'] : null),
        );
    }
}

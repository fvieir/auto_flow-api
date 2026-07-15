<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\DTOs;

use App\Presentation\Http\Requests\Whatsapp\StartConversationRequest;

final class StartConversationDTO
{
    public function __construct(
        public readonly ?int $channelContactId,
        public readonly ?int $clientId,
        public readonly ?string $phone,
        public readonly ?string $name,
        public readonly ?string $document,
        public readonly ?int $waPhoneNumberId,
        public readonly int $attendantId,
    ) {
    }

    public static function fromRequest(StartConversationRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            channelContactId: isset($validated['channel_contact_id']) ? (int) $validated['channel_contact_id'] : null,
            clientId: isset($validated['client_id']) ? (int) $validated['client_id'] : null,
            phone: $validated['phone'] ?? null,
            name: $validated['name'] ?? null,
            document: $validated['document'] ?? null,
            waPhoneNumberId: isset($validated['wa_phone_number_id']) ? (int) $validated['wa_phone_number_id'] : null,
            attendantId: (int) $request->user()->id,
        );
    }
}

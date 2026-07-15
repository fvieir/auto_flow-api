<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\DTOs;

use App\Presentation\Http\Requests\Whatsapp\UpdateConversationStageRequest;

final class UpdateConversationStageDTO
{
    public function __construct(
        public readonly string $stage,
        public readonly ?int $appointmentId,
        public readonly ?string $reason,
    ) {
    }

    public static function fromRequest(UpdateConversationStageRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(
            stage: $validated['stage'],
            appointmentId: isset($validated['appointment_id']) ? (int) $validated['appointment_id'] : null,
            reason: $validated['reason'] ?? null,
        );
    }
}

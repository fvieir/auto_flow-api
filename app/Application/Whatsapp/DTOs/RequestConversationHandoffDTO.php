<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\DTOs;

use App\Presentation\Http\Requests\Whatsapp\RequestConversationHandoffRequest;

final class RequestConversationHandoffDTO
{
    public function __construct(
        public readonly string $subject,
    ) {
    }

    public static function fromRequest(RequestConversationHandoffRequest $request): self
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new self(subject: $validated['subject']);
    }
}

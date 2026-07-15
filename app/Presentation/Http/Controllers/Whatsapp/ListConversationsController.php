<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Whatsapp;

use App\Application\Whatsapp\UseCases\ListConversationsUseCase;
use App\Presentation\Http\Requests\Whatsapp\ListConversationsRequest;
use App\Presentation\Http\Resources\Whatsapp\WaConversationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListConversationsController
{
    public function __construct(private readonly ListConversationsUseCase $useCase)
    {
    }

    public function __invoke(ListConversationsRequest $request): AnonymousResourceCollection
    {
        /** @var array<string, mixed> $filters */
        $filters = $request->validated();

        $conversations = $this->useCase->handle($filters['stage'] ?? null);

        return WaConversationResource::collection($conversations);
    }
}

<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Whatsapp;

use App\Application\Whatsapp\DTOs\UpdateConversationStageDTO;
use App\Application\Whatsapp\UseCases\UpdateConversationStageUseCase;
use App\Presentation\Http\Requests\Whatsapp\UpdateConversationStageRequest;
use App\Presentation\Http\Resources\Whatsapp\WaConversationResource;
use Illuminate\Http\JsonResponse;

final class UpdateConversationStageController
{
    public function __construct(private readonly UpdateConversationStageUseCase $useCase)
    {
    }

    public function __invoke(int $conversation, UpdateConversationStageRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($conversation, UpdateConversationStageDTO::fromRequest($request));

        return WaConversationResource::make($updated)->response();
    }
}

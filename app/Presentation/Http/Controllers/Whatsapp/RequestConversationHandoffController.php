<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Whatsapp;

use App\Application\Whatsapp\DTOs\RequestConversationHandoffDTO;
use App\Application\Whatsapp\UseCases\RequestConversationHandoffUseCase;
use App\Presentation\Http\Requests\Whatsapp\RequestConversationHandoffRequest;
use App\Presentation\Http\Resources\Whatsapp\WaConversationResource;
use Illuminate\Http\JsonResponse;

final class RequestConversationHandoffController
{
    public function __construct(private readonly RequestConversationHandoffUseCase $useCase)
    {
    }

    public function __invoke(int $conversation, RequestConversationHandoffRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($conversation, RequestConversationHandoffDTO::fromRequest($request));

        return WaConversationResource::make($updated)->response();
    }
}

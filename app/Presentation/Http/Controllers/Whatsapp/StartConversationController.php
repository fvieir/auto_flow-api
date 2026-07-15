<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Whatsapp;

use App\Application\Whatsapp\DTOs\StartConversationDTO;
use App\Application\Whatsapp\UseCases\StartConversationUseCase;
use App\Presentation\Http\Requests\Whatsapp\StartConversationRequest;
use App\Presentation\Http\Resources\Whatsapp\WaConversationResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StartConversationController
{
    public function __construct(private readonly StartConversationUseCase $useCase)
    {
    }

    public function __invoke(StartConversationRequest $request): JsonResponse
    {
        $conversation = $this->useCase->handle(StartConversationDTO::fromRequest($request));

        return WaConversationResource::make($conversation)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}

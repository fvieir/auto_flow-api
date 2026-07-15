<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Whatsapp;

use App\Application\Whatsapp\DTOs\SendWhatsAppMessageDTO;
use App\Application\Whatsapp\UseCases\SendWhatsAppMessageUseCase;
use App\Presentation\Http\Requests\Whatsapp\SendWhatsAppMessageRequest;
use App\Presentation\Http\Resources\Whatsapp\WaMessageResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SendWhatsAppMessageController
{
    public function __construct(private readonly SendWhatsAppMessageUseCase $useCase)
    {
    }

    public function __invoke(SendWhatsAppMessageRequest $request): JsonResponse
    {
        $message = $this->useCase->handle(SendWhatsAppMessageDTO::fromRequest($request));

        return WaMessageResource::make($message)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}

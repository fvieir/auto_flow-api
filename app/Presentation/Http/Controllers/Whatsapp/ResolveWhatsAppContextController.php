<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Whatsapp;

use App\Application\Whatsapp\UseCases\ResolveWhatsAppContextUseCase;
use App\Presentation\Http\Requests\Whatsapp\ResolveWhatsAppContextRequest;
use App\Presentation\Http\Resources\Client\ClientResource;
use Illuminate\Http\JsonResponse;

final class ResolveWhatsAppContextController
{
    public function __construct(private readonly ResolveWhatsAppContextUseCase $useCase)
    {
    }

    public function __invoke(ResolveWhatsAppContextRequest $request): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        $result = $this->useCase->handle($validated['phone'], $validated['phone_number_id']);

        return response()->json([
            'tenant_id' => $result['tenantId'],
            'client' => $result['client'] !== null ? ClientResource::make($result['client']) : null,
        ]);
    }
}

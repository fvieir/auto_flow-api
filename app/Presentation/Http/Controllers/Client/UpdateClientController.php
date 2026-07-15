<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\DTOs\UpdateClientDTO;
use App\Application\Client\UseCases\UpdateClientUseCase;
use App\Presentation\Http\Requests\Client\UpdateClientRequest;
use App\Presentation\Http\Resources\Client\ClientResource;
use Illuminate\Http\JsonResponse;

final class UpdateClientController
{
    public function __construct(private readonly UpdateClientUseCase $useCase)
    {
    }

    public function __invoke(int $client, UpdateClientRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($client, UpdateClientDTO::fromRequest($request));

        return ClientResource::make($updated)->response();
    }
}
